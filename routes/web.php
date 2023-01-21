<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*Route::get('/', function () {
    return view('welcome');
});*/


/*Route::get('/', function () {
    //dd(Admin::all());

});*/

//routes de la page d'acceuil de connexion 
Route::get('/', 'LoginController@LoginForm');//cela appelle la page avec le controller loginform et la méthode a appliquer
Route::get('/login', 'LoginController@LoginForm');
Route::post('/login', 'LoginController@LoginTreatment');

//route enregistrement de réserbvation
Route::get('/register', 'ReservationsController@LoadForm');
Route::post('/register', 'ReservationsController@addreserv');

//route page d'acceuil de l'utilisateur (welcome)
Route::get('/welcome', function(){
    return view('welcome');
});

//route pour gestion des clients
Route::get('/customers', function(){
    return view('customers');
});
Route::get('/customers', 'CustomerController@LoadPage');
Route::post('/customers', 'CustomerController@DeleteACustomer');


//route pour la gestions des réservations
Route::get('/reservations', function(){
    return view('reservations');
});
Route::post('/reservations', 'ReservationsController@DeleteAReservation');

//route pour la gestion des factures
Route::get('/invoices', function(){
    return view('invoices');
});

//route pour modifier un client
Route::get('/edit_customer', function(){
    return view('edit_customer');
});

Route::get('/edit_customer', 'CustomerController@CustomerForm');
Route::post('/edit_customer', 'CustomerController@UpdateCustomer');

//modifier une reservation
/*Route::get('/edit_reservation', function(){
    return view('edit_reservation');
});*/

Route::get('/edit_reservation', 'ReservationsController@GetAReservation');
Route::post('/edit_reservation', 'ReservationsController@UpdateReservation');

//route pour payement de revservation
Route::get('/buy_reservation', function(){
    return view('buy_reservation');
});
Route::post('/buy_reservation', 'ReservationsController@BuyReservation');

//route impression
Route::get('/printview', function(){
    return view('printview');
});

Route::get('/printreservation', function(){
    return view('printreservation');
});