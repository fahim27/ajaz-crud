<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
          integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/8.11.8/sweetalert2.css">
    <script
        src="https://code.jquery.com/jquery-3.5.0.min.js"
        integrity="sha256-xNzN2a4ltkB44Mc/Jz3pT4iU1cmeR0FkXs4pru/JxaQ="
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/8.11.8/sweetalert2.all.js"></script>

    <title>Ajax Crud</title>
    <style>
        .mt-10 {
            margin-top: 150px;
        }

        .text-black {
            color: #000000;
        }

        .full-width {
            width: 100%;
        }

        img#sliderDisplay {
            width: 280px;
            height: 180px;
            text-align: center;
            margin-left: 35px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8 mt-10">
            <button class="btn btn-primary" type="button" id="addBtn">Add</button>
            <br/>
            <br/>
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Class</th>
                    <th scope="col">Roll</th>
                    <th scope="col">Image</th>
                    <th scope="col">ction</th>
                </tr>
                </thead>
                <tbody>
                @foreach($students as $k=> $student)
                <tr>
                    <th scope="row">{{$k+1}}</th>
                    <td>{{$student->name}}</td>
                    <td>{{$student->class}}</td>
                    <td>{{$student->roll}}</td>
                    <td>{{$student->name}}</td>
                    <td>

                        <a class="btn btn-success text-white" id="{{$student->id}}">Edit</a>
                        <a class="btn btn-danger text-white" id="{{$student->id}}">Delete</a>
                    </td>

                </tr>
                @endforeach

                </tbody>
            </table>

        </div>
    </div>

</div>


<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-backdrop="static" tabindex="-1" role="dialog"
     aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-center" id="staticBackdropLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form enctype="multipart/form-data" id="modalForm">
                    @csrf
                    <div class="form-group">
                        <label class="text-black">Name</label>
                        <input type="text" class="form-control" id="name" name="name">
                    </div>
                    <div class="form-group">
                        <label class="text-black">Class</label>
                        <input type="number" class="form-control" id="class" name="class">
                    </div>
                    <div class="form-group">
                        <label class="text-black">Roll</label>
                        <input type="number" class="form-control" id="roll" name="roll">
                    </div>
                    <div class="form-group">
                        <label class="text-black">Image</label>
                        <img src="{{asset('preview.png')}}" id="sliderDisplay" onclick="triggerClick()">
                        <input type="file" name="image" onchange="displayImage(this)" class="form-control"
                               id="sliderImage" style="display: none;"></div>

                    <button type="submit" class="btn btn-primary full-width">Submit</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>

    //open the modal
    $("#addBtn").on('click', function (e) {
        $("#staticBackdrop").modal('show');
        $("#staticBackdropLabel").text('Add Student');
        let $action = "{{route('student.store')}}";
        $("#staticBackdropLabel").text('Add Student');
        $("#modalForm").attr('action', $action);

    });

    //ajax data store

    $('#modalForm').on('submit', function (event) {
        event.preventDefault();
        const CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        let formData = new FormData($(this)[0]);
        $.ajax({
            headers: {'X-CSRF-TOKEN': CSRF_TOKEN},
            url: $(this).attr('action'),
            method: "POST",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            // beforeSend: function () {
            //     $('.preloader').show();
            // },

            //success function
            success: function (resp) {
                if (resp.success == "OK") {
                    $("#staticBackdrop").modal('hide');
                    Swal.fire({
                        type: 'success',
                        text: "Student Add successfully",

                    });

                } else {
                    //  $('.preloader').hide();
                    Swal.fire({
                        type: 'error',
                        title: '<P style="color: red;">Oops...<p>',
                        text: resp.errors,
                        footer: '<b> Something Wrong</b>'
                    });
                    //  console.log(resp);
                }
            },
            //error function
            error: function (e) {
                alert("some thing want wrong");
            }
        });
    });



    //data update modal
    $(".btn-success").on('click',function (e) {
        let id=$(this).attr('id');
        let $action = "{{ route('student.edit',':id') }}";
        let $actionUpdate = "{{ route('student.update',':id') }}";
        const CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        let $baseLink = "{{ asset('storage') }}/";


        if (id>0){
           $.ajax({
               url: $action.replace(':id', id),
               type: "GET",
               dataType: 'json',
               cache: false,
               // beforeSend: function () {
               //     $('.preloader').show();
               // },

               //success function
               success: function (resp) {
                   if (resp.success == "OK") {
                       $("#staticBackdrop").modal('show');
                       $("#staticBackdrop").find('#name').val(resp.data.name);
                       $("#staticBackdrop").find('#roll').val(resp.data.roll);
                       $("#staticBackdrop").find('#class').val(resp.data.class);
                       $("#staticBackdrop").find('#sliderDisplay').attr('src',$baseLink+resp.data.image);
                       $("#staticBackdropLabel").text('Edit Student :'+ resp.data.name);
                       $("#modalForm").attr('action', $actionUpdate.replace(":id",id));
                       $("#modalForm").attr('method','PUT');


                   } else {
                       //  $('.preloader').hide();
                       Swal.fire({
                           type: 'error',
                           title: '<P style="color: red;">Oops...<p>',
                           text: resp.errors,
                           footer: '<b> Something Wrong</b>'
                       });
                       //  console.log(resp);
                   }
               },
               //error function
               error: function (e) {
                   alert("some thing want wrong");
               }
           });
       }



    })

    function triggerClick() {
        document.querySelector('#sliderImage').click();
    }

    function displayImage(e) {
        if (e.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                document.querySelector('#sliderDisplay').setAttribute('src', e.target.result);
            }
            reader.readAsDataURL(e.files[0]);
        }
    }

</script>


<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
        crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
        integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
        crossorigin="anonymous"></script>
</body>
</html>
