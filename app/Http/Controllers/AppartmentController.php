<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Models\Appartement;
use DB;

class AppartmentController extends Controller
{
    //appartements dispo
    public function appdispo()//appart dispo
    {
        $aujourd = date('Y-m-d');//prendre la date de today

        //prendre a chaque fois les ids des apparts
        $qu = Appartement::all();
        foreach ($qu as $qu) 
        {
            // //Selectionner les id des appartement qui sont oqp dans la table reservation
            $busyapp = DB::table('reservations')->where('dat_dep', '>=', $aujourd)->where('dat_arriv', '<=', $aujourd)->where('id_appart', $qu->id_appart)->get();
        
            if(count($busyapp) == 0) //cad si l'appart est libre
            {
                //recupérer l'appartement en question
                $appartment = Appartement::whereId_appart($qu->id_appart)->get();
                //prendre le batiments de l'appart
                $nbr_places = 0;
                foreach ($appartment as $appartment) 
                {
                    $batiments = DB::table('batiments')->where('id_bat', $appartment->id_bat)->get();
                    foreach ($batiments as $batiments)
                    {
                        $libele = $batiments->lib_bat;
                    }
                    //voir combien de lit tel appartement a
                    $lits = DB::table('lits')->where('id_appart', $qu->id_appart)->get();
                    //nombre de lits
                    $nombre_de_lits = count($lits);
                    //comptons le nombre de places
                    foreach ($lits as $lits) 
                    {
                        $nbr_places = $nbr_places + $lits->nb_place;
                    }
                    echo "<tr>
                <td><button type='button' class='collapsible btn btn-circle bg-gradient-success'><font color='white'><i class='fa fa-angle-down'></i></font></button>
                <div class='content' style='display:none;'>
                    <p>Numéro de l'apartement:  ".$appartment->id_appart."<br>Batiment:  ".$libele."<br>Tarif journalier(FCFA):  ".number_format($appartment->tar_jour, 0, ',', ' ')."<br>Tarif mensuel(FCFA):  ".number_format($appartment->tar_mois, 0, ',', ' ')."<br>Nombre de lits:  ".$nombre_de_lits."<br>Nombre de places:  ". $nbr_places."</p>
                </div></td><td>".$appartment->lib_appart."</td></tr>"; 
                }
                

            }

        }
      
      
    }

}
