<?php

namespace App\Http\Controllers;

use App\Http\Repositories\CharityTickerRepository;
use App\Http\Requests\CreateCharityTickerRequest;

class CharityTickerController extends Controller
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
     * Show the profile for a given user.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function store(CreateCharityTickerRequest $request)
    {
        try {
          $code = $this->charityTickerRepo->saveTickerNUser($request->all());
          return redirect()->route('thankyou', ['charity_code' => $code]);
        } catch (\Exception $exception) {
            return back()->withError($exception->getMessage())->withInput();
        }
        
    }
}
