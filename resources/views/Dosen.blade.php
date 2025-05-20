<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
      <!-- jQuery library -->
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <!-- DataTables JS -->
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>

    <title>Document</title>
</head>
<body>
   
<form action="{{route('gas')}}" method="post">
    @csrf
    <select name="id" id="">
        @foreach ( $user as $key => $value )
            <option value="{{$value['userid']}}"> 
                {{$value['name']}}
            </option>
        @endforeach
    </select>
    <button>gas</button>
</form>
<hr>
    
<div class="row">
    <table id="example" class="display" style="width:80%">
        <thead>
            <tr>
               @for ($i = 0; $i < count($data);$i++)
                    <th @if ($data[$i]['day'] == 'San') style="background: red" @endif>
                        @php
                            echo $data[$i]['hari']; 
                        @endphp
                    </th>
               @endfor
            </tr>
        </thead>
        <tbody>
            <tr>
                @for ($i = 0; $i < count($data);$i++)
                <td @if ($data[$i]['day'] == 'Sun') style="background: red" @endif>
                    @php
                        echo $data[$i]['telat']; 
                    @endphp
                </td>
               @endfor
            </tr>
            <tr >
                @for ($i = 0; $i < count($data);$i++)
                    <td @if ($data[$i]['day'] == 'Sun') style="background: red" @endif>
                        @php
                            echo $data[$i]['tidak_absen']; 
                        @endphp
                    </td>
               @endfor
            </tr>
            <tr  >
                @for ($i = 0; $i < count($data);$i++)
                    <td @if ($data[$i]['day'] == 'Sun') style="background: red" @endif>
                        @php
                            echo $data[$i]['tanpa_keterangan']; 
                        @endphp
                    </td>
               @endfor
            </tr>
        </tbody>
    </table>
</div>

   <script>
        $(document).ready(function() {
            $('#example').DataTable();
        });
    </script>
</script>
</body>
</html>