@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Users') }}</div>

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
                                <th scope="col">Email</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $i=1;
                            @endphp
                            @foreach($users as $user)
                            <tr>
                                <td>{{$i}}</td>
                                <td>{{ $user->name}}</td>
                                <td>{{ $user->email}}</td>
                                <td>
                                    <!-- <button class="btn btn-success"> Edit </button> -->
                                    <a href="{{route('delete-user', ['id' => '__ID__']) }}" data-id="{{ $user->id }}"class="btn btn-danger delete-user"> Delete </a>
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
<script>
    $(document).ready(function() {
    $('.delete-user').on('click', function(event) {
        event.preventDefault();
        let $this = $(this);
        swal({
            buttons: ["Cancel", 'Yes'],
            title: "Are you sure?",
            text: "Once deleted, you will not be able to recover this data!",
            icon: "warning",
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                let id = $this.data('id');
                let deleteUrl = $this.attr('href').replace('__ID__', id);
                window.location.href = deleteUrl;
                swal("Your data has been deleted!", {
                    icon: "success",
                });
            } else {
                swal("Your data is safe!");
            }
        });
    });
});
</script>
@endsection