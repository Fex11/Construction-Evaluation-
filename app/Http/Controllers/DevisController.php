<?php

namespace App\Http\Controllers;

use App\Models\Devis;
use App\Models\Maison;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Exception;

class DevisController extends Controller
{
    public function createDevis(Request $request){
        $request->validate([
            'maison' => 'required',
            'finition' => 'required',
            'date_debut' => 'required|date',
            'ref_d' => 'required',
            'lieu' => 'required'
        ],[
            'maison.required' => '',
        ]);

        $user = Session::get('client');
        $devis = new Devis();
        $deviss = $devis->createDevis($user,$request->input('maison'),$request->input('date_debut'),$request->input('finition'),$request->input('ref_d'),$request->input('lieu'));

        $maison = new Maison();
        $travaux= $maison->getTravauxByIdMaison($request->input('maison'));
        foreach ($travaux as $t){
            DB::table('travaux_devis')->insert([
                'code_travaux' => $t->code,
                'ref_devis' => $deviss->ref_devis,
                'pu' => $t->pu,
                'qte' => $t->qte,
            ]);
        }

        $finition = $devis->getFinitionById($request->input('finition'));
        DB::table('finition_devis')->insert([
            'id_finition' => $finition->id,
            'ref_devis' => $deviss->ref_devis,
            'taux_finition' => $finition->taux_finition,
        ]);

        return redirect()->intended('devis/goToCreate')->with('success', 'Creation reussi');
    }

    public function goToCreateDevis(){
        $maison = new Maison();
        $devis =new Devis();
        $finition = $devis->getAllFinition();
        $maisons = $maison->getAllMaison();
        return view('devis.create',[
           'finitions' => $finition,
            'maisons' => $maisons,
        ]);
    }

    public function listeDevis(){
        $devis =new Devis();
        $deviss= $devis->getDevisClient();
        return view('devis.liste',[
            'devis' => $deviss,
        ]);
    }

    public function listeDevisAdmin(){
        $devis =new Devis();
        $deviss= $devis->getAllDevis();
        return view('devis.listeAdmin',[
            'devis' => $deviss,
        ]);
    }

    public function getPaiement($ref){
        return view('devis.paiement',[
            'ref' => $ref,
        ]);
    }

    public function getAllInfoDevis($ref){
        $d=new Devis();
        $travaux=$d->getTravauxByRef($ref);
        $devis=$d->getDevisByRef($ref);
        $paiement=$d->getPaiementByRef($ref);
        $total=0;
        foreach ($paiement as $p) {
            $total=$total+$p->montant;
        }
        $data = [
            'travaux' => $travaux,
            'devis' => $devis,
            'paiement' => $paiement,
            'total' => $total
        ];
        $pdf=Pdf::loadView('devis.export',$data);
        return $pdf->download($devis->ref_devis.'.pdf');
    }

    public function getDetailDevis($ref){
        $d=new Devis();
        $travaux=$d->getTravauxByRef($ref);
        $devis=$d->getDevisByRef($ref);
        return view('devis.devis',[
           'travaux' => $travaux,
           'devis' => $devis,
        ]);
    }

    public function paiementDevis(Request $request){
        $ref=$request->input('ref_d');
        $d =new Devis();
        $devis=$d->getDevisByRef($ref);
        $montant = $request->input('montant');

        $rules = [
            'ref_p' => 'required',
            'date_paiement' =>'required',
            'montant' => 'required|numeric|min:0',
        ];
        $customMessages = [
            'ref_p.required' => 'Reference paiment obligatoire',
            'date_paiement.required' =>'Date paiement obligatoire',
            'montant.required' => 'Montant obligatoire',
            'montant.numeric' => 'Montant doit etre numerique',
            'montant.min' => 'Montant doit etre positif',
        ];
        $donnees =$request->all();
        $validator = Validator::make($donnees, $rules, $customMessages);
        if($devis->reste == 0){
            return response()->json([
                'classs' => 'alert alert-primary',
                'messagee' => 'Totalité payé',
            ]);
        }else{
            if ($validator->fails()) {
                $errors = $validator->errors()->all();
                $mess='';
                foreach ($errors as $error) {
                    $mess = $mess.' =>'.$error;
                }
                return response()->json([
                    'classs' => 'alert alert-danger',
                    'messagee' => $mess,
                ]);
            } else {
                if($montant <= $devis->reste){
                    try {
                        DB::table('paiement')->insert([
                            'ref_paiement' => $request->input('ref_p'),
                            'ref_devis' => $request->input('ref_d'),
                            'date_paiement' => $request->input('date_paiement'),
                            'montant' => $request->input('montant'),
                        ]);
                    }catch (Exception $e){

                    }
                    return response()->json([
                        'classs' => 'alert alert-success',
                        'messagee' => 'Paiement de '.number_format($montant, 0, ',', ' ').' Ar reussi',
                    ]);
                }else{
                    $depasse = $montant - $devis->reste;
                    return response()->json([
                        'classs' => 'alert alert-danger',
                        'messagee' => 'Miotra '.number_format($depasse, 0, ',', ' ').' Ar (Reste a payé : '.number_format($devis->reste, 0, ',', ' ').' Ar)',
                    ]);
                }
            }
        }
    }
}
