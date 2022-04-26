<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    use HasFactory;

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i',
    ];

    public function detalles(){
        return $this->hasMany(Detalle::class);
    }

    public function vendedor(){
        return $this->belongsTo(User::class, 'vendedor_id');
    }

    /**
     * Busca coincidencia en el nombre y/o nit del cliente o del usuario que emite la factura
     * @param String $buscar
     * @author David Peláez
    */
    public function scopeWhereLike($query, $buscar){
        if($buscar){
            return $query->whereHas('vendedor', function (Builder $query) use ($buscar){
                $query->where('name', 'like', '%'.$buscar.'%');
            })
                ->orWhere('cliente_nombre', 'like', '%'.$buscar.'%')
                ->orWhere('cliente_nit', 'like', '%'.$buscar.'%');
        }
    }



}
