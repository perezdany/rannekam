<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class InvoicesController extends Controller
{
    //
    public function all_fact()
    {
        $query = DB::select("SELECT SQL_NO_CACHE f.id_fact, f.mont_sur_fact, r.id_reserv, f.dat_emi, f.rest_a_pay, f.hr_emi, f.s, a.tar_jour, a.lib_appart, r.mont_reserv, r.nb_jr, a.tar_mois, c.nom_clt, c.pnom_clt, r.dat_reserv, r.statut FROM factures f, reservations r, clients c, appartements a WHERE c.id_clt=r.id_clt AND r.id_reserv=f.id_reserv AND r.id_appart=a.id_appart AND r.statut=1 AND f.rest_a_pay=0  ORDER BY dat_emi DESC, hr_emi DESC");
        return $query;

    }
}
