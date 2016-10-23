<?php
/**
 * 使用者登入相關功能
 *
 * @version 1.0.0
 * @author spark it@upgi.com.tw
 * @date 16/10/14
 * @since 1.0.0 spark: 於此版本開始編寫註解
 */
namespace App\Http\Controllers\Auth;

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

/**
 * Class LoginController
 *
 * @package App\Http\Controllers\Auth
 */
class LoginController extends Controller 
{
    /** @var User 注入User Model */
    private $user;
    /** @var Common 注入Common共用元件 */
    private $common;

    /**
     * 建構式
     *
     * @param User $user
     * @param Common $common
     * @return void
     */
    public function __construct(
        User $user,
        Common $common
    ) {
        $this->user = $user;
        $this->common = $common;
    }

    /**
     * 顯示登入頁面
     * 根據不同的權限導向對應的頁面
     *
     * @return view 回傳頁面
     */
    public function show()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $role = $user->authorization;
            define("MANAGER", '99');
            if ($role == MANAGER) {
                return redirect('Project/ProjectList');
            } else {
                return redirect('Process/MyProcess');
            }
        } else {
            return view('Login.login');
        }
    }

    /**
     * 驗證並登入
     * 
     * @param Request $request request物件
     * @return view 回傳頁面
     */
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
        $type = $type = env('WebSSO', 'LDAP');
        if ($validator->passes()) {
            $attempt = $this->common->singleSignOn($input['account'], $input['password'], $type);

            if ($attempt) {
                if (Auth::check()) {
                    $user = Auth::user();
                    $role = $user->authorization;
                    if ($role === '99') {
                        return redirect('Project/ProjectList');
                    } else {
                        return redirect('Process/MyProcess');
                    }
                } else {
                    return Redirect::to('login')
                    ->withErrors(['fail'=>'登入失敗!']);
                }
            }
            return Redirect::to('login')
                    ->withErrors(['fail'=>'帳號或密碼錯誤!']);
        }
        return Redirect::to('login')
                    ->withErrors($validator)
                    ->withInput(\Input::except('password'));
    }

    /**
     * 登出
     * @return view 回傳登入頁
     */
    public function logout()
    {
        Auth::logout();
        return Redirect::to('login');
    }
}