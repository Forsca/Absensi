<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">    
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.3/css/dataTables.dataTables.css" />
    
    <title>Absen Dosen</title>
</head>
<body>
    <div >
        <main>
            <div class="py-5 text-center">
                <h2>Absen Dosen</h2>
                <form action="{{route('dosen_gas')}}" method="post" class="mt-5 col-md-3 " style="text-align: right">
                    @csrf
                    <div class="form-group">
                        <input type="month" class="form-control" name="tanggal" value=" @if (isset($tanggal)) {{$tanggal}} @endif  " required>
                        <button class="btn btn-primary mt-2 ml-auto fload-end"> submit </button>
                    </div>
                </form>
                <table class="table table-striped border mt-5" id="myTable">
                    <thead>
                        <tr>
                            <th>
                                No
                            </th>
                            <th>
                                Nama
                            </th>
                            
                            @php
                                $j=21;
                                for ($i=1; $i <= $jum ; $i++):
                            @endphp
                                 <th>
                                    @php
                                        echo $j;
                                    @endphp
                                </th>
                            @php
                                $j++;
                                if ($j > $jum) {
                                    $j = 1;
                                }
                                endfor;
                            @endphp
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $key => $value )
                            <tr>
                                <td rowspan="3">
                                    {{$key+1}}
                                </td>
                                <td rowspan="3">
                                   @php
                                      echo $value['nama'];
                                   @endphp
                                </td>
                                @foreach ($value['data'] as $item)
                                <td 
                                    @if ($item['telat'] == 'red')
                                        style="background: red"
                                    @endif >
                                    @php
                                        echo $item['telat'] != 'red' ? $item['telat'] : '' ;
                                    @endphp
                                </td>
                                @endforeach
                            </tr>
                            <tr>
                                @foreach ($value['data'] as $item)
                                <td  style=" @if ($item['absen'] == 'red') background: red;  @endif">
                                    @php
                                        echo $item['absen'] != 'red' ? $item['absen'] : '';
                                    @endphp
                                </td>
                                @endforeach
                            </tr>
                            <tr>
                                @foreach ($value['data'] as $item)
                                <td @if ($item['telat'] == 'red')
                                style="background: red"
                            @endif >
                                    @php
                                        echo $item['tidak_masuk'] != 'red' ? $item['tidak_masuk'] : '';
                                    @endphp
                                </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    {{-- <script src="https://cdn.datatables.net/2.1.3/js/dataTables.js"></script> --}}
</body>
</html>