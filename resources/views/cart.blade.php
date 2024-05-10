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

                    @php
                    $totalAmount = 0;
                    @endphp

                    @if(isset($cart) && count($cart)>0)
                    @foreach($cart as $item)
                    @foreach($item['cartdetails'] as $book)

                    @php
                    $subtotal = $book->price * $book->qty;
                    $totalAmount += $subtotal;
                    @endphp

                    <div class="card mb-3">
                        <!-- <div class="card-header bg-transparent border-bottom-0">
                            <button data-dismiss="alert" data-target="#closeablecard" type="button" class="btn btn-light close"
                                aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div> -->
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
                                            <!-- <div class="input-group">
                                            <button class="minus btn btn-sm btn-outline-success"
                                                data-bookid="{{ $book->id }}" type="button">-</button>
                                            <input type="text" value="{{$book->qty}}" style="width:30px;"
                                                data-bookid="{{ $book->id }}" class="qty ps-1 pe-1" disabled>
                                            <button class="plus btn btn-sm btn-outline-success"
                                                data-bookid="{{ $book->id }}" type="button">+</button>
                                            </p>
                                           
                                        </div> -->
                                        <p class="card-text">
                                            <small class="text-muted">Amount = ₹
                                                {{number_format($subtotal, 2, '.', '')}}</small>
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
                            Total Amount = ₹ {{ number_format($totalAmount, 2, '.', '') }}
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
    <script>
    $(document).ready(function() {
        $(".plus").click(function() {
            var bookId = $(this).data("bookid");
            var input = $(".qty[data-bookid='" + bookId + "']");
            var value = parseInt(input.val()) + 1;
            input.val(value);
            addToCart(bookId, value);
        });

        $(".minus").click(function() {
            var bookId = $(this).data("bookid");
            var input = $(".qty[data-bookid='" + bookId + "']");
            var value = parseInt(input.val()) - 1;
            value = Math.max(value, 0);
            input.val(value);
            addToCart(bookId, value);
            if (value == 0) {
                $(".add[data-bookid='" + bookId + "']").show();
                $(".counter[data-bookid='" + bookId + "']").hide();
            }
        });

        function addToCart(bookId, qty) {
            price = $("#price-" + bookId + "").val();
            total = price * qty;
            $.ajax({
                url: '/store-cart', // Replace with the actual URL for saving the cart
                type: 'POST',
                data: {
                    books_id: bookId,
                    quantity: qty,
                    total: total
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log('Cart saved successfully');
                },
                error: function(xhr, status, error) {
                    console.error('Error saving cart:', error);
                }
            });
        }
    });
    </script>
    @endsection