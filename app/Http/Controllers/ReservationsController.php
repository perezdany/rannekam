<?php

namespace App\Http\Controllers;


use DB;
use Illuminate\Http\Request;
use \App\Models\Client;
use \App\Models\Appartement;
use \App\Models\Reservation;
use \App\Models\Facture;

class ReservationsController extends Controller
{
    //Classe qui gère les réservations, ajout modif etc...

    public function LoadForm()
    {
        return view('register');
    }

    public function addreserv()
    {
        session_start();
        $nom =  htmlspecialchars(request('nom_client'));//securiser nos variables pour evietr des injection de code
        $pnom = htmlspecialchars(request('prenom_client'));
        $tel = htmlspecialchars((request('tel')));
        $mail = htmlspecialchars((request('email')));
        $title = htmlspecialchars((request('title')));
        $objet = "Location d'appartement(s) meublé(s)";
        $appart = intval(request('appart'));
        $adultes = intval(request('adlt'));
        $enfants = intval(request('eft'));
        $jour = intval(request('jr'));
        $add = htmlspecialchars((request('add')));
        $arrivdate = htmlspecialchars((request('ar')));
        $timestamp = strtotime($arrivdate); //recupération du timestamp de la date donnée
        $newdate1 = date("Y-m-d", $timestamp);//conversion2(en anglais)
        $remise = intval(request('remise'));
        $montant = intval(request('montant'));//on ne calcule plus le montant en arriere plan maintenant on entre le montant et la remise
        //date de la reservation
        $timestamp1 = strtotime(request('dat_reserv'));
        $dat_reserv = date("Y-m-d", $timestamp1);

        //calcule de la date de depart
        $depart = (new calculator())->arrivdate($jour,$newdate1);

        //vérifactions de la taille de certaines valeures entrées afin de débuter le traitement
        if(strlen($nom) <= 60)
        {
            if(strlen($pnom) <= 60)
            {
                //verifier si le client est deja dans la base
                
                $l = Client::whereNom_cltAndPnom_clt($nom, $pnom)->get();

                if(count($l) == 0)//si il n'est pas dans la base
                {

                    //mettre le client dans la table
                    $client = new Client(['title' => $title, 'nom_clt' => $nom, 'pnom_clt' => $pnom, 'tel' => $tel, 'mail' => $mail, 'address' => $add]);
                    $client->save();

                  
                    //prix unitaire
                    $_SESSION['p1'] = (new Calculator())->price($jour, $appart);

                    //recuperer le nom de l'appart
                    $de = Appartement::whereId_appart($appart)->get();
                    foreach ($de as $de) 
                    {
                        $_SESSION['desig'] = $de->lib_appart;
                    }

                    //requete pour la reservation
                    $id="RESER".date("Ymdhisa");
                    $date=date("Y-m-d");
                    $stat=0;
                    $hr = date("H:i:s");

                    //recuperer le matricule du client
                    $client = Client::whereNom_cltAndPnom_clt($nom, $pnom)->get();
                    foreach ($client as $client) 
                    {
                        $id_client = $client->id_clt;
                    }
                    

                    //pour eviter que lorsqu'on renvoie le formulaire avec ces meme données il yait traitement a nouveau on fera :
                    $queryverif = Reservation::whereDat_arrivAndNb_jrAndNb_adltAndNb_enfAndId_appartAndId_clt($newdate1, $jour, $adultes, $enfants, $appart, $id_client)->get();
                    
                    if(count($queryverif)== 0)
                    {
                        //on peut alors faire l'insertion
                        $reservation  = new Reservation(['id_reserv' => $id, 'id_appart' => $appart, 'id_clt' => $id_client, 'dat_reserv' => $dat_reserv, 'nb_adlt' => $adultes, 'nb_enf' => $enfants, 'mont_reserv' => $montant, 'dat_arriv' => $newdate1, 'dat_dep' => $depart, 'statut' => $stat, 'nb_jr' => $jour, 'hr_reserv' => $hr, 'objet' => $objet, 'rem' => $remise]);
                        $reservation->save();
                       
                        //formatage des dates pour les affichages
                        $aftamp1 = strtotime($arrivdate); //recupération du timestamp de la date donnée
                        $afd1 = date("d/m/Y", $aftamp1);
                        $aftamp2 = strtotime($depart); //recupération du timestamp de la date donnée
                        $afd2 = date("d/m/Y", $aftamp2);

                        //inserton de la facture proformat
                        $idfact = "F".date("Ymdhisa");//id de facture
                        $insert_facture = new Facture (['id_fact' => $idfact, 'mont_sur_fact' => $montant, 'id_reserv' => $id, 'dat_emi' => $date, 'rest_a_pay' => $montant, 'hr_emi' => $hr, 's' => 0, 'id_pay' => 4]);
                        $insert_facture->save();
                       
                        
                        //creation des sessions
                        $_SESSION['appart'] = $appart;
                        $_SESSION['nom'] = $nom;
                        $_SESSION['pnom'] = $pnom;
                        $timestampd = strtotime($date);//recuperation du timestanp de la date donnée
                        $_SESSION['date'] = date("d/m/Y", $timestampd);
                        $_SESSION['newdate1'] = $newdate1;
                        $_SESSION['montant'] = $montant;
                        
                        $_SESSION['reserv'] = $id;
                        $_SESSION['j'] = $jour;
                        $_SESSION['fact'] = $idfact;
                        $_SESSION['remise'] = $remise;

                        //MESSAGE DE RESULTAT
                        $message = 'Réservation effectuée';

                    }
                    else
                    {
                        $message = 'Réservation déja enregistrée';
                    }
                
                  //rediriger sur la facture a meme temps
                }

                if(count($l) == 1)//il est dans la base
                {
                  
                    //prix unitaire
                    $_SESSION['p1'] = (new Calculator())->price($jour, $appart);

                    
                    //recuperer le matricule du client
                    $client = Client::whereNom_cltAndPnom_clt($nom, $pnom)->get();
                    foreach ($client as $client) 
                    {
                        $id_client = $client->id_clt;
                    }
                    //dd($client->id_clt);
                                        
                    //recuperer le nom de l'appart
                    $de = Appartement::whereId_appart($appart)->get();
                    foreach ($de as $de) 
                    {
                        $_SESSION['desig'] = $de->lib_appart;
                    }

                    $id="RESER".date("Ymdhisa");
                    $text="indefini";
                    $date=date("Y-m-d");
                    $hr = date("H:i:s");
                    $stat=0;

                    //reservation
                    $queryverif = Reservation::whereDat_arrivAndNb_jrAndNb_adltAndNb_enfAndId_appartAndId_clt($newdate1, $jour, $adultes, $enfants, $appart, $id_client)->get();
                    if(count($queryverif) == 0)
                    {
                        //on peut alors faire l'insertion
                        $reservation  = new Reservation(['id_reserv' => $id , 'id_appart' => $appart, 'id_clt' => $id_client, 'dat_reserv' => $dat_reserv, 'nb_adlt' => $adultes, 'nb_enf' => $enfants, 'mont_reserv' => $montant, 'dat_arriv' => $newdate1, 'dat_dep' => $depart, 'statut' => $stat, 'nb_jr' => $jour, 'hr_reserv' => $hr, 'objet' => $objet, 'rem' => $remise]);
                        $reservation->save();

                        //formatage des dates pour les affichages
                        $aftamp1 = strtotime($arrivdate); //recupération du timestamp de la date donnée
                        $afd1 = date("d/m/Y", $aftamp1);
                        $aftamp2 = strtotime($depart); //recupération du timestamp de la date donnée
                        $afd2 = date("d/m/Y", $aftamp2);

                        //inserton de la facture proformat
                        $idfact = "F".date("Ymdhisa");//id de facture
                        $insert_facture = new Facture (['id_fact' => $idfact, 'mont_sur_fact' => $montant, 'id_reserv' => $id, 'dat_emi' => $date, 'rest_a_pay' => $montant, 'hr_emi' => $hr, 's' => 0, 'id_pay' => 4]);
                        $insert_facture->save();

                        //creation des sessions
                        $_SESSION['appart'] = $appart;
                        $_SESSION['nom'] = $nom;
                        $_SESSION['pnom'] = $pnom;
                        $timestampd = strtotime($date);//recuperation du timestanp de la date donnée
                        $_SESSION['date'] = date("d/m/Y", $timestampd);
                        $_SESSION['newdate1'] = $newdate1;
                        $_SESSION['montant'] = $montant;
                       
                        $_SESSION['reserv'] = $id;
                        $_SESSION['j'] = $jour;
                        $_SESSION['fact'] = $idfact;
                        $_SESSION['remise'] = $remise;

                        //MESSAGE DE RESULTAT
                      $message = 'Réservation effectuée';
                    
                  }
                  else
                  {
                    $message = 'Réservation déja enregistrée';
                  }
                
                  //rediriger sur la facture a meme temps
                }
            }
            else
            {
              $message="les prénoms ne doivent pas depasser 60 caractères";
            }
        }
        else
        {
            $message="votre nom ne doit pas depasser 60 caractères";
        } 

        //on revient sur le formulaire avec les messages de traitement recceuillis
        return view('register', compact('message'));
    }


    public function NonPayedReservation() //reservation en cours
    {
       
        $query = DB::select('SELECT SQL_NO_CACHE r.id_reserv, r.dat_reserv, r.hr_reserv, r.nb_jr, c.nom_clt, c.pnom_clt, r.statut, a.lib_appart, r.mont_reserv, r.dat_arriv, r.dat_dep, r.objet, c.id_clt, c.title, r.rem FROM reservations r, clients c, appartements a WHERE r.id_clt=c.id_clt AND r.id_appart=a.id_appart AND r.statut= 0 ');
        //table('reservations')->where('dat_dep', '>', $aujourd)->where('statut', 0);
        return $query;
    }

    public function GetAReservation()
    {
        $the_reservation = DB::select('SELECT SQL_NO_CACHE r.id_reserv, r.id_appart, r.nb_adlt, r.nb_enf, r.dat_arriv, r.nb_jr, c.nom_clt, c.pnom_clt, a.lib_appart, r.mont_reserv, r.rem, r.statut, f.id_fact FROM reservations r, appartements a, clients c, factures f WHERE r.id_clt=c.id_clt AND r.id_appart=a.id_appart AND f.id_reserv = r.id_reserv  AND f.s = 0 AND r.id_clt="'.request('id_client').'" AND r.id_reserv="'.request('id').'" ');
        foreach ($the_reservation as $the_reservation) 
        {
            $id_appart = $the_reservation->id_appart;
            $nb_adultes = $the_reservation->nb_adlt;
            $nb_enfants = $the_reservation->nb_enf;
            $date_arrivee = $the_reservation->dat_arriv;
            $jours = $the_reservation->nb_jr;
            $nom_client = $the_reservation->nom_clt;
            $pnom_client = $the_reservation->pnom_clt;
            $designation_appart = $the_reservation->lib_appart;
            $montant_reservation = $the_reservation->mont_reserv;
            $remise = $the_reservation->rem;
            $statut_reservation = $the_reservation->statut;
            $id_facture = $the_reservation->id_fact;
            $id_reservation = $the_reservation->id_reserv;
        }
        //on redirige sur le formulaire de modification avec les valeurs recupérées
        return view('edit_reservation', compact('id_appart', 'nb_adultes', 'nb_enfants', 'date_arrivee', 'jours', 'nom_client', 'pnom_client', 'designation_appart', 'montant_reservation','remise', 'statut_reservation', 'id_facture', 'id_reservation'));

    }

    public function UpdateReservation()
    {
        //traitement pour la modification
        //reservation

        //requete pour la modification
        $appart = intval(request('ap'));
        $adultes = intval(request('adlt'));
        $enf = intval(request('enf'));
        $jr = intval(request('jr'));
        $montant = intval(request('montant'));
        $arrivdate = htmlspecialchars(request('ar'));
        $timestamp = strtotime($arrivdate); //recupération du timestamp de la date donnée
        $newdate1 = date("Y-m-d", $timestamp);
        $remise = intval(request('remise'));
        //creation des sessions
        /*$_SESSION['appart'] = $appart;
        $_SESSION['newdate1'] = $newdate1;
        $_SESSION['j'] = $jr;
        $_SESSION['remise'] = $remise;*/
        $hr = date("H:i:s");

        //changer le montant de la reservation aux cas ou ya changment

        //calcul des nouvelles dates de depart en ajoutant juste les valeur des jour
        $depart = (new Calculator())->leaving_date($jr, $newdate1);
      
        //generer le nouveau montant : faire une nouvelle facture avec le nouveau reste a payer
        $ancien_mont = Reservation::whereId_reserv(request('id_reserv'))->get();
        foreach ($ancien_mont as $ancien_mont)
        {
          $ancien_mont = $ancien_mont->mont_reserv;
        }
        //dd($ancien_mont);

        
        if($montant != $ancien_mont)
        {
            //Selctionner le montant qui reste a payer de la derniere facture payée
            $montr = DB::select("SELECT SQL_NO_CACHE f.rest_a_pay FROM factures f, reservations r  WHERE f.id_reserv=r.id_reserv AND f.s = 1  AND r.id_reserv='".request('id_reserv')."' ORDER BY f.rest_a_pay ASC LIMIT 1");
            $compte = count($montr);
            //dd($compte);
            if($compte == 0)//y a pas de facture payée
            {
                //faire une facture (proformat)
              
                //supprimer les anciennes facture qui pourraient nous tromper...
                $fa = DB::select('SELECT f.id_fact FROM factures f , reservations r WHERE f.id_reserv=r.id_reserv AND f.s=0  AND r.id_reserv="'.request('id_reserv').'" ');
                foreach($fa as $fa) 
                {
                    $fsup = DB::table('factures')->whereId_fact($fa->id_fact)->delete();
                }
                 
                 //Identifiant de la facture
                $hr = date("H:i:s");
                $idfact = "F".date("Ymdhisa");//id de facture
                $date=date("Y-m-d");
                //$_SESSION['fact'] = $idfact;//recuperer pour envoyer ca dans la facture

                //insertion de la facture; on considère qu'elle est proformat
                $insert_facture = new Facture (['id_fact' => $idfact, 'mont_sur_fact' => $montant, 'id_reserv' => request('id_reserv'), 'dat_emi' => $date, 'rest_a_pay' => $montant, 'hr_emi' => $hr, 's' => 0, 'id_pay' => 4]);
                $insert_facture->save();
                
                //modifier la réservation
                $update = DB::table('reservations')->whereId_reserv(request('id_reserv'))->update(['id_appart'=> $appart, 'nb_adlt' => $adultes, 'nb_enf' => $enf, 'mont_reserv' => $montant, 'dat_arriv' => $newdate1, 'dat_dep' => $depart, 'nb_jr' => $jr, 'rem' => $remise]);

                $message = "Modification effectuée";
                  
                //redirection vers la facture
                // echo'<font color="green" size="25px"><font/><br><a href="../facture/factmod.php">Imprimer la facture<a/>';
                //$_SESSION['rest']= $montant;//puisqu'il n'a pas encore payé le montant restant est identique au montant de la réservation
             
            }
            else //y a facture payée
            {
                //recuper le montant de toutes les anciennes factures reglée de la reservation
                $anfact = DB::select("SELECT SQL_NO_CACHE f.mont_sur_fact FROM facture f, reservation r WHERE f.id_reserv=r.id_reserv and r.id_reserv='".request('id_reserv')."' AND f.s ='1' ");
                $som = 0;
                foreach ($anfact as $anfact) 
                {
                    $som += $anfact->mont_sur_fact;
                }

                //faire la soustraction du nouveau montant et de $som
                $rest = $montant - $som;
                //$_SESSION['rest'] = $rest;

                //modifier la réservation si après la modification la réservation est soldée ($rest == 0)
                if ($rest == 0)
                {
                    //suppression de la facture proformat ancienne Supprimer maintenant la facture dont on a plus besoin
                  
                    $fa = DB::select('SELECT f.id_fact FROM facture f , reservation r WHERE f.id_reserv=r.id_reserv AND f.s=0  AND r.id_reserv ="'.request('id_reserv').'"');
                    foreach($fa as $fa)
                    {
                     
                        $fsup = DB::table('factures')->whereId_fact($fa->id_fact)->delete();
                        
                    }

                    //Identifiant de la facture
                    $hr = date("H:i:s");
                    $idfact = "F".date("Ymdhisa");//id de facture
                    $date=date("Y-m-d");
                    //$_SESSION['fact'] = $idfact;//recuperer pour envoyer ca dans la facture

                    //nouvelle facture (proformat)

                    $insert_facture = new Facture (['id_fact' => $idfact, 'mont_sur_fact' => $montant, 'id_reserv' => request('id_reserv'), 'dat_emi' => $date, 'rest_a_pay' => $montant, 'hr_emi' => $hr, 's' => 0, 'id_pay' => 4]);
                    $insert_facture->save();

                   
                    //modifier le statut de la réservation
                    //$update_statut = (new Reservation())->update_statut($_SESSION['idr']);

                    //modifier la réservation
                    $update = DB::table('reservations')->whereId_reserv(request('id_reserv'))->update(['id_appart'=> $appart, 'nb_adlt' => $adultes, 'nb_enf'=> $enf, 'mont_reserv' => $montant, 'dat_arriv' => $newdate1, 'dat_dep' => $depart, 'nb_jr' => $jr, 'rem' => $remise, 'satut' => 1]);

                    $message = "Modification effectuée";

                    //suppression ds anciennes factures de règlementet faire une facture de règlement qui regroupe tous les payements en fait
                  
                    $fa = DB::select('SELECT f.id_fact FROM facture f , reservation r WHERE f.id_reserv=r.id_reserv AND f.s=1  AND r.id_reserv ="'.request('id_reserv').'"');
                    foreach($fa as $fa)
                    {
                     
                        $fsup = DB::table('factures')->whereId_fact($fa->id_fact)->delete();
                        
                    }
                    //Identifiant de la facture
                    $hr = date("H:i:s");
                    $idfact = "F".date("Ymdhisa");//id de facture
                    //$_SESSION['fact'] = $idfact;//recuperer pour envoyer ca dans la facture

                    //insertion de la facture; on considère qu'elle est payée cette fois avec le rest 0
                    $insert_facture = new Facture (['id_fact' => $idfact, 'mont_sur_fact' => $montant, 'id_reserv' => request('id_reserv'), 'dat_emi' => $date, 'rest_a_pay' => 0, 'hr_emi' => $hr, 's' => 0, 'id_pay' => 4]);
                    $insert_facture->save();


                }
                else//la réservation n'est pas soldée
                {
                    //Identifiant de la facture
                    $hr = date("H:i:s");
                    $idfact = "F".date("Ymdhisa");//id de facture
                    $_SESSION['fact'] = $idfact;//recuperer pour envoyer ca dans la facture
                    
                    //modifier la réservation
                    $update = (new Reservation())->update_reserv($appart, $adultes, $enf, $montant, $newdate1, $depart, $jr, $remise, $_SESSION['idr']);
                    $update = DB::table('reservations')->whereId_reserv(request('id_reserv'))->update(['id_appart'=> $appart, 'nb_adlt' => $adultes, 'nb_enf' => $enf, 'mont_reserv' => $mont, 'dat_arriv' => $newdate1, 'dat_dep' => $depart, 'nb_jr' => $jr, 'rem' => $remise, 'satut' => 1]);

                    $message = "Modification effectuée";

                    //suppression de la facture proformat ancienne Supprimer maintenant la facture dont on a plus besoin
                  
                    $fa = DB::select('SELECT f.id_fact FROM facture f , reservation r WHERE f.id_reserv=r.id_reserv AND f.s=0  AND r.id_reserv ="'.request('id_reserv').'"');
                    foreach($fa as $fa)
                    {
                     
                       $fsup = DB::table('factures')->whereId_fact($fa->id_fact)->delete();
                        
                    }
                    
                    //insertion de la facture (une facture proformat) d'abord
                    $ $insert_facture = new Facture (['id_fact' => $idfact, 'mont_sur_fact' => $montant, 'id_reserv' => request('id_reserv'), 'dat_emi' => $date, 'rest_a_pay' => $montant, 'hr_emi' => $hr, 's' => 0, 'id_pay' => 4]);
                    $insert_facture->save();

                    //supprimer les ancienes facture de règlement et mettre une nouvelle qui resume tout
                    
                    $fa = DB::select('SELECT f.id_fact FROM facture f , reservation r WHERE f.id_reserv=r.id_reserv AND f.s=1  AND r.id_reserv ="'.request('id_reserv').'"');
                    foreach($fa as $fa)
                    {
                     
                        $fsup = DB::table('factures')->whereId_fact($fa->id_fact)->delete();
                        
                    }
                    
                }
            }
            //redirection vers la facture
            
        }
        else
        {
            $message = "Aucune modification particulière n'a été faite sur cette réservation, ou le montant de la réservation n'a pas changé";
        }

        return view('edit_reservation', compact('message'));
              
    }

    public function DeleteAReservation()
    {
        //suppression d'une reservation
        $delete = DB::table('reservations')->whereId_reserv(request('id'))->delete();
        return view('reservations');
        //dump($delete);
    }

    public function BuyReservation()
    {

      
        $arg = intval(request('m'));
        //$_SESSION['pay'] = $arg;
        $pay_mode = request('pay_mod');
        //$_SESSION['pay_mod'] = $pay_mod;
        $idfact = "F".rand(0,10000);//id de facture
        //$_SESSION['fact'] = $idfact;
        $date = date('Y-m-d');
        //$_SESSION['date'] = $da;
        $hr = date("H:i:s");


        //Selectionner le montant qui reste a payer de la derniere facture reglée
        $montr = DB::select("SELECT SQL_NO_CACHE f.rest_a_pay FROM factures f, reservations r WHERE f.id_reserv=r.id_reserv AND r.id_reserv='".request('id_reservation')."' AND f.s='1' ORDER BY f.hr_emi DESC, f.dat_emi DESC LIMIT 1");
        $compte = count($montr);

        if($compte == 0)//ca veut dire c'est sa premiere facture qu'il doit regler
        {

            //c'est que là on va prendre le montant qui est dans la reservation vu que le client fait son premier payement
            $montreserv = Reservation::whereId_reserv(request('id_reservation'))->get();
            foreach ($montreserv as $montreserv)
            {
                $rest = intval($montreserv->mont_reserv) - $arg;//faire la soustraction
            }

            //$_SESSION['rest'] = $rest;
            if($rest < 0)
            {
                $messageissue = " Montant trop élevé! Veuillez entrer un montant plus petit que le précédent";
            }
            else
            {
                //insertion (facture payée)
                $insert_facture = new Facture (['id_fact' => $idfact, 'mont_sur_fact' => $arg, 'id_reserv' => request('id_reservation'), 'dat_emi' => $date, 'rest_a_pay' => $rest, 'hr_emi' => $hr, 's' => 1, 'id_pay' => $pay_mode]);
                $insert_facture->save();
                
                //mise a jour de la table reservation, enfin de statut

                if($rest == 0)
                {
                    $update = DB::table('reservations')->whereId_reserv(request('id_reservation'))->update(['statut'=> 1]);   
                    //var_dump($update);
                }
                else
                {

                }
                //les elements pour la facture

                //les tarifs et le nom de l'appart
                /*
                $choisi = DB::select("SELECT SQL_NO_CACHE a.tar_jour, a.tar_mois, a.lib_appart FROM appartement a, reservation r WHERE a.id_appart=r.id_appart AND r.id_reserv='".request('id_reservation')."' ");
                $tarifs = $choisi->fetch();
                $_SESSION['desig'] = $tarifs[2];
                $_SESSION['p1'] = $tarifs[0];

                //nom et prenoms du client
                $cl = $bdd->query("SELECT SQL_NO_CACHE nom_clt, pnom_clt FROM client WHERE id_clt='".$_GET['clt']."' ");
                $clt = $cl->fetch();
                $_SESSION['nom'] = $clt[0];
                $_SESSION['pnom'] = $clt[1];

                //nbre de jour  dans reservation
                $nb = $bdd->query("SELECT SQL_NO_CACHE mont_reserv, nb_jr  FROM reservation WHERE id_reserv='".$_GET['id']."'");
                $o = $nb->fetch();
                $_SESSION['montant'] = $o[0];
                $_SESSION['j'] = $o[1];
                $_SESSION['reserv'] = $_GET['id'];*/

                //echo'<font color="green">; <a href="../facture/facture.php" >Imprimer facture</a></font>';
                $message= "Action effectuée";

            }
        }
        else//y a une facture
        {
          
            //voir si la facture existe a l'instant t
            $verif = Facture::whereId_fact($idfact)->get();
            $ok = count($verif);

            if($ok == 0)//la facture n'existe pas
            {
                //voir si le montant qu'il a entré ne depasse PAS le rest a payer de la dernière facture
                $ver = DB::select("SELECT SQL_NO_CACHE f.rest_a_pay FROM factures f, reservations r WHERE f.id_reserv=r.id_reserv AND r.id_reserv='".request('id_reservation')."' AND f.s ='1' AND f.rest_a_pay <'".$arg."' ORDER BY f.hr_emi DESC, f.dat_emi DESC LIMIT 1");

                $ct = count($ver);
                if($ct == 0)// ca depasse pas
                {
                    //prendre le montant rest a payer de la dernière facture
                    $last = DB::select("SELECT SQL_NO_CACHE f.rest_a_pay FROM factures f, reservations r  WHERE f.id_reserv=r.id_reserv AND r.id_reserv='".request('id_reservation')."' AND f.s ='1' ORDER BY f.hr_emi DESC LIMIT 1");
                    foreach($last as $last)
                    {
                        $rest = intval($last->rest_a_pay) - $arg;//faire la soustraction
                    }
                   
                    //$_SESSION['rest'] = $rest;

                    //il faut verifier si le reste devient zéro alors on doit solder la réservation
                    if($rest == 0)
                    {
                        $update = DB::table('reservations')->whereId_reserv(request('id_reservation'))->update(['statut'=> 1]);   
                    }

                    //insertion de la facture
                    $insert_facture = new Facture (['id_fact' => $idfact, 'mont_sur_fact' => $arg, 'id_reserv' => request('id_reservation'), 'dat_emi' => $date, 'rest_a_pay' => $rest, 'hr_emi' => $hr, 's' => 1, 'id_pay' => $pay_mode]);
                    $insert_facture->save();


                    //les elements pour la facture
                    //les tarifs et le nom de l'appart
                    /*$choisi = $bdd->query("SELECT SQL_NO_CACHE a.tar_jour, a.lib_appart FROM appartement a, reservation r WHERE a.id_appart=r.id_appart AND r.id_reserv='".$_GET['id']."' ");
                    $tarifs = $choisi->fetch();
                    $_SESSION['desig'] = $tarifs[1];
                    $_SESSION['p1'] = $tarifs[0];
                    //nom et prenoms du client
                    $cl = $bdd->query("SELECT SQL_NO_CACHE nom_clt, pnom_clt FROM client WHERE id_clt='".$_GET['clt']."' ");
                    $clt = $cl->fetch();
                    $_SESSION['nom'] = $clt[0];
                    $_SESSION['pnom'] = $clt[1];
                    //nbre de jour et de jours dans reservation
                    $nb = $bdd->query("SELECT SQL_NO_CACHE mont_reserv, nb_jr  FROM reservation WHERE id_reserv='".$_GET['id']."'");
                    $o = $nb->fetch();
                    $_SESSION['montant'] = $o[0];
                    $_SESSION['j'] = $o[1];

                    $_SESSION['reserv'] = $_GET['id'];
                    $_SESSION['fact'] = $idfact;*/

                    $message = "Action effectuée";
                    //echo'<font color="green"> <a href="../facture/facture.php" >Imprimer facture</a></font>';
                     
                }
                else//ca depasse
                {
                    $messageissue = "Montant trop élevé! Veuillez entrer un montant plus petit que le précédent";
                }

                
            }
            else //elle existe
            {
                $messageissue = "Facture existante";
            }
          
        }
        //var_dump($messageissue);
        
        if(isset($message))
            return view('buy_reservation', compact('message'));
        else
            return view('buy_reservation', compact('messageissue'));

    }
}
