<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class UserDosen extends Model
{
    use HasFactory;
    protected $table = "userdosen";
    protected $fillable = [
        'uid',
        'userid',
        'name',
    ];

    public function absen_pegawai_1()
    {
        $bulan = date("m");
        $tahun = date("Y");
        $jumlah_tgl = cal_days_in_month(CAL_GREGORIAN, $bulan-1, $tahun);
        return $this->hasMany(AbsenDosen::class, 'id_pegawai', 'userid');
                        // ->where('waktu','>=',date('Y').'-'.(date('m')-3).'-21')
                        // ->where('waktu','<=',date('Y').'-'.(date('m')).'-'.$jumlah_tgl)->orderBy('waktu','asc');
    }
    public function solo()
    {
        
        $tanggal = 17;
        $bulan = 8;
        $tahun = 2025;
        return $this->hasOne(AbsenDosen::class, 'id_pegawai', 'userid')
                        ->where('waktu','>',Carbon::create($tahun, $bulan, $tanggal)->format('Y-m-d'))
                        ->where('waktu','<',Carbon::create($tahun, $bulan, $tanggal+1)->format('Y-m-d'));
                        // ->where('waktu','<=',date('Y').'-'.(date('m')).'-'.$jumlah_tgl)->orderBy('waktu','asc');
    }
    public function absen_pegawai_2()
    {
        return $this->hasMany(AbsenDosen::class, 'id_pegawai', 'userid')
                        ->where('waktu','>=',date('Y').'-'.(date('m')-3).'-1')
                        ->where('waktu','<=',date('Y').'-'.(date('m')).'-11')->orderBy('waktu','asc');
    }
    public function absen_pegawai_3()
    {
        return $this->hasMany(AbsenDosen::class, 'id_pegawai', 'userid')
                        ->where('waktu','>=',date('Y').'-'.(date('m')-3).'-11')
                        ->where('waktu','<=',date('Y').'-'.(date('m')).'-21')->orderBy('waktu','asc');
    }
}
