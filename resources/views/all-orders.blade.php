@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div>
                        {{ __('All Orders') }}
                    </div>
                </div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif

                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Order Id</th>
                                <th scope="col">User Name</th>
                                <th scope="col">Order Name</th>
                                <th scope="col">Total Quantity</th>
                                <th scope="col">Total Price</th>
                                <th scope="col">Order Date</th>
                                <th scope="col">Payment Method</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $i=1;
                            @endphp
                            @foreach($orders as $order)
                            @php
                            //dd($order->user);
                            @endphp
                            <tr>
                                <td>{{$i}}</td>
                                <td>{{$order->order_id}}</td>
                                <td>{{$order->user['name']}}</td>
                                <td>{{ $order->first_name}} {{ $order->last_name}}</td>
                                <td>{{$order->total_qty}}</td>
                                <td>{{$order->total_price}}</td>
                                <td>{{\Carbon\Carbon::parse($order->created_at)->format('d-m-Y')}}</td>
                                <td>{{$order->payment_method}}</td>
                                <td>
                                    <a class="btn btn-success" href="{{route('view-order',['id'=>$order->id])}}">
                                        View </a>
                                    <!-- <a class="btn btn-danger delete-order" data-id="{{ $order->id }}"> Delete </a> -->
                                </td>
                            </tr>
                            @php
                            $i++;
                            @endphp
                            @endforeach
                        </tbody>
                    </table>

                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
<script>
// document.querySelectorAll('.delete-category').forEach(button => {
//     button.addEventListener('click', function(event) {
//         event.preventDefault();
//         swal({
//                 buttons: ["Cancel", 'Yes'],
//                 title: "Are you sure?",
//                 text: "Once deleted, you will not be able to recover this data!",
//                 icon: "warning",
//                 // buttons: true,
//                 dangerMode: true,
//             })
//             .then((willDelete) => {
//                 if (willDelete) {
//                     let categoryId = this.getAttribute('data-id');
//                     let deleteUrl = this.getAttribute('href').replace('__ID__', categoryId);
//                     window.location.href = deleteUrl;
//                     swal("Your data has been deleted!", {
//                         icon: "success",
//                     });
//                 } else {
//                     swal("Your data is safe!");
//                 }
//             });
//     });
// });
</script>

@endsection