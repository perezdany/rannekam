<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facture extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable =['id_fact', 'mont_sur_fact', 'id_reserv', 'dat_emi', 'rest_a_pay', 'hr_emi', 's', 'id_pay'];
}
