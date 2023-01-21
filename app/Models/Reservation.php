<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = ['id_reserv', 'id_appart', 'id_clt', 'dat_reserv', 'nb_adlt', 'nb_enf', 'mont_reserv', 'dat_arriv', 'dat_dep', 'statut', 'nb_jr', 'hr_reserv', 'objet', 'rem'];
}
