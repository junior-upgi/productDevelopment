<?php

namespace App\Http\Controllers\Auth;

//use Class
use App\Http\Controllers\Controller;
use Validator;
use Auth;
use Redirect;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Controllers\Common;
use App\Http\Controllers\ServerData;
use Illuminate\Support\Facades\Hash;

use DB;
use App\Models;
use App\Models\productDevelopment\Para;
use App\Models\productDevelopment\Project;
use App\Models\productDevelopment\VProjectList;
use App\Models\productDevelopment\VProcessList;
use App\Models\productDevelopment\VPreparation;
use App\Models\productDevelopment\ProjectContent;
use App\Models\productDevelopment\ProjectProcess;
use App\Models\productDevelopment\ProcessTree;
use App\Models\companyStructure\VStaff;
use App\Models\companyStructure\Staff;
use App\Models\companyStructure\Node;
use App\Models\sales\Client;
use App\Models\upgiSystem\User;
//use App\Models;


class LoginController extends Controller 
{
    
    public $user;

    public function __construct(
        User $user
    ) {
        $this->user = $user;
    }

    public function hashPassword()
    {
        $user = $this->user->all();
        foreach ($user as $list) {
            $id = $list->ID;
            $p = Hash::make($list->mobileSystemAccount);
            $us = new user();
            $us = $us->where('ID', $id);
            $pa = array(
                'password' => $p,
            );
            $us->update($pa);
            //$list->save();
        }
        return 'yes';
    }

    public function show()
    {
        return view('Login.login');
    }
    public function login(Request $request)
    {
        $input = $request->input();
        //驗證規則
        $rules = array(
            'account'=>'required',
            'password'=>'required'
        );
        isset($input['remember']) ? $remember = true : $remember = false;
        //驗證表單
        $validator = Validator::make($input, $rules);

        if ($validator->passes()) {
            /*
            $attempt = Auth::attempt([
                'mobileSystemAccount' => $input['account'],
                'password' => $input['password'],
            ], $remember);
            */
            $attempt = $this->LDAP($input['account'], $input['password']);
            $login = $this->userLogin($input['account']);
            if ($attempt && $login) {
                if (Auth::check()) {
                    return Redirect::intended('/Project/ProjectList');
                } else {
                    return Redirect::to('login')
                    ->withErrors(['fail'=>'登入失敗!']);
                }
            }
            return Redirect::to('login')
                    ->withErrors(['fail'=>'帳號或密碼錯誤!']);
        }
        //fails
        return Redirect::to('login')
                    ->withErrors($validator)
                    ->withInput(\Input::except('password'));
    }
    public function logout()
    {
        Auth::logout();
        return Redirect::to('login');
    }

    public function LDAP($account, $password)
    {
        $ldaphost = "192.168.168.86";  // your ldap servers
        $ldapport = 389;                 // your ldap server's port number

        // Connecting to LDAP
        $ldapconn = ldap_connect($ldaphost, $ldapport) or die("con't connect LDAP");
        if ($ldapconn) {
            ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
            try {
                $ldapbind = ldap_bind($ldapconn, "uid=$account,ou=user,dc=upgi,dc=ddns,dc=net", $password);
            } catch (\Exception $e) {
                return false;
            }
            return $ldapbind;
        }
    }
    public function userLogin($account)
    {
        $auth = $this->user->where('mobileSystemAccount', $account)->first();
        if ($auth) {
            Auth::loginUsingId($auth->ID);
            return true;
        } else {
            return false;
        }
    }
}