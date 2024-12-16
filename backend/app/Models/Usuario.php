<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    /** @use HasFactory<\Database\Factories\UsuarioFactory> */
    use HasFactory;

    protected $table = 'usuarios';
    protected $hidden = ['password'];
    protected $fillable = ['name', 'cpf', 'email', 'password'];
}
