{{-- <hr class="my-4 my-5 "> --}}
<div class="row my-4 my-5">
    <div class="col-md-12">
        <div class="text-center">
            <h1 class="display-8 fw-bold">Time To Give !</h1>
        </div>
    </div>
</div>

@if (session('error'))
    <div class="alert alert-danger" role="alert">
        {{ session('error') }}
    </div>
@endif
<div class="row g-5 mt-5" id="payment">
    <div class="col-sm-12 col-md-12 col-lg-5 d-md-none d-lg-block">
        <img src="{{ asset('/img/charity.png') }}" alt="" style="width: 400px">
    </div>
    <div class="col-sm-12 col-md-12 col-lg-7">
        <div class="shadow p-3 mb-5 bg-white rounded">
            <div class="card-body">
                <h2 class="mb-3">Billing Info</h2>
                <hr class="mb-4 ">
                <form action="{{ route('createTick') }}" method="POST" autocomplete="off" id="paymentForm"
                    style="position: relative">
                    @csrf
                    <div class="row g-3">
                        <div class="col-sm-12 col-md-6">
                            <label for="first_name" class="form-label">First name</label>
                            <input type="text" name="first_name" id="first_name"
                                class="form-control @error('first_name') is-invalid @enderror" placeholder="John"
                                value="{{ old('first_name') }}" required>
                            @error('first_name')
                                @include('shared.error',['message'=>$message])
                            @enderror
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <label for="last_name" class="form-label">Last name</label>
                            <input type="text" name="last_name" id="last_name"
                                class="form-control @error('last_name') is-invalid @enderror" placeholder="Doe"
                                value="{{ old('last_name') }}" required>
                            @error('last_name')
                                @include('shared.error',['message'=>$message])
                            @enderror
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}"
                                class="form-control @error('email') is-invalid @enderror" placeholder="you@example.com"
                                required>
                            @error('email')
                                @include('shared.error',['message'=>$message])
                            @enderror
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <label for="donation_amount" class="form-label">Amount</label>
                            <div class="input-group">
                                <div class="input-group-text">$</div>
                                <input type="number" value="{{ old('donation_amount') }}" name="donation_amount"
                                    class="form-control @error('donation_amount') is-invalid @enderror"
                                    placeholder="How much?" step="0.01" min="0.01" required>
                                @error('donation_amount')
                                    @include('shared.error',['message'=>$message])
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <label class="form-label" for="tick_frequency">Frequency</label>
                            <div class="input-group">
                                <div class="input-group-text">Every</div>
                                <input type="number" value="{{ old('tick_frequency') }}" name="tick_frequency"
                                    class="form-control @error('tick_frequency') is-invalid @enderror"
                                    placeholder="How often?" step="1" min="1" required>
                                <select class="form-select @error('tick_frequency_unit') is-invalid @enderror"
                                    name="tick_frequency_unit" required>
                                    <option selected="">Choose…</option>
                                    <option value="kdei" {{ old('tick_frequency_unit') == 'kdei' ? 'selected' : '' }}>
                                        3
                                        Seconds
                                        (~Kedei Dibur)</option>
                                    <option value="mins" {{ old('tick_frequency_unit') == 'mins' ? 'selected' : '' }}>
                                        Minutes
                                    </option>
                                    <option value="hours"
                                        {{ old('tick_frequency_unit') == 'hours' ? 'selected' : '' }}>Hours
                                    </option>
                                    <option value="days"
                                        {{ old('tick_frequency_unit') == 'days' ? 'selected' : '' }}>Days
                                    </option>
                                </select>
                                @error('tick_frequency')
                                    @include('shared.error',['message'=>$message])
                                @enderror
                                @error('tick_frequency_unit')
                                    @include('shared.error',['message'=>$message])
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <label class="form-label" for="tick_frequency">Select Organization or Add New</label>
                            <select class="form-control @error('charity_organization_id') is-invalid @enderror"
                                id="tomSelect" name="charity_organization_id"
                                placeholder="Search for your charity organization" autocomplete="off"
                                value="{{ old('charity_organization_id') }}" required>
                                <option value="">Search for your charity organization</option>
                                @foreach ($organizations as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                            @error('charity_organization_id')
                                @include('shared.error',['message'=>$message])
                            @enderror
                        </div>

                        <div class="col-sm-12 col-md-6">
                            <div class="form-check">
                                <label for="hasSubscribed" class="form-label">&nbsp;</label>
                            </div>
                            <div class="form-check" title="Continue the charity until stopped">
                                <input class="form-check-input" name="hasSubscribed" id="hasSubscribed" type="checkbox"
                                    value="1" @if (!old('timer_expiry_timestamp')) checked="checked" @endif>
                                <label class="form-check-label" for="hasSubscribed">
                                    Keep it going until I stop (you will receive occasional reminders)
                                </label>
                            </div>
                        </div>
                        {{-- <div class="vl d-md-none d-lg-block"></div> --}}
                        <div class="col-sm-12 col-md-6">
                            <label for="first_name" class="form-label">Expiry Time</label>
                            <input type="text" @if (!old('timer_expiry_timestamp')) disabled @endif name="timer_expiry_timestamp"
                                id="datetimepicker"
                                class="form-control @error('timer_expiry_timestamp') is-invalid @enderror"
                                placeholder="2014/03/15 15:06" value="{{ old('timer_expiry_timestamp') }}" title="Choose a date time to expire the charity">
                            @error('timer_expiry_timestamp')
                                @include('shared.error',['message'=>$message])
                            @enderror
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-6">
                            <div class="form-group">
                                <label for="card-element">Credit Card</label>
                                <div id="card-element">
                                    <!-- a Stripe Element will be inserted here. -->
                                </div>
                                <!-- Used to display form errors -->
                                <div id="card-errors" role="alert"></div>
                            </div>
                        </div>
                        <div class="col-md-12 col-lg-12 col-sm-12">
                            <button class="btn btn-primary" type="submit">Start</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@push('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('datepicker/jquery.datetimepicker.min.css') }}" />
    <style>
        .vl {
            border-left: 2px solid #08080863;
            height: 55px;
            position: absolute;
            left: 50%;
            margin-left: 2px;
            bottom: 30%;
            width: 3px;
        }

        .StripeElement {
            background-color: white;
            padding: 10px 12px;
            border-radius: 4px;
            border: 1px solid #ccd0d2;
            box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
            -webkit-transition: box-shadow 150ms ease;
            transition: box-shadow 150ms ease;
        }

        .StripeElement--focus {
            box-shadow: 0 1px 3px 0 #cfd7df;
        }

        .StripeElement--invalid {
            border-color: #fa755a;
        }

        .StripeElement--webkit-autofill {
            background-color: #fefde5 !important;
        }

        #card-errors {
            color: #fa755a;
        }

    </style>
@endpush
@push('scripts')
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('datepicker/jquery.datetimepicker.full.min.js') }}"></script>

    <script type="text/javascript">
        window.addEventListener("load", function() {
            $('#datetimepicker').datetimepicker({
                minDate: new Date(),
                format: 'Y/m/d H:i',
                value: "{{ old('timer_expiry_timestamp') }}",
            });
            $('#hasSubscribed').change(function() {
                if (this.checked) {
                    $("#datetimepicker").prop('disabled', true);
                    $("#datetimepicker").prop('value', '');
                } else {
                    $("#datetimepicker").prop('disabled', false);
                }
            });

            var tomSelect = new TomSelect("#tomSelect", {
                create: true,
                createFilter: function(input) {
                    input = input.toLowerCase();
                    console.log(input, 'input');
                    return !(input in this.options);
                },
                onItemAdd: function() {
                    this.setTextboxValue('');
                    this.refreshOptions();
                },
                persist: false,
                create: function(input, callback) {
                    const data = {
                        name: input
                    }
                    axios.post('/api/create-org', data)
                        .then(function(response) {
                            callback({
                                value: response.data.data.id,
                                text: response.data.data.name
                            })
                        })
                        .catch(function(error) {
                            let msg = 'An error occurred.';
                            console.log(error.response.data.errors);
                            if (error.response) {
                                alert(error.response.data.message || msg);
                            } else if (error.request) {
                                alert(msg);
                            } else {
                                alert(msg);
                            }
                        });
                },
                onChange: function() {
                    this.wrapper.classList.toggle('is-invalid', !this.isValid);
                }
            });

            @if (old('charity_organization_id'))
                tomSelect.setValue({{ old('charity_organization_id') }},true);
                setTimeout(() => {
                tomSelect.close();
                tomSelect.blur();
                }, 10);
            @endif
        });
    </script>
    <script type="text/javascript">
        window.addEventListener("load", function() {
            // Create a Stripe client
            var stripe = Stripe('{{ config('services.stripe.key') }}');
            // Create an instance of Elements
            var elements = stripe.elements();
            // Custom styling can be passed to options when creating an Element.
            // (Note that this demo uses a wider set of styles than the guide below.)
            var style = {
                base: {
                    color: '#32325d',
                    lineHeight: '18px',
                    fontFamily: '"Raleway", Helvetica, sans-serif',
                    fontSmoothing: 'antialiased',
                    fontSize: '16px',
                    '::placeholder': {
                        color: '#aab7c4'
                    }
                },
                invalid: {
                    color: '#fa755a',
                    iconColor: '#fa755a'
                }
            };
            // Create an instance of the card Element
            var card = elements.create('card', {
                style: style,
                hidePostalCode: true
            });
            // Add an instance of the card Element into the `card-element` <div>
            card.mount('#card-element');
            // Handle real-time validation errors from the card Element.
            card.addEventListener('change', function(event) {
                var displayError = document.getElementById('card-errors');
                if (event.error) {
                    displayError.textContent = event.error.message;
                } else {
                    displayError.textContent = '';
                }
            });
            // Handle form submission
            var form = document.getElementById('paymentForm');
            form.addEventListener('submit', function(event) {
                event.preventDefault();
                var options = {
                    name: `${document.getElementById('first_name').value} ${document.getElementById('last_name').value}`,
                    email: document.getElementById('email').value,
                }
                stripe.createToken(card, options).then(function(result) {
                    if (result.error) {
                        // Inform the user if there was an error
                        var errorElement = document.getElementById('card-errors');
                        errorElement.textContent = result.error.message;
                    } else {
                        // Send the token to your server
                        stripeTokenHandler(result.token);
                    }
                });
            });

            function stripeTokenHandler(token) {
                console.log(token, 'token');
                // Insert the token ID into the form so it gets submitted to the server
                var form = document.getElementById('paymentForm');
                var hiddenInput = document.createElement('input');
                hiddenInput.setAttribute('type', 'hidden');
                hiddenInput.setAttribute('name', 'stripe_token');
                hiddenInput.setAttribute('value', token.id);
                form.appendChild(hiddenInput);
                // Submit the form
                form.submit();
            }
        });
    </script>
@endpush
