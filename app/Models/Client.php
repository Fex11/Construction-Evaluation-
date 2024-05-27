<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Client extends Model
{
    use HasFactory;

    protected $table = 'client';

    public function insertContact($contact){
        return DB::table('client')->insertGetId([
            'contact' => $contact,
        ],'id');
    }
}
