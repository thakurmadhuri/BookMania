@extends('layouts.user-app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Cart') }}</div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif

                    @if(isset($cart) && count($cart)>0)
                    @foreach($cart as $item)
                    @foreach($item['cartdetails'] as $book)
                    <div class="card mb-3">
                        <div class="row g-0">
                            <div class="col-md-4">
                                <img src="{{ asset('images/book1.jpg') }}" class="img-fluid rounded-start" alt="...">
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title">{{$book->name}}</h5>
                                    <p class="card-text">By {{$book->author}}</p>
                                    <p class="card-text">{{$book->description}}</p>
                                    <div class="d-flex justify-content-between">
                                        <p class="card-text">
                                            <small class="text-muted">Quantity = {{$book->qty}}</small>
                                        </p>
                                        <p class="card-text">
                                            <small class="text-muted">Amount = {{$book->total_book_price}}</small>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @endforeach
                    @endforeach
                    <div class="d-flex justify-content-evenly">
                        <span class="fw-bold">
                            Total Amount = ₹ {{$cart[0]['total_price']}}
                        </span>
                        <a class="btn btn-success mb-2" href="{{route('checkout')}}">Proceed To Checkout</a>
                    </div>
                    @else
                    <img src="{{asset('images/empty-cart.png')}}">
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection