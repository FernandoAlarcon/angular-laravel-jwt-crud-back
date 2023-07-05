<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use App\Models\actividades;
use Illuminate\Http\Request;

class ActividadesController extends Controller
{   
    public function __construct() {
        $this->middleware('auth:api');
    }

    public function index(Request $request)
    {   
       try {
        $Data        = trim($request->get('data'));
        $actividades = actividades::select('*')
        ->where('usuario','=',Auth()->user()->id)
        ->where(static function ($query) use ($Data) {
            $query->where('actividad' , 'LIKE', "%{$Data}%")
                  ->orWhere('horas'    , 'LIKE', "%{$Data}%");
        })  
        ->orderBy('id', 'DESC')      
        ->paginate(5);

        return [ 
            'pagination' => [
                'total'         => $actividades->total(),
                'current_page'  => $actividades->currentPage(),
                'per_page'      => $actividades->perPage(),
                'last_page'     => $actividades->lastPage(),
                'from'          => $actividades->firstItem(),
                'to'            => $actividades->lastItem(),
            ],
            'actividad' => $actividades, 
            'status'    => true
        ];
       } catch (\Throwable $th) {
        
        return [ 
            'message' => $th, 
            'status'  => false
        ];
       }/// end tryCatch 
       
    }
 
    public function store(Request $request)
    {
        try {
            $this->validate($request, [  
                'horas'       => 'required',  
                'actividad'   => 'required',  
            ]);

            $actividades = new actividades;
            $actividades->usuario      = Auth()->user()->id;
            $actividades->horas        = $request->input('horas'); 
            $actividades->actividad    = $request->input('actividad'); 
            $actividades->save();

            if($actividades){
                $resultado = true;
            }else{
                $resultado = false;
            }

            return  [
                'status' => $resultado,
                'message' => 'Actividad Agregada'
            ];

        }  catch (\Exception $e) {
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
                'actividad'   => 'required',  
            ]);

            $actividades = actividades::find($id); 
            $actividades->horas        = $request->input('horas'); 
            $actividades->actividad    = $request->input('actividad'); 
            $actividades->save();

            if($actividades){
                $resultado = true;
           }else{
                $resultado = false;
           }

           return  [
                'status'  => $resultado, 
                'message' => 'Registro Actualizado', 

            ];

        }  catch (\Exception $e) {
            //return $this->capturar($e, 'Error al actualizar Categoria');
            return [ 
                'message' => $e, 
                'status'  => false
            ];
        }
    }
  
    public function destroy($id,actividades $actividades)
    {   
        try {
            $actividades = actividades::find($id);
            $actividades->delete();
            
            return [ 
                'message' => 'actividad eliminada', 
                'status'  => true
            ];

        }  catch (\Exception $e) {
            ///return $this->capturar($e, 'Error al eliminar Categoria');
            return [ 
                'message' => $e, 
                'status'  => false
            ];
        }

    }
    
}
