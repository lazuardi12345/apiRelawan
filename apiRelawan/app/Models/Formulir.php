<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Formulir extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama', 'jenis_kelamin', 'tempat', 'tanggal_lahir',
        'provinsi', 'kabupaten', 'kecamatan', 'kelurahan',
        'email', 'no_hp', 'ktp', 'motivasi', 'kontribusi'
    ];
}
