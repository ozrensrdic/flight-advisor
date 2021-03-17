@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-12 mb-4">
            <div class="pull-left">
                <h2>Preview cities</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('cities.create') }}">
                    {{ __('Add a city') }}
                </a>
            </div>
        </div>

        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        @endif

        <table class="table table-bordered table-responsive-lg">
            <tr>
                <th>No</th>
                <th>Name</th>
                <th>Country</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
            @foreach ($cities as $city)
                <tr>
                    <td>{{ $city->id }}</td>
                    <td>{{ $city->name }}</td>
                    <td>{{ $city->country }}</td>
                    <td>{{ $city->description }}</td>
                    <td>
                        <form action="{{route('cities.destroy', $city->id)}}" method="POST">

                            <a class="btn btn-success" href="{{ route('cities.edit', $city->id) }}">
                                {{ __('Edit') }}
                            </a>

                            @csrf
                            @method('DELETE')

                            <button type="submit" title="delete" class="btn btn-danger">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
</div>
@endsection
