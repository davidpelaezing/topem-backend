<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use League\CommonMark\Delimiter\Delimiter;

class Factura extends Model
{
    use HasFactory;

    public function detalles(){
        return $this->hasMany(Detalle::class);
    }
}
