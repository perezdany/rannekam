<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Models\Client;
use DB;

class CustomerController extends Controller
{
    //
    public function LoadPage()
    {
        return view('customers');
    }

    public function CustomerForm()
    {
       return view('edit_customer');
    }

    public function all_customer()
    {
        $clients = Client::all();
        return $clients;
    }

    public function UpdateCustomer()
    {
        //mise a jour, modification des info du client
        $edit = DB::table('clients')->whereId_clt(request('id'))->update(['nom_clt' => request('nom'), 'pnom_clt' => request('pnom'), 'tel' => request('tel'), 'mail' => request('mail'), 'address' => request('ad')]);

        //var_dump(request('id'));

        $message = 'Modification effectuée avec succès!';
        return view('edit_customer', compact('message'));
    }

    public function DeleteACustomer()
    {
        DB::table('clients')->whereId_clt(request('id'))->delete();
        return view('customers');
    }

}