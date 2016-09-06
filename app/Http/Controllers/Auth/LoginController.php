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
//use App\Models;


class LoginController extends Controller 
{
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
        //驗證表單
        $validator = Validator::make($input, $rules);

        if ($validator->passes()) {
            /*
            $attempt = Auth::attempt([
                'mobileSystemAccount' => $input['account'],
                'password' => $input['password']
            ]);
            */
            $attempt = Auth::attempt([
                'mobileSystemAccount' => '00010001',
                'password' => '00010001'
            ], false, false);
            if ($attempt) {
                return Redirect::intended('post');
            }
            return Redirect::to('login')
                    ->withErrors(['fail'=>'帳號或密碼錯誤!']);
        }
        //fails
        return Redirect::to('login')
                    ->withErrors($validator)
                    ->withInput(Input::except('password'));
    }
    public function logout()
    {
        Auth::logout();
        return Redirect::to('login');
    }
}