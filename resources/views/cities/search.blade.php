@extends('layouts.app')

@section('content')
    <div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Search</h2>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Search city') }}</div>

                <div class="card-body">
                    @csrf
                    <div class="form-group row">
                        <label for="search" class="col-md-4 col-form-label text-md-right">{{ __('Search') }}</label>

                        <div class="col-md-6">
                            <input id="search" type="search" class="form-control @error('search') is-invalid @enderror" name="search" required autocomplete="name" autofocus>

                            @error('search')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="row mt-5">
        <table class="table table-bordered collapse" id="comments">
            <tr>
                <th>City</th>
                <th>Country</th>
                <th>Description</th>
            </tr>
        </table>
    </div>

    <script>
        $( "#search" ).keyup(function() {
            let city = $('#search').val();

            if (city.length >= 3) {
                searchCity();
            }
        });

        function searchCity() {
            let city = $('#search').val();
            let table = $('#comments');
            $.ajax({
                url: '{{ route('cities.search.results') }}',
                type: "GET",
                data: {
                    city: city,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    table.empty()
                    if (data.cities) {
                        $.each(data.cities, function( index, city ) {
                            let tr = `<tr>
                                    <td>${city.name}</td>
                                    <td>${city.country}</td>
                                    <td>${city.description}</td>
                                </tr>`;

                            let commentTr = '<tr>';
                            $.each(city.comments, function( index, comments ) {
                                ($('#comments' + city.name).append(
                                    commentTr = commentTr + `<tr><td colspan="3"><small>${comments.comment}</small></td></tr>`
                                ));
                            })

                            table.append(tr + commentTr);
                        });

                        table.removeClass('collapse');
                    }
                },
                error: function(response) {
                    console.log(response)
                }
            });
        }
    </script>
@endsection
