<?php
namespace App\Http\Controllers;

session_start();
use Illuminate\Http\Request;
use DB;
use \App\Models\Admin; //déclaration du model admin dans notre controleur

class LoginController extends Controller
{
    public function LoginForm()
    {
        return view('login');
    }

    public function LoginTreatment()
    {
        request()->validate([
        'pseudo' => ['required'],
        'password' => ['required'],
    ]);

        //DB::table('admins')->wherePseudo(request('pseudo'))->whereMdp(request('password'));
        //Admin::wherePseudoAndMdp(request('pseudo'), request('password'))
       
        if( DB::table('admins')->wherePseudo(request('pseudo'))->whereMdp(request('password'))->count() > 0)
        {
            $theuser = DB::select('SELECT * FROM admins WHERE pseudo = ?', [request('pseudo')])[0];
            $pseudo = request('pseudo');
            //aller avec les sessions c'est mieux
            $_SESSION['theuser'] = $theuser;
            $_SESSION['pseudo'] = $pseudo;
            return view('welcome',  compact('pseudo', 'theuser',));
        }
        else
        {
            $fail = 'Utilisateur inexistant';
            return view('login', compact('fail'));
        }
   
    }
}
