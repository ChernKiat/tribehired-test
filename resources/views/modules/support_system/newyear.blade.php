<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <title>Hello</title>

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
                <img src="/mySupportSystem/newyear/profilesample.png" alt="" width="100px" height="100px" id="profile" style="border-radius: 50%;">
                <h4 style="text-align: center;  margin: 5px; color: rgb(255, 255, 81);">Profile Photo</h4>
                <input type="file" name="pic" accept="image/*" id="imgInp" style="color: #000000; background-color: rgb(255, 255, 255); border-radius: 1rem;">

                <h6 style="text-align: center;  margin-top: 30px; color: rgb(255, 255, 81);">Name</h6>
                <input  type="text" class="form-control form-rounded" placeholder="Enter Name"
                  style=" text-align: center; border-radius: 1rem; font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;color: rgb(0, 39, 110); font-size:xx-large; font-weight: bold;">
                <!--Bio Data-->
                <div class="row">
                    <div class="col">
                        <h6 style="text-align: start;  margin-top: 30px; color: rgb(255, 255, 81);">Year of birth</h6>
                        <div class="form-group">
                            <select class="form-control" name="" id="yearpicker"
                                style="text-align: center; border-radius: 1rem; font-weight: bold;">
                                <option value="">Please Select</option>
                            </select>
                        </div>

                        <script type="text/javascript"
                          src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
                        <script type="text/javascript">
                          let startYear = 1800;
                          let endYear = new Date().getFullYear();
                          for (i = endYear; i > startYear; i--) {
                            $('#yearpicker').append($('<option />').val(i).html(i));
                          }
                        </script>
                    </div>

                    <div class="col">
                        <h6 style="text-align: start;  margin-top: 30px; color: rgb(255, 255, 81);">Gender</h6>
                        <div class="form-group">

                            <select class="form-control" name="" id="gender"
                                style="text-align: center; border-radius: 1rem; font-weight: bold;">
                                <option value="">Please Select</option>
                                <option>Male</option>
                                <option>Female</option>
                                <option>Other</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!--Email-->
                <h6 style="text-align: start;  margin: 3px; color: rgb(255, 255, 81);">Email</h6>
                <input type="text" class="form-control form-rounded" placeholder="Enter Email"
                      style="text-align: center; border-radius: 1rem; font-weight: bold;">
                <!--CheckBox-->
                <div class="form-check">
                    <label class="form-check-label"
                        style="text-align: start;  margin: 3px; color: rgb(255, 255, 255); font-weight: bold;">
                        <input type="checkbox" class="form-check-input" name="" id="" value="checkedValue" checked>
                        User are agreed to share their personal info to participate the interactive
                    </label>
                </div>

                <!--Finaly Submit-->
                <div class="container" id ="submit">
                      <img  src="/mySupportSystem/newyear/curvebutton.png" alt="ok" style="width: 150px; font-weight: bolder; font-family: Georgia, 'Times New Roman', Times, serif; cursor: pointer;">
                      <div  class="centered" >OK</div>
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
          //do something
          alert("Clicked:  Ok");
        });


        function uploadImg(path) {
          alert(path);
        }

        imgInp.onchange = evt => {
            const [file] = imgInp.files
            if (file) {
              profile.src = URL.createObjectURL(file)
            }
          }
    </script>
</body>
</html>
