@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div>
                        {{ __('Categories') }}
                    </div>
                    <div>
                        <a class="btn btn-success" href="{{route('add-category')}}"> Add </a>
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
                                <th scope="col">Name</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $i=1;
                            @endphp
                            @foreach($categories as $cat)
                            <tr>
                                <td>{{$i}}</td>
                                <td>{{ $cat->name}}</td>
                                <td>
                                    <a class="btn btn-success" href="{{ route('edit-category', ['id' => $cat->id ]) }}"> Edit </a>
                                    <a class="btn btn-danger" href="{{ route('delete-category', ['id' => $cat->id ]) }}"> Delete </a>
                                </td>
                            </tr>
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
@endsection