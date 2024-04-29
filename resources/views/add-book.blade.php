@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div>
                        @if(isset($book))
                        {{ __('Edit Book') }}
                        @else
                        {{ __('Add Book') }}
                        @endif
                    </div>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ isset($book) ? route('update-book', $book->id) : route('store-book') }}">
                        @csrf
                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="name" class="form-control @error('name') is-invalid @enderror"
                                    name="name" value="{{ isset($book) ? $book->name : old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="description"
                                class="col-md-4 col-form-label text-md-end">{{ __('Description') }}</label>

                            <div class="col-md-6">
                                <textarea id="description" type="description"
                                    class="form-control @error('description') is-invalid @enderror" name="description"
                                    value="{{ isset($book) ? $book->description : old('description') }}" required autocomplete="description" autofocus>{{ isset($book) ? $book->description : old('description') }}
                                </textarea>

                                @error('description')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="price" class="col-md-4 col-form-label text-md-end">{{ __('Price') }}</label>

                            <div class="col-md-6">
                                <input id="price" type="number" step="0.01" class="form-control @error('price') is-invalid @enderror"
                                    name="price" value="{{ isset($book) ? $book->price : old('price') }}" required autocomplete="price" autofocus>

                                @error('price')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="author" class="col-md-4 col-form-label text-md-end">{{ __('Author') }}</label>

                            <div class="col-md-6">
                                <input id="author" type="author"
                                    class="form-control @error('author') is-invalid @enderror" name="author"
                                    value="{{ isset($book) ? $book->author : old('author') }}" required autocomplete="author" autofocus>

                                @error('author')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="category"
                                class="col-md-4 col-form-label text-md-end">{{ __('Category') }}</label>

                            <div class="col-md-6">
                                <select id="category_id" type="category_id"
                                    class="form-select @error('category_id') is-invalid @enderror" name="category_id" required
                                    autofocus>
                                    <option disabled selected>-- Select --</option>
                                    @foreach($categories as $cat)
                                    <option value="{{$cat->id}}" {{ (isset($book) && $book->category_id == $cat->id) ? 'selected' : '' }}>{{$cat->name}}</option>
                                    @endforeach
                                </select>

                                @error('category_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12 d-flex justify-content-center">
                                <a type="button" class="btn btn-secondary me-2" href="{{route('books')}}"> Cancel</a>
                                <button type="submit" class="btn btn-primary"> Save</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection