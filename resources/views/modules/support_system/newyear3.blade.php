<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <title>New Year - Message</title>

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
    </style>
</head>

<body style=" background: lightblue url(/mySupportSystem/newyear/bg.jpg) no-repeat fixed center; -webkit-background-size: cover;-moz-background-size: cover; -o-background-size: cover;background-size: cover; ">
    <div class="d-flex align-items-center" style="text-align: center;  margin: 25px; justify-content: center; ">
        <div class="row">
            <div class="col">
                <!--Image and name data-->
                <img src='{{ !empty($user->user_image) ? "/mySupportSystem/newyear/users/{$user->id}/{$user->user_image}" : "/mySupportSystem/newyear/profilesample.png" }}' alt="" width="100px" height="100px" id="profile"
                    style="border-radius: 50%;">
                <h4 style="text-align: center;  margin: 5px; color: rgb(255, 255, 81);"></h4>
                <br/>
                <!--Bio Data-->
                <div class="row">
                    <div class="col">
                        <h6 style="text-align: center;  margin-top: 100px; color: rgb(255, 255, 255);">Thanks</h6>
                        <h6 id="name" style="text-align: center;  margin-top: 30px; color: rgb(255, 255, 255);  font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;color: rgb(255, 255, 255); font-size:xx-large; font-weight: bold;"  >{{ $user->user_name }}</h6>
                        <h6 style="text-align: center;  margin-top: 30px;color: rgb(255, 255, 255);">Please redeem<br/>the voucher at redemption counter</h6>
                    </div>
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
</body>
</html>
