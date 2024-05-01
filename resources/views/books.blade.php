@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div>
                        {{ __('Books') }}
                    </div>
                    <div>
                        <a class="btn btn-success" href="{{route('add-book')}}"> Add </a>
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
                                <th scope="col" style="width: 22%;">Name</th>
                                <th scope="col" style="width: 22%;">Description</th>
                                <th scope="col">Author</th>
                                <th scope="col">Price</th>
                                <th scope="col">Category</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $i=1;
                            @endphp
                            @foreach($books as $book)
                            <tr>
                                <td>{{$i}}</td>
                                <td style="width: 22%;">{{ $book->name}}</td>
                                <td style="width: 22%;">{{ $book->description}}</td>
                                <td>{{ $book->author}}</td>
                                <td>{{ $book->price}}</td>
                                <td>{{ $book->category['name']}}</td>
                                <td>
                                <a class="btn btn-success" href="{{ route('edit-book', ['id' => $book->id ]) }}"> Edit </a>
                                    <a class="btn btn-danger" href="{{ route('delete-book', ['id' => $book->id ]) }}"> Delete </a>
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