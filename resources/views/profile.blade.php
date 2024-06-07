@extends('layouts.user-app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Profile') }}</div>

                <div class="card-body center">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif

                    @if(isset($user))
                    <div class="row">
                        <div class="col-3">
                            <p class="text-end fw-bold">
                                Name:-
                            </p>
                        </div>
                        <div class="col-3">
                            {{$user->name}}
                        </div>
                    
                        <div class="col-3">
                            <p class="text-end fw-bold">
                                Email:-
                            </p>
                        </div>
                        <div class="col-3">
                            {{$user->email}}
                        </div>
                    </div>

                    <table class="table table-striped mt-5">
                        <thead>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Address</th>
                            <th scope="col">Pincode</th>
                            <th scope="col">Mobile</th>
                            <th scope="col">City</th>
                            <th scope="col">State</th>
                            <th scope="col">Country</th>
                        </thead>
                        <tbody>
                            @php
                            $i=1;
                            @endphp
                            @foreach($user->addresses as $address)
                            @if(isset($address))
                            <tr>
                                <td>{{$i}}</td>
                                <td>{{ $address->first_name}} {{ $address->last_name}}</td>
                                <td>{{$address->address}}</td>
                                <td>{{$address->pincode}}</td>
                                <td>{{$address->mobile}}</td>
                                <td>{{$address->city}}</td>
                                <td>{{$address->state}}</td>
                                <td>{{$address->country}}</td>
                            </tr>
                            @endif
                            @php
                            $i++;
                            @endphp
                            @endforeach
                        </tbody>
                    </table>

                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection