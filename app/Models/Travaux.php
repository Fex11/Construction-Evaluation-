<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Travaux extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'travaux';

}
