<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appartement extends Model
{
   use HasFactory;
   public $timestamps = false;

   //attribut mass assignable
   protected $fillable = [
        'id_appart',
        'lib_appart',
        'tar_jour',
        'tar_mois',
    ];
}
