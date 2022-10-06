<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <title>New Year - Greeting</title>

    <style>
        .container {
            position: relative;
            text-align: center;
            color: white;
        }

        .centered {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .list-group {
            max-height: 200px;
            margin-bottom: 10px;
            overflow: scroll;
            -webkit-overflow-scrolling: touch;
        }
    </style>
</head>
<body style=" background: lightblue url(/mySupportSystem/newyear/bg.jpg) no-repeat fixed center; -webkit-background-size: cover;-moz-background-size: cover; -o-background-size: cover;background-size: cover; ">
    {!! Form::open(['route' => array('supportsystem.newyear.update', $user->id), 'files' => true, 'id' => 'newyear-form']) !!}
    {{ csrf_field() }}
    <div class="d-flex align-items-center" style="text-align: center;  margin: 25px; justify-content: center; ">
        <div class="row">
            <div class="col">
                <!--Image and name data-->

                <img src='{{ !empty($user->user_image) ? "/mySupportSystem/newyear/users/{$user->id}/{$user->user_image}" : "/mySupportSystem/newyear/profilesample.png" }}' alt="" width="100px" height="100px" id="profile"
                    style="border-radius: 50%;">

                <h4 style="text-align: center;  margin: 5px; color: rgb(255, 255, 81);">Profile Photo</h4>

                @include('layouts.message')

                <h6 style="text-align: center;  margin-top: 30px; color: rgb(255, 255, 81);">Delicate to:</h6>

                <input type="text" id="delicate" name="to" autocomplete="off" class="form-control form-rounded" placeholder="Enter Here" maxlength="20" required style="text-align: center; border-radius: 1rem; font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;color: rgb(0, 39, 110); font-size:xx-large; font-weight: bold;">
                <p class="myFontLengthText" style="font-size: small; text-align: end; color: rgb(255, 255, 255);">0 character</p>

                <!--Greetings DropDown-->
                <div class="row" >
                    <div class="col">
                        <h6 style="text-align: center;  margin-top: 30px; color: rgb(255, 255, 81);">Select your
                            greetings</h6>
                        <input type="text" id="greeting" name="message" autocomplete="off" class="form-control form-rounded" placeholder="Not Selected"

                            style="margin-top: 10px ; margin-bottom: 10px; text-align: center; border-radius: 1rem; font-weight: bold; background-color: #ffffff;">

                        <div class="form-group">
                            <select class="form-control" name="" id="greetinglist" multiple="multiple"
                                style="text-align: start;  font-weight: bold;">
                                <option value="">Please Select</option>
                                @foreach (\App\Models\SupportSystem\User::GREETING_DESCRIPTION_LIST as $key => $greeting)
                                    <option value="{{ $key }}">{{ $greeting }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!--Finaly Submit-->
                <div class="container" id="submit">
                    <img src="/mySupportSystem/newyear/curvebutton.png" alt="Submit"
                        style="width: 150px; font-weight: bolder; font-family: Georgia, 'Times New Roman', Times, serif; cursor: pointer;">
                    <div class="centered" style="cursor: pointer;">OK</div>
                </div>
            </div>
        </div>
    </div>


    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
        crossorigin="anonymous"></script>


    <script>
        $('#submit').click(function () {
            $('#newyear-form').submit();
        });

        $('.myFontLengthText').each(function() {
            var myFontLengthText = $(this);
            myFontLengthText.prev().on("keyup", function() {
                var character = $(this).val().length;
                if (character > 1) {
                    var isPlural = 'characters';
                } else {
                    var isPlural = 'character';
                }
                if (character < 20) {
                    myFontLengthText.html(character + ' ' + isPlural);
                } else if (character == 20) {
                    myFontLengthText.html("maximum 20 characters reach");
                }
            });
            myFontLengthText.prev().keyup();
        });

        //Update list data on selected text
        $('#greetinglist').change(function () {
            var selectedgreeting = $("#greetinglist option:selected").text();

            document.getElementById("greeting").value =selectedgreeting;
            greetingtext=selectedgreeting;
        });
    </script>
</body>
</html>
