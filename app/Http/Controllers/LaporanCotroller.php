<?php

namespace App\Http\Controllers;

use App\Models\AbsenDosen;
use App\Models\User;
use App\Models\UserDosen;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LaporanCotroller extends Controller
{
    public function index()
    {
        $data['data'] = UserDosen::all();
        return view('laporan.index', $data);
    }

    public function proses($id)
    {
        $tanggal = 1;
        $bulan = 8;
        $tahun = 2025;
        $jumlah_tgl = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
        $data['hari'] = $tanggal;
        $data['bulan'] = $bulan;
        $data['tahun'] = $tahun;
        $data['jum'] = $jumlah_tgl;
        $data['jum_hari'] = 31; //tambah hari
        $data['data'] = AbsenDosen::where('id_pegawai', $id)->where('waktu', '>', Carbon::create($tahun, $bulan, $tanggal)->format('Y-m-d'))
            ->where('waktu', '<', Carbon::create($tahun, $bulan + 1, $tanggal)->format('Y-m-d'))
            ->orderBy('waktu')->get();
        return view('laporan.laporan', $data);
    }
    public function solo()
    {
        $data['data'] = UserDosen::with('solo')->get();
        // echo json_encode($data['data']);
        return view('laporan.laporan_solo', $data);
    }
}
