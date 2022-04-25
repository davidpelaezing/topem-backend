<?php

namespace App\Http\Controllers;

use App\Models\Detalle;
use Illuminate\Http\Request;

class DetalleController extends Controller
{
    /**
     * Almacena un detalle
     * @param Detalle array
     * @param Factura id
     * @return boolean
     * @author David Peláez
     */
    public static function guardar($detalle, $factura_id){
        
        /** Convertirmos el array en objeto */
        $ObjDetalle = json_decode($detalle);

        $nuevo_detalle = new Detalle();
        $nuevo_detalle->factura_id = $factura_id;
        $nuevo_detalle->descripcion = $ObjDetalle->descripcion;
        $nuevo_detalle->valor_unitario = $ObjDetalle->valor_unitario;
        $nuevo_detalle->cantidad = $ObjDetalle->cantidad;
        $nuevo_detalle->total = $ObjDetalle->total;
        
        return $nuevo_detalle->save();

    }

    /** 
     * Elimina un detalle
     * @param Detalle id
     * @return boolean
     * @author David Peláez
    */
    public static function eliminar($id){
        $detalle = Detalle::where('id', $id)->first();

        /** Validamos que el detalle exista */
        if(!$detalle){
            return false;
        }
        
        return $detalle->delete();
    } 


}
