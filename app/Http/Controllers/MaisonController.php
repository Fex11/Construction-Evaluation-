<?php

namespace App\Http\Controllers;

use App\Models\Devis;
use App\Models\Maison;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MaisonController extends Controller
{
    public function listeTravaux(){
        $maison = new Maison();
        $travaux = $maison->getAllTravaux();
        return view('travaux.liste',[
           'travaux' => $travaux,
        ]);
    }

    public function updateTravaux(Request $request){
        $request->validate([
            'id' => 'required',
            'code' => 'required',
            'travaux' => 'required',
            'unite' => 'required',
            'pu' => 'required',
        ]);

        DB::table('travaux')
            ->where('id', $request->input("id"))
            ->update(['code' => $request->input("code") , 'travaux' => $request->input("travaux") , 'unite' => $request->input("unite") , 'pu' => $request->input("pu")]);

        return redirect()->intended('travaux/liste')->with('success', 'Modification reussi');
    }

    public function listeFinition(){
        $devis = new Devis();
        $finition = $devis->getAllFinition();
        return view('finition.liste',[
            'finition' => $finition,
        ]);
    }

    public function updateFinition(Request $request){
        $request->validate([
            'id' => 'required',
            'taux' => 'required',
            'finition' => 'required',
        ]);

        DB::table('finition')
            ->where('id', $request->input("id"))
            ->update(['taux_finition' => $request->input("taux") ]);

        return redirect()->intended('finition/liste')->with('success', 'Modification reussi');
    }
}
