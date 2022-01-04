<?php

namespace App\Http\Repositories;

use App\Events\UserPasswordEmailEvent;
use App\Events\VerifyEmailEvent;
use App\Models\CharityOrganization;
use App\Models\CharityTicker;
use App\Models\User;
use Carbon\Carbon;
use Cartalyst\Stripe\Stripe;
use Collegeman\Fuerte\Fuerte;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
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
            ]);
            $stripe_cus_id = $customer['id'];

            // Create user card on stripe
            $card = $stripe->cards()->create($stripe_cus_id, $data['stripe_token']);

            // Create user on database
            $token = Str::random(60);
            $user = new User;
            $user->stripe_cus_id = $stripe_cus_id;
            $user->email_verify_token = $token;
            $user->fill($data);
            $user->save();
            $userId = $user->id;

            // Create user charity ticker
            $charityTicker = new CharityTicker;
            $charityTicker->user_id = $userId;
            if (!$request->has('hasSubscribed')) {
                $charityTicker->timer_expiry_timestamp = Carbon::createFromFormat('Y/m/d H:i', $data['timer_expiry_timestamp'])->format('Y-m-d H:i:s'); // user entered datetime - converted to timestamp
            }
            $charityTicker->fill($data);
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
        $password = Fuerte::make();
        $user->status = 1;
        $user->email_verify_token = null;
        $user->email_verified_at = now();
        $user->password = Hash::make($password);
        $user->save();

        $charityDt->timer_start = now();
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
            $time = now();
            $timer_start = $charityDt->timer_start;
            if (!$timer_start) {
                $timer_start = $user->email_verified_at;
                $charityDt->timer_start = $timer_start;
            }
            $charityDt->timer_completed_at = $time;
            $charityDt->total_donation_amount = calTotalDonationAmount($timer_start, $time, $charityDt->donation_amount, $charityDt->tick_frequency, $charityDt->tick_frequency_unit);
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
                $this->stopUserCharity($charity_code);
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
