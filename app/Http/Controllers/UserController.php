<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\Client;
use App\Models\Devis;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    public function createAdmin(){
        User::create([
            'email' => 'Admin@gmail.com',
            'password' => 'mdpadmin',
        ]);
    }

    public function goToLoginClient(){
        return view('user.loginClient');
    }

    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return view('user.loginClient');
    }

    public function trun(){
        $tables = Schema::getAllTables();
        DB::statement('SET session_replication_role =replica');
        DB::beginTransaction();

        try{
            foreach ($tables as $table){
                DB::table($table->tablename)->truncate();
            }
            DB::commit();
        }catch (\Exception $e){

        } finally {
            DB::statement('SET session_replication_role = DEFAULT');
            User::create([
                'email' => 'Admin@gmail.com',
                'password' => 'mdpadmin',
            ]);

            return redirect('logAdmin');
        }
    }
    /*public function register(Request $request){
        $request->validate([
            'nom' => 'required',
            'dtn' => 'required|date',
            'pseudo' => 'required',
            'contact' => 'required|regex:#03[2843][0-9]{7}$#',
            'email' => 'required|email',
            'password' => 'required|confirmed',
            'password_confirmation' => 'required',
        ],[

        ]);

        $user=User::create([
            'pseudo' => $request->input("pseudo"),
            'email' => $request->input("email"),
            'password' => $request->input("password"),
            'is_admin' => false,
        ]);

        Client::create([
            'nom' => $request->input("nom"),
            'date_de_naissance' => $request->input("dtn"),
            'contact' => $request->input("contact"),
            'id_users' => $user->id,
        ]);
        return redirect()->intended('/')->with('success', 'Inscription reussi , connectez-vous maintenant');
    }*/

    public function login(LoginRequest $request){
        $credentials = $request->validated();
        if (Auth::attempt($credentials)){
            $request -> session() -> regenerate();
            $user=Auth::user();
            if($user['email']=='Admin@gmail.com'){
                return redirect()->intended('/user/admin');
            }else{
                return redirect()->intended('logAdmin')->with('error', 'Verifier votre mot de passe')->onlyInput('email');
            }
        }else{
            return redirect()->intended('logAdmin')->with('error', 'Verifier votre mot de passe')->onlyInput('email');
        }
    }

    public function loginClient(Request $request){
        $request->validate([
            'contact' => 'required|regex:#03[2843][0-9]{7}$#',
        ],[

        ]);
        $contact =$request->input('contact');
        $client = Client::where('contact',$contact)->first();
        if($client){
            $request->session()->regenerate();
            Session::put('client',$client->id);
            return redirect()->intended('devis/liste');
        }else{
            $user=new Client();
            $request->session()->regenerate();
            $id =$user->insertContact($contact);
            Session::put('client',$id);
            return redirect()->intended('devis/liste');
        }
        return view('mety ve');
    }

    public function admin(){
        $annee= DB::table('devis')
            ->select(DB::raw('DISTINCT EXTRACT(YEAR FROM date_devis) AS year'))
            ->orderBy('year')
            ->get();
        $montant = DB::table('v_montant_total_devis')
                    ->first();
        $paiement = DB::table('v_total_tout_paiement')
            ->first();
        $devis=new Devis();
        $donnees = $devis->getDonneeYear('2024');
        return view('user.admin',[
            'annees' => $annee,
            'donnees' => $donnees,
            'montant' => $montant->montant_total,
            'paiement' => $paiement->paye,
        ]);
    }

    public function client(){
        return view('user.client');
    }

    public function acceuil(){
        $film= new Film();
        $films = $film->getAll();
        $nb = [];
        $titre = [];
        foreach ($films as $f){
            $titre[] = $f->titre;
            $nb[] = $f->duree;
        }
        return view('test',[
            'nb' => $nb,
            'titre' => $titre,
        ]);
    }

}
