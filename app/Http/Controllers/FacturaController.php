<?php

namespace App\Http\Controllers;

use App\Models\Detalle;
use App\Models\Factura;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FacturaController extends Controller
{
    /**
     * Almacena una factura
     * @param Request $request
     * @return json
     * @author David Peláez
     */
    public function guardar(Request $request){
        try {
            
            /** Validamos los datos de entrada */
            $request->validate([
                'cliente_nombre' => 'required|string|max:255|min:3',
                'cliente_apellido' => 'required|string|max:255|min:3',
                'valor_sin_iva' => 'required|numeric|min:0',
                'iva' => 'required|numeric|min:0',
                'total' => 'required|numeric|min:0',
                'detalles' => 'required|array',
            ]);

            DB::beginTransaction();

            $factura = new Factura();
            $factura->vendedor_id = Auth::id();
            $factura->cliente_nombre = $request->cliente_nombre;
            $factura->cliente_apellido = $request->cliente_apellido;
            $factura->valor_sin_iva = $request->valor_sin_iva;
            $factura->iva = $request->iva;
            $factura->total = $request->total;
            $factura->save();

            /** Almacenamos los detalles */
            foreach($request->detalles as $detalle){
                if(!DetalleController::guardar($detalle, $factura->id)){
                    DB::rollBack();
                    return response()->json(['mensaje' => $detalle], 400);
                }
            }

            DB::commit();
            return response()->json(['factura' => $factura], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e]);
        }
    }

    /**
     * Edita una factura
     * @param Request $request
     * @param Factura $id
     * @return json
     * @author David Peláez
     */
    public function actualizar(Request $request, $id){
        try {
            /** Validamos los datos de entrada */
            $request->validate([
                'cliente_nombre' => 'required|string|max:255|min:3',
                'cliente_apellido' => 'required|string|max:255|min:3',
                'valor_sin_iva' => 'required|numeric|min:0',
                'iva' => 'required|numeric|min:0',
                'total' => 'required|numeric|min:0',
                'detalles' => 'required|array',
            ]);

            DB::beginTransaction();

            $factura = Factura::where('id', $id)->firstOrFail();
            $factura->vendedor_id = Auth::id();
            $factura->cliente_nombre = $request->cliente_nombre;
            $factura->cliente_apellido = $request->cliente_apellido;
            $factura->valor_sin_iva = $request->valor_sin_iva;
            $factura->iva = $request->iva;
            $factura->total = $request->total;
            $factura->save();

            /** Eliminamos todos los detalles */
            Detalle::where('factura_id', $factura->id)->delete();

            /** Almacenamos los detalles */
            foreach($request->detalles as $detalle){
                if(!DetalleController::guardar($detalle, $factura->id)){
                    DB::rollBack();
                    return response()->json(['mensaje' => $detalle], 400);
                }
            }

            return response()->json(['factura' => $factura], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e]);
        }
    }

    /**
     * Consulta una factura
     * @param Factura id
     * @return json
     * @author David Peláez
    */
    public function consultar($id){
        try {
            $factura = Factura::where('id', $id)->with('detalles')->firstOrFail();
            return response()->json(['factura' => $factura], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e]);
        }
    }
}
