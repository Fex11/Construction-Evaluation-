<?php

namespace App\Http\Controllers;

use App\Imports\Import;
use App\Models\ImportModel;
use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;


class ImportController extends Controller
{
    public function importCsv(Request $request){
        /*$request->validate([
            'file' => ['required','mimes:csv,xlsx'],
        ]);*/

        //TRAVAUX et MAISON
        $r = Excel::toArray(new Import(),$request->file('file'))[0];
        $rules = [
            'type_maison' => 'required',
            'description' =>'required',
            'surface' => 'required',
            'code_travaux' =>'required',
            'type_travaux' => 'required',
            'unite' => 'required',
            'prix_unitaire' =>'required',
            'quantite' => 'required',
            'duree_travaux' => 'required'
        ];



        $customMessages = [
            'type_maison.required' => 'type_maison est requis',
            'description.required' =>'description est requis',
            'surface.required' => 'surface est requis',
            'code_travaux.required' =>'code_travaux est requis',
            'type_travaux.required' => 'type_travaux est requis',
            'unite.required' => 'unité est requis',
            'prix_unitaire.required' => 'prix_unitaire est requis',
            'quantite.required' => 'qte est requis',
            'duree_travaux.required' => 'duree_travaux est requis'
        ];

        $erreur = [];
        $validation=[];
        $i=1;

        foreach ($r as $row){
            $validator = Validator::make($row, $rules, $customMessages);
            if ($validator->fails()) {
                $errors = $validator->errors()->all();
                foreach ($errors as $error) {
                    $validation[]=$error .' (Ligne '.$i.')';
                }
            } else {
                try{
                    DB::table('import')->insert([
                        'type_maison' => $row['type_maison'],
                        'descri' =>$row['description'],
                        'surface' => $row['surface'],
                        'code_travaux' =>$row['code_travaux'],
                        'travaux' => $row['type_travaux'],
                        'unite' => $row['unite'],
                        'pu' =>str_replace(',', '.',$row['prix_unitaire']),
                        'qte' =>str_replace(',', '.',$row['quantite']),
                        'duree_travaux' => $row['duree_travaux']
                    ]);
                }catch(\Exception $e){
                    $erreur[]=$e->getMessage().' : Erreur a la ligne'.' '.$i.' : '.$row['type_maison'].','.$row['description'].','.$row['surface'].','.$row['code_travaux'].','.$row['type_travaux'].','.$row['unite'].','.$row['prix_unitaire'].','.$row['quantite'].','.$row['duree_travaux'];
                }
            }
            $i++;
        }

        try{;
            DB::insert('insert into maison (type_maison,descri,surface,duree_travaux) select type_maison,descri,surface,duree_travaux from import group by type_maison,descri,surface,duree_travaux');
        }catch(\Exception $e){
            $erreur[]=$e->getMessage();
        }

        try{
            DB::insert('insert into travaux (code,travaux,unite,pu) select code_travaux,travaux,unite,pu from import group by code_travaux,travaux,unite,pu');
        }catch(\Exception $e){
            $erreur[]=$e->getMessage();
        }

        try{
            DB::insert('insert into travaux_maison (id_maison,id_travaux,qte) select m.id,t.id,i.qte from import i join maison m on m.type_maison=i.type_maison join travaux t on t.code=i.code_travaux group by m.id,t.id,i.qte');
        }catch(\Exception $e){
            $erreur[]=$e->getMessage();
        }

        //DEVIS
        $rr = Excel::toArray(new Import(),$request->file('devis'))[0];
        $ruless = [
            'client' => 'required',
            'ref_devis' =>'required',
            'type_maison' => 'required',
            'finition' =>'required',
            'taux_finition' => 'required',
            'date_devis' => 'required',
            'date_debut' =>'required',
            'lieu' => 'required',
        ];
        $customMessagess = [
            'client.required' => 'client est requis',
            'ref_devis.required' =>'ref_devis est requis',
            'type_maison.required' => 'type_maison est requis',
            'finition.required' =>'finition est requis',
            'taux_finition.required' => 'taux_finition est requis',
            'date_devis.required' => 'date_devis est requis',
            'date_devis.date' => 'date_devis invalide',
            'date_debut.date' => 'date_debut invalide',
            'date_debut.required' => 'date_debut est requis',
            'lieu.required' => 'lieu est requis',
        ];

        $i=1;

        foreach ($rr as $row){
            $validator = Validator::make($row, $ruless, $customMessagess);
            if ($validator->fails()) {
                $errors = $validator->errors()->all();
                foreach ($errors as $error) {
                    $validation[]=$error .' (Ligne '.$i.')';
                }
            } else {
                try{
                    DB::table('importdevis')->insert([
                        'client' => $row['client'],
                        'ref_devis' =>$row['ref_devis'],
                        'type_maison' =>$row['type_maison'],
                        'finition' => $row['finition'],
                        'taux_finition' =>str_replace(',','.',str_replace('%', '',$row['taux_finition'])),
                        'date_devis' => $row['date_devis'],
                        'date_debut' => $row['date_debut'],
                        'lieu' =>$row['lieu'],
                    ]);
                }catch(\Exception $e){
                    $erreur[]=$e->getMessage().' : Erreur a la ligne'.' '.$i.' : '.$row['client'].','.$row['ref_devis'].','.$row['finition'].','.$row['taux_finition'].','.$row['date_devis'].','.$row['date_debut'].','.$row['lieu'];
                }
            }
            $i++;
        }

        try{;
            DB::insert('insert into client (contact) select client from importdevis group by client');
        }catch(\Exception $e){
            $erreur[]=$e->getMessage();
        }

        try{
            DB::insert('insert into finition (finition,taux_finition) select finition,taux_finition from importdevis group by finition,taux_finition');
        }catch(\Exception $e){
            $erreur[]=$e->getMessage();
        }

        try{
            DB::insert('insert into devis (ref_devis,date_devis,date_debut,lieu,id_client,id_maison,id_finition) select i.ref_devis,i.date_devis,i.date_debut,i.lieu,c.id,m.id,f.id from importdevis i
                                join maison m on m.type_maison=i.type_maison
                                join finition f on f.finition=i.finition
                                join client c on c.contact=i.client
                                    group by i.ref_devis,i.date_devis,i.date_debut,i.lieu,c.id,m.id,f.id');

            DB::insert('insert into travaux_devis (code_travaux,ref_devis,pu,qte) select i.code_travaux,id.ref_devis,i.pu,i.qte from importdevis id
                                join import i on id.type_maison=i.type_maison
                                    group by i.code_travaux,id.ref_devis,i.pu,i.qte');

            DB::insert('insert into finition_devis (id_finition,ref_devis,taux_finition) select f.id,i.ref_devis,i.taux_finition  from importdevis i
                                join finition f on f.finition=i.finition
                                    group by f.id,i.ref_devis,i.taux_finition');
        }catch(\Exception $e){
            $erreur[]=$e->getMessage();
        }

        return view('import.import',[
            'validation' => $validation,
            'erreur' => $erreur,
        ]);
    }

    public function goToImport(){
        return view('import.import');
    }

    public function goToPaiement(){
        return view('import.paiement');
    }

    public function paiement(Request $request){
        $r = Excel::toArray(new Import(),$request->file('file'))[0];
        $rules = [
            'ref_devis' => 'required',
            'ref_paiement' =>'required',
            'date_paiement' => 'required',
            'montant' =>'required',
        ];

        $customMessages = [
            'ref_devis.required' => 'ref_devis est requis',
            'ref_paiement.required' =>'ref_paiement est requis',
            'date_paiement.required' => 'date_paiement est requis',
            'montant.required' =>'montant est requis',
        ];

        $erreur = [];
        $validation=[];
        $i=1;

        foreach ($r as $row){
            $validator = Validator::make($row, $rules, $customMessages);
            if ($validator->fails()) {
                $errors = $validator->errors()->all();
                foreach ($errors as $error) {
                    $validation[]=$error .' (Ligne '.$i.')';
                }
            } else {
                try{
                    DB::table('paiement')->insert([
                        'ref_devis' => $row['ref_devis'],
                        'ref_paiement' =>$row['ref_paiement'],
                        'date_paiement' => $row['date_paiement'],
                        'montant' =>$row['montant'],
                    ]);
                }catch(\Exception $e){
                    if($e->getCode()==23505){
                        $erreur[]='Ref_paiment deja existente a la ligne'.' '.$i.' : '.$row['ref_devis'].','.$row['ref_paiement'].','.$row['date_paiement'].','.$row['montant'];
                    }elseif ($e->getCode()==23503){
                        $erreur[]='Devis inexistente a la ligne'.' '.$i.' : '.$row['ref_devis'].','.$row['ref_paiement'].','.$row['date_paiement'].','.$row['montant'];
                    }else{
                        $erreur[]=$e->getMessage().' => ligne'.' '.$i.' : '.$row['ref_devis'].','.$row['ref_paiement'].','.$row['date_paiement'].','.$row['montant'];
                    }

                }
            }
            $i++;
        }

        /*try{;
            DB::insert('insert into paiement (ref_devis,ref_paiement,date_paiement,montant) select ref_devis,ref_paiement,date_paiement,montant from importpaiement');
        }catch(\Exception $e){
            $erreur[]=$e->getMessage();
        }*/

        if(count($erreur)==0 && count($validation)==0){
            return view('import.paiement')->with('success', 'Importation reussi sans erreur');
        }else{
            return view('import.paiement',[
                'validation' => $validation,
                'erreur' => $erreur,
            ]);
        }
    }

}
