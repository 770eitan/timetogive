<?php

namespace App\Http\Repositories;

use App\Events\UserPasswordEmailEvent;
use App\Events\VerifyEmailEvent;
use App\Models\CharityOrganization;
use App\Models\CharityTicker;
use App\Models\User;
use Carbon\Carbon;
use Cartalyst\Stripe\Stripe;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class CharityTickerRepository
{

    /**
     * Save charity ticker information and user.
     *
     * @param  array  $data
     * @return array
     */
    public function saveTickerNUser($request)
    {
        DB::beginTransaction();
        try {
            $data = $request->all();
            // Create user on stripe
            $stripe = Stripe::make(config('services.stripe.secret'));
            $customer = $stripe->customers()->create([
                'email' => $data['email'],
                'name' => $data['first_name'] . ' ' . $data['last_name'],
                'source' => $data['stripe_token']
            ]);
            $stripe_cus_id = $customer['id'];
            
            // Create user on database
            $user = new User;
            $user->stripe_cus_id = $stripe_cus_id;
            $user->email_verify_token = Str::random(60);
            $user->fill($data);
            $user->save();
            $userId = $user->id;

            // Create user charity ticker
            $charityTicker = new CharityTicker;
            $charityTicker->user_id = $userId;
            if (config('timetogive.mode')=='countup' && !$request->has('is_subscribed')) { // for 'deposit' will calculate correct timer_expiry_timestamp upon email verification
                $charityTicker->timer_expiry_timestamp = Carbon::createFromFormat('Y/m/d H:i', $data['timer_expiry_timestamp'])->format('Y-m-d H:i:s'); // user entered datetime - converted to timestamp
            } elseif (config('timetogive.mode')=='deposit') {
                $charityTicker->total_donation_amount = (double)$data['total_donation_amount']; // TODO currency data type...
            }
            $charityTicker->fill($data);

            // if (config('timetogive.mode') == 'countup') {
            //     // Create user card on stripe
            //     $card = $stripe->cards()->create($stripe_cus_id, $data['stripe_token']);
            // } else 
            $charityTicker->save();
            DB::commit();
            $details = $this->getCharityInfoById($charityTicker->id);
            VerifyEmailEvent::dispatch($user);
            //Mail::to($user->email)->send(new EmailVerify($user));
            return $details->charity_code;
        } catch (ValidationException $e) {
            DB::rollback();
            throw $e;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Get Charity ticker info by Id
     *
     * @return Collection CharityTicker
     */
    public function getCharityInfoById($id)
    {
        return CharityTicker::find($id);
    }

    /**
     * Get Charity ticker info by charity code
     *
     * @return Collection CharityTicker
     */
    public function getCharityDetailsByCode($charity_code)
    {
        return CharityTicker::where('charity_code', $charity_code)->with('user')->first();
    }

    /**
     * Get organizations lists
     *
     * @return Collection CharityOrganization
     */
    public function getOrgList()
    {
        return CharityOrganization::select(['id', 'name'])->where('status', 1)->orderBy('name', 'asc')->get();
    }

    /**
     * Verify user with email and password and charity code
     *
     * @return Collection User
     */
    public function verifyUserWithCode(array $data)
    {
        $code = $data['s_code'];
        $userDetails = User::where(['email' => $data['s_email'], 'status' => 1])->whereHas('charity_ticker', function ($q) use ($code) {
            return $q->where('charity_code', $code);
        })->first();

        if (!$userDetails || !Hash::check($data['s_password'], $userDetails->password)) {
            throw new \ErrorException(config('message.search_err'));
        }
        return $userDetails;
    }

    /**
     * Verify user with email token
     *
     * @return Collection User
     */
    public function verifyUserFromEmailToken($token)
    {
        $user = User::where(['email_verify_token' => $token, 'status' => 0])->first();

        if (!$user) {
            throw new \ErrorException(config('message.invalid_ch'));
        }
        // get charity details
        $charityDt = CharityTicker::where(['user_id' => $user->id])->first();
        if (!$charityDt) {
            throw new \ErrorException(config('message.search_err'));
        }
        $userId = $user->id;
        $password = \Illuminate\Support\Collection::times(4, fn() => Str::random(5) )->join('-');
        $user->status = 1;
        $user->email_verify_token = null;
        $now = now();
        $user->email_verified_at = $now;
        Log::debug(__FUNCTION__.':'.__LINE__,['now' => $now, 'now_ts' => $now->timestamp, 'ny' => now('America/New_York'),'ny_ts' => now('America/New_York')->timestamp, 'email_verified_at' => $user->email_verified_at ]);
        $user->password = Hash::make($password);
        $user->save();

        $unittosec = [
            'sec' => 1,
            'mins' => 60,
            'hours' => 60 * 60,
            'days' => 60 * 60 * 24,
        ];

        $isdeposit = config('timetogive.mode')=='deposit';

        if ($isdeposit) {
            $tda = (int)($charityDt->total_donation_amount * 100);
            $da = (int)($charityDt->donation_amount * 100);
            $numcompletesteps = (int)($tda / $da);
            $remaindersteps = $tda % $da;
            $secondsbetweensteps = $charityDt->tick_frequency * $unittosec[$charityDt->tick_frequency_unit];
            $completestepstotalseconds = $numcompletesteps * $secondsbetweensteps;
            $remainderstepstotalseconds = (int)($remaindersteps * $secondsbetweensteps);
            $charityDt->timer_expiry_timestamp = now($user->timezone)->addSeconds($completestepstotalseconds + $remainderstepstotalseconds);
            Log::debug(__FUNCTION__.':'.__LINE__, ['now' => now(), 'now_ts' => now()->timestamp, 'usr' => now($user->timezone),'usr_ts' => now($user->timezone)->timestamp, 'timer_expiry_timestamp' => $charityDt->timer_expiry_timestamp ]);

            $org = CharityOrganization::find($charityDt->charity_organization_id);

            $charge = Stripe::make(config('services.stripe.secret'))->charges()->create([
                'customer' => $user->stripe_cus_id,
                'currency' => 'USD',
                'amount'   => $charityDt->total_donation_amount,
                'capture'  => config('timetogive.capture', false),
                // 'source'   => $data['stripe_token'],
                'description' => "TimeToGive charity ticker for {$org->name}",
                'metadata' => [
                    'user_id' => $userId,
                    'to' => $org->name,
                    'id' => $charityDt->id,
                ]
            ]);
            $charityDt->charge = $charge['id'];
        } // for 'countup' we set timer_expiry_timestamp as user submitted by form
        $charityDt->timer_start = $isdeposit ? now($user->timezone) : $now;
        Log::debug(__FUNCTION__.':'.__LINE__, ['now' => now(), 'now_ts' => now()->timestamp, 'usr' => now($user->timezone),'usr_ts' => now($user->timezone)->timestamp, 'timer_start' => $charityDt->timer_start ]);
        $charityDt->save();

        UserPasswordEmailEvent::dispatch($user, $password);
        //Mail::to($user->email)->send(new UserPassword($user, $password));
        return User::where(['id' => $userId, 'status' => 1])->whereHas('charity_ticker')->first();
    }

    /**
     * Verify user with email token
     *
     * @return Collection User
     */
    public function verifyUserFromCharityCode($charity_code)
    {
        $charityDt = CharityTicker::where('charity_code', $charity_code)
            ->whereHas('user', function ($q) {
                return $q->where(['status' => 1]);
            })
            ->with(['charity_organization'])
            ->first();
        if (!$charityDt) {
            throw new \ErrorException(config('message.invalid_ch'));
        }
        // Setup user session for stopping the charity timer
        Auth::loginUsingId($charityDt->user_id);
        return $charityDt;
    }

    /**
     * Stop user charity
     *
     * @return Collection CharityTicker
     */
    public function stopUserCharity($charity_code)
    {
        DB::beginTransaction();
        try {
            $user = Auth::user();
            $userId = $user->id;
            $charityDt = CharityTicker::where(['charity_code' => $charity_code, 'user_id' => $userId])->first();
            if (!$charityDt) {
                throw new \ErrorException(config('message.search_err'));
            }

            $isdeposit = config('timetogive.mode') == 'deposit';

            $timer_start = $charityDt->timer_start;
            if (!$timer_start) {
                $timer_start = $isdeposit ? (new \Carbon\Carbon($user->email_verified_at, $charityDt->timezone))->format('Y-m-d H:i:s') : $user->email_verified_at;
                Log::debug(__FUNCTION__.':'.__LINE__, [
                    'new Carbon' => new \Carbon\Carbon($user->email_verified_at),
                    'new Carbon_ts' => (new \Carbon\Carbon($user->email_verified_at))->timestamp,
                    'new Carbon tz' => new \Carbon\Carbon($user->email_verified_at, $charityDt->timezone),
                    'new Carbon tz_ts' => (new \Carbon\Carbon($user->email_verified_at, $charityDt->timezone))->timestamp,
                    'email_verified_at' => $user->email_verified_at,
                    'timer_start' => $timer_start,
                    'charityDt->timer_start' => $charityDt->timer_start
                ]);
                $charityDt->timer_start = $timer_start;
            }

            $time = now($isdeposit ? $user->timezone : null);
            Log::debug(__FUNCTION__.':'.__LINE__, ['now' => now(), 'now_ts' => now()->timestamp,
                'now_tz' => now($user->timezone),'now_tz_ts' => now($user->timezone)->timestamp, 'time' => $time ]);

            $origtotal = $charityDt->total_donation_amount;

            if ($isdeposit && "$time" > $charityDt->timer_expiry_timestamp) { // cap it; total_donation_amount remains its full total
                $charityDt->timer_completed_at = $charityDt->timer_expiry_timestamp;
            } else { // in all other cases - timer was ended early; total needs to be reduced
                $charityDt->timer_completed_at = $time;
                $charityDt->total_donation_amount = calTotalDonationAmount($timer_start, $time, $charityDt->donation_amount, $charityDt->tick_frequency, $charityDt->tick_frequency_unit);
            }
            if ($isdeposit && $charityDt->charge) {
                $stripe = Stripe::make(config('services.stripe.secret'));
                $charge = $stripe->charges()->find($charityDt->charge);
                if(!Arr::get($charge, 'captured')){
                    $charge = $stripe->charges()->capture($charityDt->charge, ['amount' => $charityDt->total_donation_amount]);
                    /////////////////////////////////////////////////////////////////////////////////////
                    //  Cartalyst\Stripe\Exception\MissingParameterException
                } else if ($origtotal > $charityDt->total_donation_amount) { // needs to reduce / refund...
                    $origfee = ($origtotal * 0.029) + 0.30;
                    $wouldvbeenfee = ($charityDt->total_donation_amount * 0.029) + 0.30;
                    $diffdontrefund = $origfee - $wouldvbeenfee;
                    $refund = $stripe->refunds()->create($charityDt->charge, $origtotal - $charityDt->total_donation_amount - $diffdontrefund, [
                        'metadata' => array_merge(['reason' => 'stopped from TimeToGive'], compact('origtotal','origfee','wouldvbeenfee','diffdontrefund')),
                        // 'reason' => 'requested_by_customer', // ?
                    ]);
                }
            } else {
                Log::error('Unexpected #9487948 - missing charge??', compact('user','charityDt','origtotal','isdeposit','time'));
            }

            $charityDt->save();
            DB::commit();
            return true;
        } catch (\Exception $e) {
            dd($e);
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Check expiry time and Stop user charity
     *
     * @return boolean
     */
    public function checkAutoStopTimer($charity_code, $offset)
    {
        try {
            $user = Auth::user();
            $userId = $user->id;
            $charityDt = CharityTicker::where(['charity_code' => $charity_code, 'user_id' => $userId])->first();
            if (!$charityDt) {
                throw new \ErrorException(config('message.search_err'));
            }
            // If user entered expiry time and its not completed
            $timeText = '';
            if ($charityDt->timer_expiry_timestamp && !$charityDt->timer_completed_at) {
                $this->stopUserCharity($charity_code); // TODO server-side check & confirm before stopping
                // $time = now();
                // // echo $charityDt->timer_expiry_timestamp;
                // // echo '--';
                // // echo $time;
                // $startDate = Carbon::parse($charityDt->timer_expiry_timestamp);
                // $endDate = Carbon::parse($time);
                // if(!$startDate->gt($endDate)) {
                //   $this->stopUserCharity($charity_code);
                //   return [
                //     'ok'=>false,
                //     'timeText' => 0
                //   ];
                // }
                // $timeText = getRemainingTime($charityDt->timer_expiry_timestamp);
            }
            return [
                'ok' => true,
                'timeText' => $timeText,
            ];
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
