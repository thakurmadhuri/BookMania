@extends('layouts.user-app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Books') }}</div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif

                    @php
                    $user=Auth::user();
                    $cart = Session::get('cart.'. $user->id, []);
                    @endphp

                    <form action="{{ route('all-books') }}" method="GET" >
                        <div class="input-group mb-3">
                            <input type="text" name="q" placeholder="Search books..." class="form-control" id="inputGroupFile04"
                                aria-describedby="inputGroupFileAddon04" aria-label="Upload">
                            <button class="btn btn-outline-secondary" type="submit"
                                id="inputGroupFileAddon04">Search</button>
                        </div>
                    </form>


                    <div class="row row-cols-1 row-cols-md-3 g-4">
                        @foreach($books as $book)

                        <div class="col">
                            <div class="card h-100">
                                <img src="{{ asset('images/book1.jpg') }}" class="card-img-top" alt="{{$book->name}}">
                                <div class="card-body">
                                    <h5 class="card-title fw-bold">{{$book->name}}</h5>
                                    <p class="fs-5 ">By {{$book->author}}</p>
                                    <p class="card-text ">{{$book->description}}</p>
                                </div>
                                <div class="card-footer d-flex justify-content-between">
                                    <small class="text-muted">₹ {{$book->price}}</small>
                                    <input type="hidden" id="price-{{$book->id }}" value="{{$book->price}}">
                                    <button class="add btn btn-sm btn-success "
                                        style="{{isset($cart[$book->id])? 'display:none;': ''  }}"
                                        data-bookid="{{ $book->id }}"> Add to cart</button>
                                    <div class="counter" data-bookid="{{ $book->id }}"
                                        style="{{isset($cart[$book->id])? '': 'display:none;'  }}">
                                        <div class="input-group">
                                            <button class="minus btn btn-sm btn-outline-success"
                                                data-bookid="{{ $book->id }}" type="button">-</button>
                                            <input type="text"
                                                value="{{isset($cart[$book->id])? $cart[$book->id]: ''  }}"
                                                style="width:30px;" data-bookid="{{ $book->id }}" class="qty ps-1 pe-1"
                                                disabled>
                                            <button class="plus btn btn-sm btn-outline-success"
                                                data-bookid="{{ $book->id }}" type="button">+</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // $(".counter").hide();

    $(".add").click(function() {
        var bookId = $(this).data("bookid");
        $(".add[data-bookid='" + bookId + "']").hide();
        $(".qty[data-bookid='" + bookId + "']").val("1");
        addToCart(bookId, 1)
        $(".counter[data-bookid='" + bookId + "']").show();
    });

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
        total = parseFloat(price * qty);
        $.ajax({
            url: '/store-cart', // Replace with the actual URL for saving the cart
            type: 'POST',
            data: {
                book_id: bookId,
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