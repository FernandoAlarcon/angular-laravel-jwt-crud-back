<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use App\Models\reportes;
use Illuminate\Http\Request;

class ReportesController extends Controller
{   
    public function __construct() {
        $this->middleware('auth:api');
    }

    public function index(Request $request)
    {   
        $Data       = trim($request->get('data'));
        $actividad  = trim($request->get('actividad'));
        $reportes   = reportes::select('*')
        ->where('usuario',   '=',auth()->user()->id)
        ->where('actividad', '=',$actividad)

        ->where(static function ($query) use ($Data) {
            $query->where('created_at' , 'LIKE', "%{$Data}%")
                  ->orWhere('horas'    , 'LIKE', "%{$Data}%");
        }) 
        ->orderBy('id', 'DESC')      
        ->paginate(5);

        return [
            'pagination' => [
                'total'         => $reportes->total(),
                'current_page'  => $reportes->currentPage(),
                'per_page'      => $reportes->perPage(),
                'last_page'     => $reportes->lastPage(),
                'from'          => $reportes->firstItem(),
                'to'            => $reportes->lastItem(),
            ],
            'reportes' => $reportes
        ];
    }
 
    public function store(Request $request)
    {
        try {
            $this->validate($request, [
                'actividad'   => 'required',  
                'horas'       => 'required'
            ]);

            $reportes = new reportes();
            $reportes->usuario      = Auth()->user()->id; 
            $reportes->horas        = strval($request->input('horas')); 
            $reportes->actividad    = strval($request->input('actividad')); 
            $reportes->descripcion  = " "; 
            $reportes->save();

            if($reportes){
                $resultado = true;
           }else{
                $resultado = false;
           }

            return  [
                'status' => $resultado,
                'message' => 'Reporte Agregada'
            ];

        }  catch (\Exception $e) {
            //        return $this->capturar($e, 'Error al guardar Categoria');
            return [ 
                'message' => $e, 
                'status'  => false
            ];
        }
    }
 
    public function update(Request $request, $id)
    {
        try {
            $this->validate($request, [ 
                'horas'       => 'required',  
                'actividad'   => 'required'
            ]);

            $reportes = reportes::find($id);
            $reportes->usuario      = Auth()->user()->id;
            $reportes->horas        = strval($request->input('horas')); 
            $reportes->actividad    = strval($request->input('actividad')); 
            $reportes->descripcion  = " "; 
            $reportes->save();

            if($reportes){
                $resultado = true;
           }else{
                $resultado = false;
           }

            return  [
                'succes' => $resultado
            ];

        }  catch (\Exception $e) {
            return $this->capturar($e, 'Error al actualizar Categoria');
        }
    }
  
    public function destroy($id,reportes $reportes)
    {   
        try {
            $reportes = reportes::find($id);
            $reportes->delete();
            return;
        }  catch (\Exception $e) {
            return $this->capturar($e, 'Error al eliminar Categoria');
        }

    }
    
}
