<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class Devis extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'devis';

    protected $fillable = [
        'date_devis',
        'date_debut',
        'lieu' ,
        'id_client' ,
        'id_maison' ,
        'id_finition',
        'ref_devis',
    ];

    public function createDevis($idClient,$idMaison,$dateDebut,$idFinition,$ref,$lieu){
        $maison=new Maison();
        $maisonDevis=$maison->getMaisonById($idMaison);
        $finition=$this->getFinitionById($idFinition);

        $devis =Devis::create([
            'date_devis' => Carbon::now()->toDateString(),
            'date_debut' => $dateDebut,
            'lieu' => $lieu,
            'id_client' => $idClient,
            'id_maison' => $idMaison,
            'id_finition' =>$idFinition,
            'ref_devis' => $ref,
        ]);
        return $devis;
    }

    public function getAllFinition(){
        return DB::table('finition')
                ->get();
    }

    public function getPaiementByRef($ref){
        return DB::table('paiement')
            ->where('ref_devis', $ref)
            ->get();
    }

    public function getFinitionById($id){
        return DB::table('finition')
                ->where('id', $id)
                ->first();
    }

    public function getDevisClient(){
        $client = Session::get('client');
        return DB::table('v_devis')
            ->where('id_client', $client)
            ->get();
    }

    public function getAllDevis(){
        return DB::table('v_devis')
            ->get();
    }

    public function getDonneeYear($year){
        $result = DB::select("WITH all_months AS (SELECT generate_series(date_trunc('year', '".$year."-01-01'::date), date_trunc('year', '".$year."-01-01'::date) + INTERVAL '1 year - 1 day', interval '1 month') AS month)
                            SELECT to_char(all_months.month, 'YYYY-MM') AS month,COALESCE(SUM(v_devis.prix_total), 0) AS prix FROM all_months
                                LEFT JOIN v_devis ON DATE_TRUNC('month', v_devis.date_devis) = all_months.month
                                    GROUP BY month ORDER BY month");
        $donnees=[];
        foreach ($result as $r){
            $donnees[]=$r->prix;
        }
        return $donnees;
    }

    public function getDevisByRef($ref){
        return DB::table('v_devis')
            ->where('ref_devis', $ref)
            ->first();
    }

    public function getTravauxByRef($ref){
        return DB::table('v_travaux_devis')
            ->where('ref_devis', $ref)
            ->get();
    }

    public function getFinitionByref($ref){
        return DB::table('v_finition_devis')
            ->where('ref_devis', $ref)
            ->get();
    }

}
