<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    use HasFactory;

    protected $table = 'produto';
    public $timestamps = true;

    protected $fillable = [
        'id',
        'name',
        'description',
        'price',
        'date_expiration',
        'image',
        'id_categoria'
    ];
}
