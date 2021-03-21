@extends('layouts.app')

@section('content')
    <div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Routes</h2>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Find cheapet route') }}</div>

                <div class="card-body">
                    @csrf
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="source" class="form-label">From:</label>
                            <input class="form-control" list="citiesFrom" id="source" placeholder="Type to search...">
                            <datalist id="citiesFrom">
                                @foreach($cities as $index => $city)
                                    <option value="{{ $index . ' ' . $city }}">
                                @endforeach
                            </datalist>
                        </div>

                        <div class="col-md-6">
                            <label for="destination" class="form-label">To:</label>
                            <input class="form-control" list="citiesTo" id="destination" placeholder="Type to search...">
                            <datalist id="citiesTo">
                                @foreach($cities as $index => $city)
                                    <option value="{{ $index . ' ' . $city }}">
                                @endforeach
                            </datalist>
                        </div>

                        <div class="col-md-6 mt-4">
                            <button type="submit" class="btn btn-primary" onclick="sendRequest()" disabled>
                                {{ __('Find flight') }}
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="row mt-5">
        <table class="table table-bordered" id="routes">
            <tr>
                <th>Source city</th>
                <th>Destination city</th>
                <th>Price</th>
            </tr>
        </table>
    </div>

    <script>
        let source = '';
        let destination = '';
        let $destination = $('#destination');
        let $source = $('#source');

        $destination.change(function(){
            destination = $destination.val().split(' ').shift();
            allowRequest();
        });

        $source.change(function(){
            source = $source.val().split(' ').shift();
            allowRequest();
        });

        function allowRequest() {
            if (source && destination && (source !== destination)) {
                $('button').prop('disabled', false);
            }
        }

        function sendRequest() {
            let table = $('#routes');

            $.ajax({
                url: '{{ route('cities.route.details') }}',
                type: "GET",
                data: {
                    destination_id: destination,
                    source_id: source,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    table.empty();
                    if (data.routes) {
                        table.append(
                            `<tr>
                                <th>Source city</th>
                                <th>Destination city</th>
                                <th>Price</th>
                            </tr>
                            <tr>
                                <td>${data.routes.source}</td>
                                <td>${data.routes.destination}</td>
                                <td>${data.routes.price}</td>
                            </tr>`
                        );
                    } else {
                        table.append(
                            `<tr>
                                <td colspan="3">No results</td>
                            </tr>`
                        );
                    }
                },
                error: function(response) {
                    console.log(response)
                }
            });
        }

    </script>
@endsection
