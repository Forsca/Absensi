<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbsenDosen extends Model
{
    use HasFactory;

    protected $table = 'absendosen';
    protected $primaryKey = 'uid';

    protected $fillable  = ['id_pegawai', 'waktu', 'type', 'type_pinjer'];

    // public function pegawai()
    // {
    //     return $this->belongsTo(Pegawai::class, 'id_pegawai', 'userid');
    // }
}
