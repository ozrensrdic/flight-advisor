@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Preview City</h2>
            </div>
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

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">

                <div class="card-body">

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" disabled value="{{ $city->name }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="country" class="col-md-4 col-form-label text-md-right">{{ __('Country') }}</label>

                            <div class="col-md-6">
                                <input id="country" type="text" class="form-control" name="country" disabled value="{{ $city->country }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="description" class="col-md-4 col-form-label text-md-right">{{ __('Description') }}</label>

                            <div class="col-md-6">
                                <input id="description" type="text" class="form-control" name="description" disabled value="{{ $city->description }}">
                            </div>
                        </div>

                </div>
            </div>
        </div>
    </div>
    <div class="row mt-5">
        @if (auth()->user()->isRegularUser())
            <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#newComment{{$city->id}}">
                Add new comment
            </button>
        @endif
        <div class="modal fade" id="newComment{{$city->id}}" tabindex="-1" role="dialog" aria-labelledby="NewComment" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form action="{{route('comments.store')}}" method="POST">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">New comment</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="city_id" value="{{$city->id}}">
                            <input type="hidden" name="user_id" value="{{auth()->user()->id}}">
                            <div class="form-group">
                                <label for="comment">Leave comment for {{$city->name}}</label>
                                <textarea name="comment" class="form-control" id="comment" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <table class="table table-bordered">
                <tr>
                    <th>User</th>
                    <th>Comment</th>
                    <th>Created</th>
                    <th>Updated</th>
                    <th>Actions</th>
                </tr>

                @foreach ($comments as $comment)
                    <tr>
                        <td>{{ $comment->user->username }}</td>
                        <td>{{ $comment->comment }}</td>
                        <td>{{ $comment->created_at }}</td>
                        <td>{{ $comment->updated_at }}</td>
                        <td>
                            @if (auth()->user()->username === $comment->user->username)

                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#updateComment{{$comment->id}}">
                                    Edit
                                </button>

                                <div class="modal fade" id="updateComment{{$comment->id}}" tabindex="-1" role="dialog" aria-labelledby="NewComment" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <form action="{{route('comments.update', $comment->id)}}" method="POST">
                                            @method('PATCH')
                                            @csrf
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Edit comment</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <input type="hidden" name="city_id" value="{{$city->id}}">
                                                    <input type="hidden" name="user_id" value="{{auth()->user()->id}}">
                                                    <div class="form-group">
                                                        <label for="comment">Leave comment for {{$city->name}}</label>
                                                        <textarea name="comment" class="form-control" id="comment" rows="3">{{$comment->comment}}</textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <form action="{{route('comments.destroy', $comment->id)}}" method="POST" style="display: inline-block">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" title="delete" class="btn btn-danger">
                                        Delete
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </table>

    </div>

</div>
@endsection
