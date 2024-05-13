@extends('layouts.user-app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if(isset($cart) && count($cart)>0)

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

                    @foreach($cart as $item)
                
                    <input type="hidden" id="cart-id" name="cart-id" value="{{$item->id}}">
                    
                    @foreach($item['cartdetails'] as $book)

                    @php
                    $subtotal = $book->price * $book->qty;
                    $totalAmount += $subtotal;
                    @endphp

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
                                        <div>
                                            <div class="input-group">
                                                <button class="minus btn btn-sm btn-outline-success"
                                                    data-bookid="{{ $book->id }}" type="button">-</button>
                                                <input type="text" value="{{$book->qty}}" style="width:30px;"
                                                    data-bookid="{{ $book->id }}" class="qty ps-1 pe-1" disabled>
                                                <button class="plus btn btn-sm btn-outline-success"
                                                    data-bookid="{{ $book->id }}" type="button">+</button>
                                            </div>
                                        </div>
                                        <div>
                                            <p class="card-text">
                                                <small class="text-muted">
                                                    Amount = ₹
                                                    <span data-price="{{$book->price}}" data-bookid="{{ $book->id }}"
                                                        class="subtotal">
                                                        {{number_format($subtotal, 2, '.', '')}}
                                                    </span>
                                                </small>
                                            </p>
                                        </div>
                                        <div>
                                            <button data-bookid="{{ $book->id }}"
                                                class="remove btn btn-sm btn-danger">Remove </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @endforeach
                    <div class="d-flex justify-content-evenly">
                        <span class="fw-bold ">
                            Total Amount = ₹ <span class="total">
                                {{ number_format($totalAmount, 2, '.', '') }}
                            </span>
                        </span>
                        <a class="btn btn-success mb-2" href="{{route('checkout')}}">Proceed To Checkout</a>
                    </div>

                </div>
            </div>
            @else
            <img src="{{asset('images/empty-cart.png')}}">
            @endif
        </div>
    </div>
    <script>
    $(document).ready(function() {
        $(".plus").click(function() {
            var bookId = $(this).data("bookid");
            var input = $(".qty[data-bookid='" + bookId + "']");
            var value = parseInt(input.val()) + 1;
            input.val(value);
            setPrice(bookId, value, 'plus');
            addToCart(bookId, value);
        });

        $(".minus").click(function() {
            var bookId = $(this).data("bookid");
            var input = $(".qty[data-bookid='" + bookId + "']");
            var value = parseInt(input.val()) - 1;
            value = Math.max(value, 0);
            input.val(value);
            setPrice(bookId, value, 'minus');
            if (value == 0) {
                $(this).prop('disabled', true);
            }
            addToCart(bookId, value);
        });

        $('.remove').click(function() {
            var bookId = $(this).data("bookid");
            var cartId = $('#cart-id').val();
            $.ajax({
                url: '/remove-item',
                type: 'POST',
                data: {
                    books_id: bookId,
                    cart_id: cartId,
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log('Item removed successfully');
                },
                error: function(xhr, status, error) {
                    console.error('Error saving cart:', error);
                }
            });
        });

        function setPrice(bookId, qty, mode) {
            var price = $('.subtotal[data-bookid="' + bookId + '"]').data('price'); //setting new price
            $('.subtotal[data-bookid="' + bookId + '"]').text((price * qty).toFixed(2));

            if (mode == 'plus') {
                var old_price = price * (qty - 1);
            } else {
                var old_price = price * (qty + 1);
            }
            var new_price = price * qty;
            var total = $('.total').text().trim().replace(/\s/g, '');
            var final = total - old_price + new_price;
            $('.total').text(final.toFixed(2));
        }

        function addToCart(bookId, qty) {
            price = $("#price-" + bookId + "").val();
            total = price * qty;
            $.ajax({
                url: '/store-cart',
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