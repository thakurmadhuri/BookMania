@extends('layouts.user-app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Order Details') }}</div>

                <div class="card-body d-flex justify-content-center">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif

                    <div class="card " style="width: 35rem;">
                        <div class="d-flex justify-content-center align-items-center">
                            <img src="{{ asset('images/success.png') }}" width="200" height="200" alt="success">
                        </div>
                        <div class="card-body text-center">
                            <h5 class="card-title">Order placed successfully..!</h5>
                            <p class="card-text fw-bold">Order Id - {{$order->order_id}}</p>
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                @php
                                
                                //dd($order->books);
                                @endphp
                                @foreach($order->books as $item)
                                <div class="card mb-3">
                                    <div class="row g-0">
                                        <div class="col-md-4">
                                            <img src="{{ asset($item->book->image) }}" class="img-fluid rounded-start"
                                                alt="...">
                                        </div>
                                        <div class="col-md-8">
                                            <div class="card-body">
                                                <h5 class="card-title">{{$item->book->name}}</h5>
                                                <p class="card-text">By {{$item->book->author}}</p>
                                                <p class="card-text">{{$item->book->description}}</p>
                                                <div class="d-flex justify-content-between">
                                                    <p class="card-text">
                                                        <small class="text-muted">Quantity =
                                                            {{$item->qty}}</small>
                                                    </p>
                                                    <p class="card-text">
                                                        <small class="text-muted">Amount =
                                                            {{$item->total_book_price}}</small>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </li>
                        </ul>
                        <div class="card-body d-flex justify-content-center align-items-center">
                            <a href="{{route('all-books')}}" class="btn btn-success">Ok</a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection