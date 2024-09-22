<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Profile extends Model
{
    use HasFactory;
    protected $fillable = [
        'nama_lengkap',
        'alamat',
        'jenis_kelamin',
        'status_pernikahan',
    ];
}
