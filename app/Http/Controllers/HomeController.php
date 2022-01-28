<?php

namespace App\Http\Controllers;

use App\Http\Repositories\CharityTickerRepository;
use App\Http\Requests\CreateCharitySearchRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;

class HomeController extends Controller
{

    /**
     * @var $charityTickerRepo
     */
    private $charityTickerRepo;

    /**
     * CharityTickerController Constructor
     * @param CharityTickerRepository $charityTickerRepo
     */
    public function __construct(
        CharityTickerRepository $charityTickerRepo
    ) {
        $this->charityTickerRepo = $charityTickerRepo;
    }
    /**
     * Show home page
     * @return \Illuminate\View\View
     */
    public function index()
    {
        if (Auth::user()) {
            Auth::logout();
        }
        return view('home', ['title' => 'Home', 'organizations' => $this->charityTickerRepo->getOrgList()]);
    }

    /**
     * Show the thank you page after charity
     *
     * @return \Illuminate\View\View
     */
    public function thankyou(Request $request, $charity_code)
    {
        try {
            if (Auth::user()) {
                Auth::logout();
            }
            $codeDetails = $this->charityTickerRepo->getCharityDetailsByCode($charity_code);
            return view('thankyou', ['title' => 'Thank You', 'charity' => $codeDetails]);
        } catch (\Exception $exception) {
            return redirect()->route('home')->withError(config('message.invalid_ch'));
        }
    }

    /**
     * Verify user and set password
     *
     * @return \Illuminate\View\View
     */
    public function verify(Request $request, $token)
    {
        try {
            $user = $this->charityTickerRepo->verifyUserFromEmailToken($token);
            return redirect()->route('charity', ['charity_code' => $user->charity_ticker->charity_code]);
        } catch (\Exception $exception) {
            Log::error($exception, [$exception]);
            return redirect()->route('home')->withError(config('message.invalid_ch'));
        }
    }

    /**
     * Show the charity detail page
     *
     * @return \Illuminate\View\View
     */
    public function charityDetails(Request $request, $charity_code)
    {
        try {
            Log::notice("HomeController::charityDetails - dispatching ScheduleExpireCharities");
            \App\Jobs\ScheduleExpireCharities::dispatch();
            Log::notice("HomeController::charityDetails - dispatched ScheduleExpireCharities");
            $codeDetails = $this->charityTickerRepo->verifyUserFromCharityCode($charity_code);
            return view('charity', ['title' => 'View Charity ' . $codeDetails->charity_code, 'charity' => $codeDetails, 'user' => $codeDetails->user]);
        } catch (\Exception $exception) {
            return redirect()->route('home')->withError(config('message.invalid_ch'));
        }
    }

    /**
     * Show the charity detail page
     *
     * @return \Illuminate\View\View
     */
    public function charitySearch(CreateCharitySearchRequest $request)
    {
        try {
            $codeDetails = $this->charityTickerRepo->verifyUserWithCode($request->only(['s_email', 's_password', 's_code']));
            return redirect()->route('charity', ['charity_code' => $codeDetails->charity_ticker->charity_code]);
        } catch (\Exception $exception) {
            Session::flash('s_message', config('message.invalid_ch'));
            return redirect()->back()->withInput();
        }
    }

    public function stopCharity(Request $request, $charity_code)
    {
        try {
            $codeDetails = $this->charityTickerRepo->stopUserCharity($charity_code);
            return redirect()->route('charity', ['charity_code' => $codeDetails->charity_ticker->charity_code]);
        } catch (\Exception $exception) {
            return redirect()->route('home')->withError(config('message.invalid_ch'));
        }
    }
}
