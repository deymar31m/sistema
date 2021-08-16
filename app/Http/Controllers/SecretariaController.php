<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Secretaria;

class SecretariaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar==''){
            $secretarias = Secretaria::orderBy('id', 'desc')->paginate(3);
        }
        else{
            $secretarias = Secretaria::where($criterio, 'like', '%'. $buscar . '%')->orderBy('id', 'desc')->paginate(3);
        } 
        
        return [
            'pagination'=> [
                'total'         => $secretarias->total(),
                'current_page'  => $secretarias->currentPage(),
                'per_page'      => $secretarias->perPage(),
                'last_page'     => $secretarias->lastPage(),
                'from'          => $secretarias->firstItem(),
                'to'            => $secretarias->lastItem(),
            ],
            'secretarias' => $secretarias
        ];
    }

    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $secretaria = new Secretaria();
        $secretaria->nombre = $request->nombre;
        $secretaria->sigla = $request->sigla;
        $secretaria->responsable = $request->responsable;
        $secretaria->cargo = $request->cargo;
        $secretaria->condicion = '1';
        $secretaria->save();
    }

    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $secretaria = Secretaria::findOrFail($request->id);
        $secretaria->nombre = $request->nombre;
        $secretaria->sigla = $request->sigla;
        $secretaria->responsable = $request->responsable;
        $secretaria->cargo = $request->cargo;
        $secretaria->condicion = '1';
        $secretaria->save();
}

public function desactivar (Request $request){
    if (!$request->ajax()) return redirect('/');
    $secretaria = Secretaria::findOrFail($request->id);
    $secretaria->condicion = '0';
    $secretaria->save();

}


public function activar (Request $request){
    if (!$request->ajax()) return redirect('/');   
    $secretaria = Secretaria::findOrFail($request->id);
    $secretaria->condicion = '1';
    $secretaria->save();
}
}