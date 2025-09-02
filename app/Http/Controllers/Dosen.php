<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Rats\Zkteco\Lib\ZKTeco;
use Illuminate\Support\Facades\File;
use App\Models\UserDosen;
use App\Models\AbsenDosen;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Dosen extends Controller
{
    public function index()
    {
        $data = [
            'data'=>  array() ,
            'jum' => 0
        ];
        return view('dosen.index',$data);
    }
    public function absen_dosen(Request $request){
        $tanggal = $request->tanggal;
        $bulan = Carbon::parse($tanggal)->month - 1;
        $tahun = Carbon::parse($tanggal)->year;
        // $bulan = 7;$tahun = 2024;
        $jumlah_tgl = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
        $data_all = UserDosen::with('absen_pegawai_1')->get();
        foreach ($data_all as $key => $value) {
            if(count($value->absen_pegawai_1) > 0){
                $tmp[$key]['nama'] = $value->name;
                $tmp[$key]['data'] = $this->hitung($value->absen_pegawai_1,$tahun,$bulan,$jumlah_tgl);
            }
        }
        $data = [
            'data' => $tmp,
            'tanggal' => $request->tanggal,
            'jum' => $jumlah_tgl
        ];
        // echo json_encode($data);
        return view("dosen.index",$data);
        
    }
    public function db_absen_dosen(){
   
        $data2 = json_decode(File::get('Absen/data_dosen1.txt'), true);
        $data1 = json_decode(File::get('Absen/data_dosen2.txt'), true);
        $data3 = json_decode(File::get('Absen/data_staf.txt'), true);
        $user = json_decode(File::get('Absen/user_dosen1.txt'), true);
        $user2 = json_decode(File::get('Absen/user_dosen2.txt'), true);
        $user3 = json_decode(File::get('Absen/user_staf.txt'), true);
        $data_user = collect($user)->merge(collect($user2))->merge(collect($user3));
        $data_dosen = collect($data2)->merge(collect($data1))->merge(collect($data3));
        $bulan = Carbon::parse(Carbon::now())->month;
        $tahun = Carbon::parse(Carbon::now())->year;
        $jumlah_tgl_N = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
        
        UserDosen::truncate();
        AbsenDosen::truncate();
        foreach ($data_user as $val) {
            $data = UserDosen::where('userid',$val['userid']);
            // if (!$data->first()) {
                $data = ['userid' => $val['userid'], 'name' => $val['name'] ];
                UserDosen::create($data);
            // }
        }
        $tmp1 = collect($data_dosen)->where('timestamp','>=',Carbon::create($tahun,$bulan-2,1)->format('Y-m-d'))
            ->where('timestamp','<=',Carbon::create($tahun,$bulan,$jumlah_tgl_N)->add(1,'day')->format('Y-m-d'));
        foreach ($tmp1 as $row) {
            if(!$row['id']){
                $data_absen[] = ['id_pegawai' => null, 'waktu' => $row['timestamp'], 'type' => $row['type']];
            }else{
                $data_absen[] = ['id_pegawai' => $row['id'], 'waktu' => $row['timestamp'], 'type' => $row['type']];
            }
        }
        try {
            DB::beginTransaction();
            foreach (collect($data_absen) as $item) {
                AbsenDosen::create($item); 
            }
            DB::commit();
        } catch (\Exception $e) {
            throw $e; 
        }
        return redirect()->route('dosen');
    }
    public function hitung($param,$tahun,$bulan,$jumlah_tgl)
    {   
        $j = 21;
        for ($i=0; $i < $jumlah_tgl ; $i++) {
            $tmp = $param->where('waktu','>',Carbon::create($tahun,$bulan,$j)->format('Y-m-d'))
                        ->where('waktu','<',Carbon::create($tahun,$bulan,$j)->add(1,'day')->format('Y-m-d'))
                        ->sortBy('waktu');
            $data[$i]['tgl'] = Carbon::create($tahun,$bulan,$j)->format('Y-m-d');
            $data[$i]['data'] = $tmp;
            if(Carbon::create($tahun,$bulan,$j)->format('D') == 'Sun'){ 
                $data[$i]['telat'] = 'red';
                $data[$i]['absen'] = 'red';
                $data[$i]['tidak_masuk'] = 'red';
            }elseif(Carbon::create($tahun,$bulan,$j)->format('D') == 'Sat'){ 
                $absen = 2;
                $absen = $absen - (count($tmp->where('type', 0)) ? 1 : 0 );
                $absen = $absen - (count($tmp->where('type', 1)) ? 1 : 0 );
                if ($absen == 2) {
                    $data[$i]['telat'] = 0;
                    $data[$i]['absen'] = 0;
                    $data[$i]['tidak_masuk'] = 1;
                }else{
                    $tmp2 = isset($tmp->where('type', 0)->first()->waktu) ? $tmp->where('type', 0)->first()->waktu : '0';
                    $telat = intval(Carbon::create($tahun,$bulan,$j, 8, 0, 0,)->diffInMinutes(Carbon::create($tmp2)));
                    $data[$i]['telat'] = $telat > 0 ? $telat : '0' ;
                    $data[$i]['absen'] = $absen;
                    $data[$i]['tidak_masuk'] = 0;
                }
            }else if(Carbon::create($tahun,$bulan,$j)->format('D') == 'Fri'){ 
                $absen = 4;
                $absen = $absen - (count($tmp->where('type', 0)) ? 1 : 0 );
                $absen = ($absen - ((count($tmp->where('type', 4)) + (count($tmp->where('type', 2))) ? 1 : 0 )));
                $absen = ($absen - ((count($tmp->where('type', 5)) + (count($tmp->where('type', 3))) ? 1 : 0 )));
                $absen = $absen - (count($tmp->where('type', 1)) ? 1 : 0 );
                if ($absen == 4) {
                    $data[$i]['telat'] = 0;
                    $data[$i]['absen'] = 0;
                    $data[$i]['tidak_masuk'] = 1;
                }else{
                    $tmp2 = isset($tmp->where('type', 0)->first()->waktu) ? $tmp->where('type', 0)->first()->waktu : '0';
                    $telat1 = intval(Carbon::create($tahun,$bulan,$j, 8, 0, 0,)->diffInMinutes(Carbon::create($tmp2)));
                    if (isset($tmp->where('type', 5)->first()->waktu)) {
                        $tmp3 = $tmp->where('type', 5)->first()->waktu;
                    }else if (isset($tmp->where('type', 3)->first()->waktu)) {
                        $tmp3 = $tmp->where('type', 3)->first()->waktu;
                    }else{
                        $tmp3 = 0;
                    }
                    $telat2 = $tmp3 > 0 ? intval(Carbon::create($tahun,$bulan,$j, 14, 0, 0,)->diffInMinutes(Carbon::create($tmp3))) : 0;
                    $data[$i]['telat'] = ($telat1 > 0 ? $telat1 : '0') + ($telat2 > 0 ? $telat2 : '0') ;
                    $data[$i]['absen'] = $absen;
                    $data[$i]['tidak_masuk'] = 0;
                }
            }else{
                $absen = 4;
                $absen = $absen - (count($tmp->where('type', 0)) ? 1 : 0 );
                $absen = $absen - (count($tmp->where('type', 4)) + count($tmp->where('type', 2)) ? 1 : 0 );
                $absen = $absen - (count($tmp->where('type', 5)) + (count($tmp->where('type', 3))) ? 1 : 0 );
                $absen = $absen - (count($tmp->where('type', 1)) ? 1 : 0 );
                if ($absen == 4) {
                    $data[$i]['telat'] = 0;
                    $data[$i]['absen'] = 0;
                    $data[$i]['tidak_masuk'] = 1;
                }else{
                    $tmp2 = isset($tmp->where('type', 0)->first()->waktu) ? $tmp->where('type', 0)->first()->waktu : '0';
                    // $tmp3 =  isset($tmp->where('type', 5)->first()->waktu) ? $tmp->where('type', 5)->first()->waktu : '0';
                    if (isset($tmp->where('type', 5)->first()->waktu)) {
                        $tmp3 = $tmp->where('type', 5)->first()->waktu;
                    }else if (isset($tmp->where('type', 3)->first()->waktu)) {
                        $tmp3 = $tmp->where('type', 3)->first()->waktu;
                    }else{
                        $tmp3 = 0;
                    }
                    $telat1 = intval(Carbon::create($tahun,$bulan,$j, 8, 0, 0,)->diffInMinutes(Carbon::create($tmp2)));
                    $telat2 = $tmp3 > 0 ? intval(Carbon::create($tahun,$bulan,$j, 13, 30, 0,)->diffInMinutes(Carbon::create($tmp3))) : 0;

                    $data[$i]['telat'] = ($telat1 > 0 ? $telat1 : '0') + ($telat2 > 0 ? $telat2 : '0') ;
                    $data[$i]['absen'] = $absen;
                    $data[$i]['tidak_masuk'] = 0;
                }
            }
            
            if($j == ($jumlah_tgl+1)){
                $j = 1;
                $bulan = $bulan + 1;
            }
            $j++;
        }
        return ($data);        
    }
    public function proses()
    {
        $zk = new ZKTeco('192.168.203.254');
        $zk->connect();
        $zk->enableDevice();
        $user = $zk->getUser();
        $attendance = $zk->getAttendance(); 
        $user = json_encode($user);
        $data = json_encode($attendance);
        File::put('Absen/user_dosen1.txt', $user);
        File::put('Absen/data_dosen1.txt', $data);
    }
    public function proses1()
    {
        $zk = new ZKTeco('172.16.16.242');
        $zk->connect();
        $zk->enableDevice();
        $user = $zk->getUser(); 
        $attendance = $zk->getAttendance(); 
        $user = json_encode($user);
        $data = json_encode($attendance);
        File::put('Absen/user_dosen2.txt', $user);
        File::put('Absen/data_dosen2.txt', $data);
    }
    public function proses2()
    {
        // $zk = new ZKTeco('192.168.254.26');
        $zk = new ZKTeco('192.168.19.2');
        $zk->connect();
        $zk->enableDevice();
        $attendance = $zk->getAttendance();
        $data = json_encode($attendance);
        File::put('Absen/data_staf.txt', $data);
        $user = $zk->getUser(); 
        $user = json_encode($user);
        File::put('Absen/user_staf.txt', $user);
    }
}
