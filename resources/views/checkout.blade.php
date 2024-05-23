@extends('layouts.user-app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Checkout') }}</div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif

                    <div class="accordion" id="accordionExample">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="addressDetails">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    Address Details
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse show"
                                aria-labelledby="addressDetails" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <form>
                                        @csrf

                                        <div class="row mb-3">
                                            <label for="name"
                                                class="col-md-4 col-form-label text-md-end">{{ __('Full Name') }}</label>

                                            <div class="col-md-3">
                                                <input id="firstname" type="text" placeholder="First Name"
                                                    class="form-control @error('firstname') is-invalid @enderror"
                                                    name="firstname" value="{{ old('firstname') }}" required
                                                    autocomplete="firstname" autofocus>

                                                @error('firstname')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                            <div class="col-md-3">
                                                <input id="lastname" type="text" placeholder="Last Name"
                                                    class="form-control @error('lastname') is-invalid @enderror"
                                                    name="lastname" value="{{ old('lastname') }}" required
                                                    autocomplete="lastname" autofocus>

                                                @error('lastname')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>

                                        </div>

                                        <div class="row mb-3">
                                            <label for="mobile"
                                                class="col-md-4 col-form-label text-md-end">{{ __('Phone Number') }}</label>

                                            <div class="col-md-6">
                                                <input id="mobile" type="tel"
                                                    class="form-control @error('mobile') is-invalid @enderror"
                                                    name="mobile" value="{{ old('mobile') }}" required
                                                    autocomplete="mobile" autofocus>

                                                @error('mobile')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="address"
                                                class="col-md-4 col-form-label text-md-end">{{ __('Shipping Address') }}</label>

                                            <div class="col-md-6">
                                                <textarea id="address"
                                                    class="form-control @error('address') is-invalid @enderror"
                                                    name="address" value="old('address') " required
                                                    autocomplete="address" autofocus></textarea>

                                                @error('address')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="pincode"
                                                class="col-md-4 col-form-label text-md-end">{{ __('Pin Code') }}</label>

                                            <div class="col-md-6">
                                                <input id="pincode" type="number" maxlength="6" minlength="6"
                                                    onKeyPress="if(this.value.length==6) return false;"
                                                    class="form-control @error('pincode') is-invalid @enderror"
                                                    name="pincode" value="{{ old('pincode') }}" required
                                                    autocomplete="pincode" autofocus>

                                                @error('pincode')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="city"
                                                class="col-md-4 col-form-label text-md-end">{{ __('City') }}</label>

                                            <div class="col-md-6">
                                                <input id="city" type="text"
                                                    class="form-control @error('city') is-invalid @enderror" name="city"
                                                    value="{{ old('city') }}" required autocomplete="city" autofocus>

                                                @error('city')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="state"
                                                class="col-md-4 col-form-label text-md-end">{{ __('State') }}</label>

                                            <div class="col-md-6">
                                                <select id="state" name="state" class="form-select" required autofocus>
                                                    <option value="">Select State</option>
                                                    @foreach($states as $code => $name)
                                                    <option value="{{ $name }}">{{ $name }}</option>
                                                    @endforeach
                                                </select>

                                                @error('state')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="country"
                                                class="col-md-4 col-form-label text-md-end">{{ __('Country') }}</label>

                                            <div class="col-md-6">
                                                <input id="country" type="text"
                                                    class="form-control @error('country') is-invalid @enderror"
                                                    name="country" value="{{ old('country', 'India') }}" required
                                                    autocomplete="country" autofocus>

                                                @error('country')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class=" d-flex justify-content-center">
                                            <button type="button" class=" btn btn-success save-address ">Save
                                                Address</button>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class=" accordion-item">
                            <h2 class="accordion-header" id="headingTwo">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    Order Details
                                </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                                data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    @php
                                    $totalAmount = 0;
                                    @endphp

                                    @foreach($cart as $item)
                                    @foreach($item['cartDetails'] as $book)
                                    @php
                                    $subtotal = $book->price * $book->qty;
                                    $totalAmount += $subtotal;
                                    @endphp

                                    <div class="card mb-3">
                                        <div class="row g-0">
                                            <div class="col-md-4">
                                                <img src="{{ asset($book->image) }}" class="img-fluid rounded-start"
                                                    alt="...">
                                            </div>
                                            <div class="col-md-8">
                                                <div class="card-body">
                                                    <h5 class="card-title">{{$book->name}}</h5>
                                                    <p class="card-text">By {{$book->author}}</p>
                                                    <p class="card-text">{{$book->description}}</p>
                                                    <div class="d-flex justify-content-between">
                                                        <p class="card-text">
                                                            <small class="text-muted">Quantity =
                                                                {{$book->qty}}</small>
                                                        </p>
                                                        <p class="card-text">
                                                            <small class="text-muted">Total Amount =
                                                                ₹ {{number_format($subtotal, 2, '.', '')}}</small>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @endforeach
                                    @endforeach

                                    <div class="d-flex justify-content-center">
                                        <p> <span class="fw-bold">Total Amount = </span>
                                            ₹ {{number_format($totalAmount, 2, '.', '')}}</p>
                                    </div>
                                    <div class="d-flex justify-content-center">
                                        <br><button class="btn btn-success mb-2 confirm"> Confirm
                                            Order</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingThree">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                    Payment Method
                                </button>
                            </h2>
                            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree"
                                data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <div class="d-flex justify-content-evenly mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="paymentmode" id="cash"
                                                value="cash" checked>
                                            <label class="form-check-label" for="cash">
                                                Cash On Delivery
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="paymentmode" id="online"
                                                value="online">
                                            <label class="form-check-label" for="online">
                                                Stripe
                                            </label>
                                        </div>
                                    </div>
                                    <div class="stripe">
                                        <form action="{{route('stripe-order')}}" method="POST" id="payment-form">
                                            {{ csrf_field() }}
                                            <div class="row mb-3">
                                                <label for="amount"
                                                    class="col-md-4 col-form-label text-md-end">{{ __('Amount') }}</label>

                                                <div class="col-md-6">
                                                    <input type="text" name="amount" value="{{$totalAmount}}"
                                                        id="amount" class="form-control" readonly>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="email"
                                                    class="col-md-4 col-form-label text-md-end">{{ __('Email') }}</label>

                                                <div class="col-md-6">
                                                    <input type="text" name="email" id="email" class="form-control">
                                                </div>
                                            </div>
                                            <div class="">
                                                <label for="card-element">
                                                    Credit or debit card
                                                </label>
                                                <div id="card-element ">
                                                </div>
                                                <div id="card-errors" role="alert"></div>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-center ">
                                    <br><button class="btn btn-success mb-2 place"> Place
                                        Order</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
</div>
<script src="https://js.stripe.com/v3/"></script>

<script>
$(document).ready(function() {

    $(".stripe").hide();

    $("#online").click(function() {
        $(".stripe").show();
        // $(".place").hide();
    });

    $("#cash").click(function() {
        $(".stripe").hide();
        // $(".place").show();
    });

    function disableAccordian() {
        $('#headingTwo .accordion-button').attr('data-bs-toggle', '');
        $('#headingTwo .accordion-button').addClass('disabled');
        $('#headingTwo .accordion-button').attr('aria-disabled', 'true');

        $('#headingThree .accordion-button').attr('data-bs-toggle', '');
        $('#headingThree .accordion-button').addClass('disabled');
        $('#headingThree .accordion-button').attr('aria-disabled', 'true');
    }

    $(".confirm").click(function() {
        $('#collapseTwo').removeClass(
            'show');
        $('#collapseTwo').prev('.accordion-header').find('button')
            .addClass(
                'collapsed');
        $('#collapseTwo').attr('aria-expanded',
            'false');

        $('#collapseThree').addClass(
            'show');
        $('#collapseThree').prev('.accordion-header').find('button')
            .removeClass(
                'collapsed');
        $('#collapseThree').attr('aria-expanded', 'true');
    });

    $(".place").click(function() {
        $.ajax({
            url: '/place-order',
            type: 'POST',
            data: {

            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                window.location.href = '/complete-order';
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Error placing order!",
                });
            }
        });
    });


    $(".save-address").click(function() {
        firstname = $("#firstname").val();
        lastname = $("#lastname").val();
        mobile = $("#mobile").val();
        address = $("#address").val();
        pincode = $("#pincode").val();
        city = $("#city").val();
        state = $("#state").val();
        country = $("#country").val();

        $.ajax({
            url: '/add-address',
            type: 'POST',
            data: {
                firstname: firstname,
                lastname: lastname,
                mobile: mobile,
                address: address,
                pincode: pincode,
                city: city,
                state: state,
                country: country
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#collapseOne').removeClass(
                    'show');
                $('#collapseOne').prev('.accordion-header').find('button')
                    .addClass(
                        'collapsed');
                $('#collapseOne').attr('aria-expanded',
                    'false');

                $('#collapseTwo').addClass(
                    'show');
                $('#collapseTwo').prev('.accordion-header').find('button')
                    .removeClass(
                        'collapsed');
                $('#collapseTwo').attr('aria-expanded', 'true');
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Error while saving address!",
                });
            }
        });
    });
});
</script>

<!-- <script>
const stripe = Stripe(
    'pk_test_51PJXwlSBxW9JUnnDOk0T6AujlQGLn3MhDHz6CnWXjqrD7w2cHIYRKCDyTlep04D6EHFjq3WJBQBSk4UGSbLvsuNJ006KvS8ZiA');

const elements = stripe.elements();

const style = {
    base: {
        color: '#32325d',
        fontFamily: 'Arial, sans-serif',
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

const card = elements.create('card', {
    style: style
});

card.mount('#card-element');

card.on('change', event => {
    const displayError = document.getElementById('card-errors');
    if (event.error) {
        displayError.textContent = event.error.message;
    } else {
        displayError.textContent = '';
    }
});

const form = document.getElementById('submit');
form.addEventListener('click', async event => {
    event.preventDefault();

    const {
        token,
        error
    } = await stripe.createToken(card);

    if (error) {
        const errorElement = document.getElementById('card-errors');
        errorElement.textContent = error.message;
    } else {
        stripeTokenHandler(token);
    }
});

function stripeTokenHandler(token) {
    const hiddenInput = document.createElement('input');
    hiddenInput.setAttribute('type', 'hidden');
    hiddenInput.setAttribute('name', 'stripeToken');
    hiddenInput.setAttribute('value', token.id);
    document.body.appendChild(hiddenInput);

    document.body.submit();
}
</script> -->
@endsection