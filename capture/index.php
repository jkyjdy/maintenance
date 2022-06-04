<!DOCTYPE html>
<html>
<head>
    <!-- bootstrap  -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css"
        integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <br>
        <div class="alert alert-success">
            <h1>Aplikasi Galeri</h1>
            <h5>Belajar capture gambar dengan webcam, menyimpan data ke database, dan menampilkan hasilnya</h5>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <!-- form  -->
                        <form id="form">
                            <div class="form-group">
                                <label>name</label>
                                <input type="text" class="form-control" name="name" id="name" required>
                            </div>

                            <!-- kamera webcam akan ditampilkan di dalam id="my_camera" -->
                            <div id="my_camera">
                            </div>
                            <br>
                            <hr>
                            <button type="submit" class="tombol-simpan btn btn-primary">Register</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div id="data">

                </div>
            </div>
        </div>
    </div>
    <!-- jquery  -->
    <script src="https://code.jquery.com/jquery-3.5.1.js"
        integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"
        integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous">
    </script>
    <!-- bootstrap js  -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"
        integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous">
    </script>
    <!-- webcamjs  -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.25/webcam.js"></script>
    <script language="JavaScript">
        // menampilkan kamera dengan menentukan ukuran, format dan kualitas 
        Webcam.set({
            width: 320,
            height: 240,
            image_format: 'jpeg',
            jpeg_quality: 90
        });

        //menampilkan webcam di dalam file html dengan id my_camera
        Webcam.attach('#my_camera');

    </script>

    <script type="text/javascript">
        // saat dokumen selesai dibuat jalankan fungsi update
        $(document).ready(function () {
            update();
        });

        // jalankan aksi saat tombol register disubmit
        $(".tombol-simpan").click(function () {
            event.preventDefault();

            // membuat variabel image
            var image = '';

            //mengambil data uername dari form di atas dengan id name
            var name = $('#name').val();

            //mengambil data email dari form di atas dengan id email
            var email = $('#email').val();

            //memasukkan data gambar ke dalam variabel image
            Webcam.snap(function (data_uri) {
                image = data_uri;
            });

            //mengirimkan data ke file action.php dengan teknik ajax
            $.ajax({
                url: 'action.php',
                type: 'POST',
                data: {
                    name: name,
                    email: email,
                    image: image
                },
                success: function () {
                    alert('input data berhasil');
                    // menjalankan fungsi update setelah kirim data selesai dilakukan 
                    update()
                }
            })
        });


        //fungsi update untuk menampilkan data
        function update() {
            $.ajax({
                url: 'data.php',
                type: 'get',
                success: function (data) {
                    $('#data').html(data);
                }
            });
        }

    </script>
</body>

</html>