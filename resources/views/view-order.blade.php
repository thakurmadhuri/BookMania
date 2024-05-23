@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div>
                        {{ __('View Order') }}
                    </div>
                    <div>
                        <a class="btn btn-secondary" href="{{route('orders')}}"> Back </a>
                    </div>
                </div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif

                    <div class="row mb-3">
                        <table class="table table-striped">
                            <tr>
                                <th>Order Id</th>
                                <td>{{$order->order_id}}</td>
                            </tr>
                            <tr>
                                <th>Order Date</th>
                                <td>{{\Carbon\Carbon::parse($order->created_at)->format('d-m-Y')}}</td>
                            </tr>
                            <tr>
                                <th>User</th>
                                <td>{{$order->user['name']}}</td>
                            </tr>
                            <tr>
                                <th>Delivary Name</th>
                                <td>{{ $order->first_name}} {{ $order->last_name}}</td>
                            </tr>
                            <tr>
                                <th>Delivary Address</th>
                                <td>{{ $order->address}} </td>
                            </tr>
                            <tr>
                                <th>Pin Code</th>
                                <td>{{ $order->pincode}} </td>
                            </tr>
                            <tr>
                                <th>City</th>
                                <td>{{ $order->city}} </td>
                            </tr>
                            <tr>
                                <th>State</th>
                                <td>{{ $order->state}} </td>
                            </tr>
                            <tr>
                                <th>Total Quantity</th>
                                <td>{{ $order->total_qty}} </td>
                            </tr>
                            <tr>
                                <th>Total Price</th>
                                <td>{{ $order->total_price}} </td>
                            </tr>
                            <tr>
                                <th>Payment Method</th>
                                <td>{{ $order->payment_method}} </td>
                            </tr>
                        </table>

                        <table class="table table-striped">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Image</th>
                                <th scope="col">Name</th>
                                <th scope="col">Author</th>
                                <th scope="col">Total Quantity</th>
                                <th scope="col">Total Price</th>
                            </tr>
                            <tbody>
                                @php
                                $i=1;
                                @endphp
                                @foreach($order->books as $book)
                                @if(isset($book->book))
                                <tr>
                                    <td>{{$i}}</td>
                                    <td>
                                        <img src="{{ asset($book->book->image) }}" style=" height: 50px; width: 70px;"
                                            class="card-img-top" alt="{{ $book->book->name}}">
                                    </td>
                                    <td>{{ $book->book->name}}</td>
                                    <td>{{ $book->book->author}}</td>
                                    <td>{{ $book->qty}}</td>
                                    <td>{{ $book->total_book_price}}</td>
                                </tr>
                                @endif
                                @php
                                $i++;
                                @endphp
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection