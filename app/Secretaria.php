<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Secretaria extends Model
{
    protected $fillable = ['nombre', 'sigla', 'responsable', 'cargo', 'condicion',];
}