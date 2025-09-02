<div class="row">
    @php
        use Carbon\Carbon;
        $a = $hari;
        $b = $bulan;
    @endphp
    @for ($i = 0; $i < $jum_hari; $i++)
        <div class="col-2" style="border:1px;
        @if(Carbon::parse($tahun.'-'.$b.'-'.$a)->format('D') == "Sun") 
            color:red;
        @endif
        @if(Carbon::parse($tahun.'-'.$b.'-'.$a)->format('D') == "Fri") 
            color:blue;
        @endif
        ">
            <hr>
            {{Carbon::parse($tahun.'-'.$b.'-'.$a)->format('d-m-Y')}}
            <hr>
            @foreach ($data->filter(function ($item) use ($a,$b) {
                return Carbon::parse($item->waktu)->day == $a && Carbon::parse($item->waktu)->month == $b;
            }) as $item)
                <p>{{Carbon::parse($item->waktu)->format('H:i:s')}}</p>
            @endforeach
        </div>
        @php
            $a++;
            if ($a > $jum) {
                $a = 1;
                $b = $b+1;
            }
        @endphp
    @endfor
</div>