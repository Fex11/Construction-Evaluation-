<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Maison extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'maison';

    public function getMaisonById($id){
        return DB::table('v_maison')
                ->where('id_maison', $id)
                ->first();
    }

    public function getAllMaison(){
        return DB::table('v_maison')
                ->get();
    }

    public function getAllTravaux(){
        return DB::table('travaux')
            ->get();
    }

    public function getTravauxByIdMaison($id){
        return DB::table('v_travaux_maison')
            ->where('id_maison', $id)
            ->orderBy('code')
            ->get();
    }

}
