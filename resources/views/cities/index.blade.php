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

        @if ($error = Session::get('error'))
            <div class="alert alert-danger">
                <p>{{ $error }}</p>
            </div>
        @endif

        <table class="table table-bordered">
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
                    <td><a href="{{ route('cities.show', $city->id) }}">{{ $city->name }}</a></td>
                    <td>{{ $city->country }}</td>
                    <td>{{ $city->description }}</td>
                    <td>
                        @auth
                            @if (auth()->user()->isAdminUser())
                                <form action="{{route('cities.destroy', $city->id)}}" method="POST">
                                    @csrf
                                    @method('DELETE')

                                    <a class="btn btn-success" href="{{ route('cities.edit', $city->id) }}">
                                        {{ __('Edit') }}
                                    </a>

                                    <button type="submit" title="delete" class="btn btn-danger">
                                        Delete
                                    </button>
                                </form>
                            @endif
                            <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#comments{{$city->id}}" aria-expanded="false" aria-controls="comments{{$city->id}}">
                                Show 5 last comments
                            </button>
                        @endauth
                    </td>
                </tr>
                @foreach ($city->getCommentsPreview(5) as $index => $comment)
                    <tr class="collapse" id="comments{{$city->id}}">
                        <td colspan="4"><strong>{{$comment->user->username}}</strong>: {{$comment->comment}} <small>{{$comment->updated_at}}</small></td>
                        @if ($index === 0)
                            <td colspan="4"><a href="{{ route('cities.show', $city->id) }}">See all comments</a></td>
                        @endif
                    </tr>

                @endforeach
            @endforeach
        </table>
    </div>
</div>
@endsection
