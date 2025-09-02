<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

</head>

<body>
    <div class="container mt-5">
        
        <div class="card mb-4 card">
            <div class="card-header">
                <h3> Nama Karyawan </h3>
            </div>
            <div class="card-body">
                <select class="form-control js-example-basic-single" id="mySelect">
                    @foreach ($data as $val )
                        <option value="{{ $val->userid }}" >{{ $val->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="card w-100">
            <div class="card-header">
                <h3> Laporan </h3>
            </div>
            <div class="card-body">
                <div id="output">
                    Load Data ......
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.js-example-basic-single').select2();
        });
        $(document).ready(function() {
            $('#mySelect').change(function() {
                var url = "{{ url('/laporan/proses') }}"+"/"+ $(this).val();
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(data) {
                        $('#output').html(data);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                    }
                });
            });
        });
    </script>
</body>

</html>