@extends('layouts.user-app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('My Orders') }}</div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif

                    @foreach($orders as $order)

                    <div class="card mb-2">
                        <div class="card-body">
                            <h5 class="card-title">{{$order->order_id}}</h5>
                            <h6 class="card-subtitle mb-2 text-muted ">
                                <span class="fw-bold me-3">Order Date =
                                    {{ \Carbon\Carbon::parse($order->created_at)->format('d-m-Y') }}</span>
                                <span class="fw-bold me-3">Items = {{$order->total_qty}}</span>
                                <span class="fw-bold ">Total Amount = {{$order->total_price}}</span>
                            </h6>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    @foreach($order->books as $book)
                                    @php
                                        //dd($book->book['image']);
                                    @endphp
                                    @if(isset($book->book))
                                    <div class="card mb-3">
                                        <div class="row g-0">
                                            <div class="col-md-4">
                                                <img src="{{ asset($book->book['image']) }}"
                                                    class="img-fluid rounded-start" alt="...">
                                            </div>
                                            <div class="col-md-8">
                                                <div class="card-body">
                                                    <h5 class="card-title">{{$book->book['name']}}</h5>
                                                    <p class="card-text">By {{$book->book['author']}}</p>
                                                    <p class="card-text">{{$book->book['description']}}</p>
                                                    <div class="d-flex justify-content-between">
                                                        <p class="card-text">
                                                            <small class="text-muted">Quantity =
                                                                {{$book->qty}}</small>
                                                        </p>
                                                        <p class="card-text">
                                                            <small class="text-muted">Amount =
                                                                {{$book->total_book_price}}</small>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    @endforeach
                                </li>
                            </ul>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection