<?php

namespace App\Http\Controllers;

use App\Models\Devis;
use Illuminate\Http\Request;

class AjaxController extends Controller
{
    public function changeChoix(Request $request){
        $choix= $request->input('choix');
        $devis=new Devis();
        $donnees = $devis->getDonneeYear($choix);
        return response()->json([
            'donnees' => $donnees,
            'annee' => $choix
        ]);
    }
}
