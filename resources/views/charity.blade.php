@extends('layouts.app')

@section('title', $title)

@section('content')
    <div class="position-relative overflow-hidden bg-light">
        <div class="col-md-5 p-lg-5 mx-auto my-5">
            <h1 class="display-4 fw-normal">Punny headline</h1>
            <p class="lead fw-normal">And an even wittier subheading to boot. Jumpstart your marketing efforts with this
                example based on Apple’s marketing pages.</p>
            <a class="btn btn-outline-secondary" href="#">Coming soon</a>
        </div>
       
        <div class="row text-center" style="min-height: 180px; position:relative;">
          <div style="overflow:hidden;top:50px;left: 250px;display:none; position: absolute;" id="animate-el" class="animation-target">
            <img src="{{ asset('img/dl.png') }}" style="width: 47px;" alt="dollar" >
          </div>
            <div class="col-md-8" id="timer"></div>
            <div class="col-md-4" id="coin-wrapper" style="display: none">
                <div class="row">
                    <div class="col-lg-12">
                      <img src="{{ asset('img/dl3.png') }}" alt="Dollar" />
                    </div>
                    <div class="col-lg-12">
                      <h1 class="fs-1 text-black text-center" id="odometer"></h1>
                    </div>
                </div>
            </div>
        </div>
        <div class="product-device shadow-sm d-none d-md-block"></div>
        <div class="product-device product-device-2 shadow-sm d-none d-md-block"></div>
    </div>
    {{-- <hr class="my-4 my-5 "> --}}

@endsection

@push('styles')
    <link href="{{ asset('css/odometer-theme-car.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/animation.css') }}" rel="stylesheet" />
    <style>
      .odometer.odometer-auto-theme, .odometer.odometer-theme-car{
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
        /* #odometer::before{
          content: "$";
          padding-left:3px;
          left: -30px;
          font-size: 2.5rem; 
          position:absolute;
        } */
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('js/odometer.min.js') }}"></script>
    <script>
        window.addEventListener("load", function() {
            let text1 = document.getElementById('dl');
            let donation_amount = {{ $charity->donation_amount }};
            let allDonation = 0;
            const TIME_LIMIT = {{ getSecondsFromTick($charity->tick_frequency, $charity->tick_frequency_unit) }};
            //const TIME_LIMIT = 1300000;
            let timePassed = 0;
            let timeLeft = TIME_LIMIT;
            let timerInterval = null;
            formatTime(TIME_LIMIT);
            startTimer();

            let count = document.getElementById('odometer');

            od = new Odometer({
              el: count,
              value: 0,

              // Any option (other than auto and selector) can be passed in here
              format: '(,ddd).ddd',
              theme: 'car',
            });

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
                if(ell.style.display === 'none'){
                  ell.style.display = '';  
                }
                var ell = document.getElementById("animate-el");
                ell.style.display = '';
                count.innerHTML = lastVal;
                setTimeout(() => {
                  ell.style.display = 'none';
                }, (TIME_LIMIT <=3 ? 5000:4000));
            }

            function bounceCoin(){
              var bounce = new Bounce();
            }

            function onTimesUp() {
                clearInterval(timerInterval);
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
                if(template){
                  document.getElementById("timer")
                    .innerHTML = template;
                }
            }
        });
    </script>
@endpush
