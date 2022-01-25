@extends('layouts.app')

@section('title', $title)

@section('content')
    {{-- {{auth()->user()->id}} --}}
    @php
    $timer_start = $charity->timer_start;
    if (!$timer_start) {
        // $timer_start = $user->email_verified_at;
        $timer_start = config('timetogive.mode') == 'deposit' ? new \Carbon\Carbon($user->email_verified_at, $charity->timezone)->format('Y-m-d H:i:s') : $user->email_verified_at;
        Log::debug(__FUNCTION__.':'.__LINE__, [
            'new Carbon' => new \Carbon\Carbon($user->email_verified_at),
            'new Carbon_ts' => (new \Carbon\Carbon($user->email_verified_at))->timestamp,
            'new Carbon tz' => new \Carbon\Carbon($user->email_verified_at, $charity->timezone),
            'new Carbon tz_ts' => new \Carbon\Carbon($user->email_verified_at, $charity->timezone)->timestamp,
            'email_verified_at' => $user->email_verified_at,
            'timer_start' => $timer_start,
            'charity->timer_start' => $charity->timer_start
        ]);
    }
    $isCom = $charity->timer_completed_at ? 1 : 0;
    $totalAmount = calTotalDonationAmount($timer_start, $isCom ? $charity->timer_completed_at : now(), $charity->donation_amount, $charity->tick_frequency, $charity->tick_frequency_unit);
    Log::debug(__FUNCTION__.':'.__LINE__, [
        'now' => now(),
        'now_ts' => now()->timestamp,
        'now tz' => now($charity->timezone),
        'now tz_ts' => now($charity->timezone)->timestamp,
        'charity' => $charity,
        'isCom' => $isCom,
        'totalAmount' => $totalAmount
    ]);
    @endphp
    <div class="position-relative overflow-hidden bg-light">
        @include('charity.social')
        <div class="col-md-5 p-lg-5 mx-auto my-2">
            <h1 class="display-4 fw-normal">TzedokoMeter</h1>
            <p class="lead fw-normal">Your giving status: How much you've given, giving, and remaining to give until completion.</p>
            <a class="btn btn-outline-secondary" href="{{ route('home') . '#payment' }}">Start Another One</a>
        </div>
        <hr class="my-4 my-3 ">
        @if (session('error'))
            <div class="alert alert-danger" role="alert">
                {{ session('error') }}
            </div>
        @endif
        <div class="row" style="min-height: 180px; position:relative;">
            @if (!$charity->timer_completed_at)
                <div style="overflow:hidden;top:50px;left:calc(100% - 80%);display:none; position: absolute;"
                    id="animate-el" class="animation-target text-center">
                    <img src="{{ asset('img/dl.png') }}" style="width: 47px;" alt="dollar">
                </div>
                <div class="col-md-8 text-center" id="timer"></div>
            @else
                <div class="col-md-12 text-center">
                    <h1 class="display-7 fw-bold text-success mt-4 mb-5 ">Congratulations! Your charity has been completed.
                    </h1>
                </div>
                <div class="col-md-6 col-sm-12 text-left">
                    <ul class="list-group">
                        <li class="list-group-item">
                            <strong>Start:</strong> <span data-time="{{ $timer_start }}" class="format-time"></span>
                        </li>
                        <li class="list-group-item">
                            <strong>End:</strong> <span data-time="{{ $charity->timer_completed_at }}"
                                class="format-time"></span>
                        </li>
                        <li class="list-group-item">
                            <strong>Amount:</strong> {!! formatDonationAmountText($charity->donation_amount, $charity->tick_frequency, $charity->tick_frequency_unit, $charity->charity_organization) !!}
                        </li>
                        <li class="list-group-item">
                            <strong>Total Amount:</strong> $ {{ number_format($charity->total_donation_amount,2) }}
                        </li>
                    </ul>
                </div>
            @endif
            <div class="col-md-4 col-sm-12 text-center" id="coin-wrapper" style="visibility: hidden">
                <div class="row">
                    <div class="col-lg-12">
                        <img src="{{ asset('img/dl3.png') }}" alt="Dollar" />
                    </div>
                    <div class="col-lg-12">
                        <h1 class="fs-1 text-black text-center" id="odometer"></h1>
                    </div>
                </div>
            </div>
            @auth
                @if (!$charity->timer_completed_at)
                    @if ($charity->timer_expiry_timestamp)
                        <div class="col-md-12 mb-3">
                            <strong>Completed In: </strong>
                            <span id="expiry_time"></span>
                        </div>
                    @endif
                    <div class="col-md-4">
                        <form action="{{ route('showConfirmForm', ['charity_code' => $charity->charity_code]) }}"
                            method="get">
                            <button type="submit" class="btn btn-primary">Stop Timer</button>
                        </form>
                    </div>
                @endif
            @endauth
        </div>
    </div>
@endsection

@push('styles')
    <link href="{{ asset('css/odometer-theme-car.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/animation.css') }}" rel="stylesheet" />
    <style>
        .odometer.odometer-auto-theme,
        .odometer.odometer-theme-car {
            background: none;
        }

        .odometer .odometer-inside:before {
            content: "$";
        }

        .odometer {
            font-size: 100px;
            color: #27B157
        }

        .odometer .odometer-inside:before {
            display: inline-block;
            vertical-align: sup;
            font-size: .85em;
            margin-right: .12em
        }

        .timer {
            font-size: 3em;
            font-weight: 100;
            color: white;
            border-radius: 10px;
            border: 2px solid #030d52;
            padding: 10px;
            background: #020b43;
            margin: 10px;
            text-align: center;
            box-shadow: 1px 1px 6px 1px rgb(23 23 163 / 25%);
        }

        .timer span {
            color: #ffffff;
            display: block;
            margin-top: 10px;
            font-size: .35em;
            font-weight: 400;
        }

        .coin {
            font-size: 3em;
            font-weight: 100;
            padding: 10px;
            margin: 10px;
            text-align: center;
        }

        .t-wrap {
            width: 150px;
            display: inline-block;
        }

    </style>
@endpush

@push('scripts')
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/odometer.min.js') }}"></script>
    <script src="{{ asset('js/timer.js') }}"></script>
    <script>
        window.addEventListener("load", function() {

            async function checkIsTimerExpire() {
                let charity_code = "{{ $charity->charity_code }}";
                await axios.get(`/check-timer/${charity_code}?tm=${moment().utcOffset()}`)
                    .then(res => {
                        //document.getElementById('expiry_time').innerHTML = res.data.timeText;
                        if (res.data && res.data.ok == true) {
                            window.location.reload();
                        }
                    })
                    .catch(err => console.log(err));
            }

            // Setup remaining time
            // Update the count down every 1 second
            @if($charity->timer_expiry_timestamp)
                //let countDownDate = new Date("{{ date('c', strtotime($charity->timer_expiry_timestamp)) }}").getTime();
                let countDownDate = moment("{{ $charity->timer_expiry_timestamp }}").valueOf();
                var ctTimer = setInterval(function() {
                    // Get today's date and time
                    // Find the distance between now and the count down date
                    // Get today's date and time
                    //var now = new Date().getTime();
                    var now=moment().valueOf();
                
                    // Find the distance between now and the count down date
                    var distance = countDownDate - now;
                    // Time calculations for days, hours, minutes and seconds
                    var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    var seconds = Math.floor((distance % (1000 * 60)) / 1000);
                
                    // Output the result in an element with id="demo"
                    document.getElementById("expiry_time").innerHTML = days + "d " + hours + "h " +
                    minutes + "m " + seconds + "s ";
                
                    // If the count down is over, write some text
                    if (distance < 0) {
                        clearInterval(ctTimer);
                        document.getElementById("expiry_time").innerHTML="Completed";
                        checkIsTimerExpire();
                    }
                }, 1000);
            @endif

            let text1 = document.getElementById('dl');
            let donation_amount = {{ $charity->donation_amount }};

            let allDonation = {{ $totalAmount }};

            let isCompeted = {{ $isCom }};
            const TIME_LIMIT = {{ getSecondsFromTick($charity->tick_frequency, $charity->tick_frequency_unit) }};
            let count = document.getElementById('odometer');

            od = new Odometer({
                el: count,
                value: isCompeted ? 0 : allDonation,
                // Any option (other than auto and selector) can be passed in here
                format: '(,ddd).ddd',
                theme: 'car',
            });
            // slide from 0 to exact amount to nicely presentation
            if (isCompeted) {
                od.update(allDonation);
            }
            if (allDonation > 0) {
                var ell = document.getElementById("coin-wrapper");
                if (ell.style.visibility === 'hidden') {
                    ell.style.visibility = '';
                }
            }
            if (!isCompeted) {
                let timePassed = 0;
                let timeLeft = TIME_LIMIT;
                let timerInterval = null;
                formatTime(TIME_LIMIT);
                //checkIsTimerExpire();
                startTimer();

                function animate(obj, initVal, lastVal, duration) {
                    // let startTime = null;
                    // //get the current timestamp and assign it to the currentTime variable
                    // let currentTime = Date.now();
                    // //pass the current timestamp to the step function
                    // const step = (currentTime) => {
                    //     //if the start time is null, assign the current time to startTime
                    //     if (!startTime) {
                    //         startTime = currentTime;
                    //     }
                    //     //calculate the value to be used in calculating the number to be displayed
                    //     const progress = Math.min((currentTime - startTime) / duration, 1);
                    //     //calculate what to be displayed using the value gotten above
                    //     obj.innerHTML = Math.floor(progress * (lastVal - initVal) + initVal);
                    //     //checking to make sure the counter does not exceed the last value (lastVal)
                    //     if (progress < 1) {
                    //         window.requestAnimationFrame(step);
                    //     } else {
                    //         window.cancelAnimationFrame(window.requestAnimationFrame(step));
                    //     }
                    // };
                    // //start animating
                    // window.requestAnimationFrame(step);

                    var ell = document.getElementById("coin-wrapper");
                    if (ell.style.visibility === 'hidden') {
                        ell.style.visibility = '';
                    }
                    count.innerHTML = lastVal;
                    if (window.screen.width > 768) {
                        var ell = document.getElementById("animate-el");
                        ell.style.display = '';

                        setTimeout(() => {
                            ell.style.display = 'none';
                        }, (TIME_LIMIT <= 3 ? 5000 : 4000));
                    }
                }

                function bounceCoin() {
                    var bounce = new Bounce();
                }

                function onTimesUp() {
                    clearInterval(timerInterval);
                    //checkIsTimerExpire();
                    timePassed = 0;
                    timeLeft = TIME_LIMIT;
                    timerInterval = null;
                    formatTime(TIME_LIMIT);
                    startTimer();
                    let oldDon = allDonation;
                    allDonation += {{ $charity->donation_amount }};
                    // console.log(allDonation,'allDonation',allDonation.toFixed(2));
                    animate(text1, oldDon, allDonation.toFixed(2), 3000);
                }

                function startTimer() {
                    timerInterval = setInterval(() => {
                        timePassed = timePassed += 1;
                        timeLeft = TIME_LIMIT - timePassed;
                        formatTime(timeLeft);
                        if (timeLeft === 0) {
                            onTimesUp();
                        }
                    }, 1000);
                }

                function formatTime(seconds) {

                    seconds = Number(seconds);
                    var d = Math.floor(seconds / (3600 * 24));
                    var h = Math.floor(seconds % (3600 * 24) / 3600);
                    var m = Math.floor(seconds % 3600 / 60);
                    var s = Math.floor(seconds % 60);
                    let template = ``;
                    if (d > 0) {
                        template = `${template}<div class="t-wrap">
                        <div class="timer">
                            ${d}<span>Days</span>
                        </div>
                    </div>`;
                    }
                    if (h > 0) {
                        template = `${template}<div class="t-wrap">
                        <div class="timer">
                          ${h}<span>Hours</span>
                        </div>
                    </div>`;
                    }
                    if (m > 0) {
                        template = `${template}<div class="t-wrap">
                        <div class="timer">
                          ${m}<span>Minutes</span>
                        </div>
                    </div>`;
                    }
                    template = `${template}<div class="t-wrap">
                        <div class="timer">
                          ${s}<span>Seconds</span>
                        </div>
                    </div>`;
                    if (template) {
                        document.getElementById("timer")
                            .innerHTML = template;
                    }
                }
            }
        });
    </script>
@endpush
