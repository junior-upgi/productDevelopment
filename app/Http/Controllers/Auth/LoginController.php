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

use App\Models\upgiSystem\User;
//use App\Models;


class LoginController extends Controller 
{
    
    public $user;
    public $common;

    public function __construct(
        User $user,
        Common $common
    ) {
        $this->user = $user;
        $this->common = $common;
    }

    public function hashPassword()
    {
        $userList = $this->user->all();
        foreach ($userList as $list) {
            $id = $list->ID;
            $p = Hash::make($list->mobileSystemAccount);
            $us = $this->user;
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
        if (Auth::check()) {
            $user = Auth::user();
            $role = $user->authorization;
            if ($role === '99') {
                return redirect('Project/ProjectList');
            } else {
                return redirect('Process/MyProcess');
            }
        } else {
            return view('Login.login');
        }
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
            //single sign on type 0=DB, 1=LDAP
            $attempt = $this->common->singleSignOn($input['account'], $input['password'], 0);

            if ($attempt) {
                if (Auth::check()) {
                    $user = Auth::user();
                    $role = $user->authorization;
                    if ($role === '99') {
                        return redirect('Project/ProjectList');
                    } else {
                        return redirect('Process/MyProcess');
                    }
                    //return Redirect::intended('/Project/ProjectList');
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
}