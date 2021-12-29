<hr class="my-4 my-5 ">
<div class="text-center">
    <h2>Time To Give !</h2>
</div>
@if (session('error'))
    <div class="alert alert-danger" role="alert">
        {{ session('error') }}
    </div>
@endif
<div class="row g-5" id="payment">
    <div class="col-md-12 col-lg-12">
        <h4 class="mb-3">Billing address</h4>
        <form action="{{ route('createTick') }}" method="POST" autocomplete="off" id="paymentForm">
            @csrf
            <div class="row g-3">
                <div class="col-sm-4">
                    <label for="first_name" class="form-label">First name</label>
                    <input type="text" name="first_name" id="first_name"
                        class="form-control @error('first_name') is-invalid @enderror" placeholder="John"
                        value="{{ old('first_name') }}" required>
                    @error('first_name')
                        @include('shared.error',['message'=>$message])
                    @enderror

                </div>
                <div class="col-sm-4">
                    <label for="last_name" class="form-label">Last name</label>
                    <input type="text" name="last_name" id="last_name"
                        class="form-control @error('last_name') is-invalid @enderror" placeholder="Doe"
                        value="{{ old('last_name') }}" required>
                    @error('last_name')
                        @include('shared.error',['message'=>$message])
                    @enderror
                </div>
                <div class="col-sm-4">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                        class="form-control @error('email') is-invalid @enderror" placeholder="you@example.com"
                        required>
                    @error('email')
                        @include('shared.error',['message'=>$message])
                    @enderror
                </div>
                <div class="col-sm-12 col-md-4">
                    <label class="visually-hidden" for="donation_amount">Amount</label>
                    <div class="input-group">
                        <div class="input-group-text">$</div>
                        <input type="number" value="{{ old('donation_amount') }}" name="donation_amount"
                            class="form-control @error('donation_amount') is-invalid @enderror" placeholder="How much?"
                            step="0.01" min="0.01" required>
                        @error('donation_amount')
                            @include('shared.error',['message'=>$message])
                        @enderror
                    </div>

                </div>
                <div class="col-sm-12 col-md-4">
                    <label class="visually-hidden" for="tick_frequency">Per</label>
                    <div class="input-group">
                        <div class="input-group-text">every</div>
                        <input type="number" value="{{ old('tick_frequency') }}" name="tick_frequency"
                            class="form-control @error('tick_frequency') is-invalid @enderror" placeholder="How often?"
                            step="1" min="1" required>
                        <select class="form-select @error('tick_frequency_unit') is-invalid @enderror"
                            name="tick_frequency_unit" required>
                            <option selected="">Choose…</option>
                            <option value="kdei" {{ old('tick_frequency_unit') == 'kdei' ? 'selected' : '' }}>3
                                Seconds
                                (~Kedei Dibur)</option>
                            <option value="mins" {{ old('tick_frequency_unit') == 'mins' ? 'selected' : '' }}>Minutes
                            </option>
                            <option value="hours" {{ old('tick_frequency_unit') == 'hours' ? 'selected' : '' }}>Hours
                            </option>
                            <option value="days" {{ old('tick_frequency_unit') == 'days' ? 'selected' : '' }}>Days
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
                <div class="col-sm-12 col-md-4">
                    <select class="form-control @error('charity_organization_id') is-invalid @enderror" id="tomSelect"
                        name="charity_organization_id" placeholder="Search for your charity organization"
                        autocomplete="off" value="{{ old('charity_organization_id') }}" required>
                        <option value="">Search for your charity organization</option>
                        @foreach ($organizations as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                    @error('charity_organization_id')
                        @include('shared.error',['message'=>$message])
                    @enderror
                </div>
                <div class="col-sm-12 col-md-12">
                    <div class="form-check">
                        <input class="form-check-input" name="hasSubscribed" type="checkbox" value="1"
                            checked="checked">
                        <label class="form-check-label" for="hasSubscribed">
                            Keep it going until I stop (you will receive occasional reminders)
                        </label>
                    </div>
                </div>
                <div class="col-sm-12 col-md-4">
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
@push('styles')
    <style>
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
    <script type="text/javascript">
        window.addEventListener("load", function() {
            var tomSelect = new TomSelect("#tomSelect", {
                create: true,
                createFilter: function(input) {
                    input = input.toLowerCase();
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
                //tomSelect.close();
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
