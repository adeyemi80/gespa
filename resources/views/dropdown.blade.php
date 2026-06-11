<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="content">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Glorieux</title>

    <!-- CSS only -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4" >
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="alert alert-primary mb-4 text-center">
                   <h4 ></h4>
                </div> 
                <form>
                    <div class="form-group mb-3">
                        <select  id="country-dropdown" class="form-control">
                            <option value="">-- Select Classe --</option>
                            @foreach ($classes as $data)
                            <option value="{{$data->id}}">
                                {{$data->nom}}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <select id="state-dropdown" class="form-control">
                        </select>
                    </div>
                    <div class="form-group">
                        <select id="city-dropdown" class="form-control">
                        </select>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script>
        $(document).ready(function () {

            /*------------------------------------------

            --------------------------------------------

            Country Dropdown Change Event

            --------------------------------------------

            --------------------------------------------*/

            $('#classe-dropdown').on('change', function () {
                var idClasse = this.value;
                $("#inscription-dropdown").html('');
                $.ajax({
                    url: "{{url('api/fetch-inscriptions')}}",
                    type: "POST",
                    data: {
                        classe_id: idClasse,
                        _token: '{{csrf_token()}}'
                    },
                    dataType: 'json',
                    success: function (result) {
                        $('#inscription-dropdown').html('<option value="">-- Select Nom élève --</option>');
                        $.each(result.states, function (key, value) {
                            $("#inscription-dropdown").append('<option value="' + value
                                .id + '">' + value.n + '</option>');
                        });
                        $('#city-dropdown').html('<option value="">-- Select City --</option>');

                    }

                });

            });

           

        });

    </script>

</body>

</html>