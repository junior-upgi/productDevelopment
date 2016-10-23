<?php
/**
 * 使用者權限規則控管
 *
 * @version 1.0.0
 * @author spark it@upgi.com.tw
 * @date 16/10/19
 * @since 1.0.0 spark: 於此版本開始編寫註解
 */
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Auth;
use Redirect;

/**
 * Class Role
 *
 * @package App\Http\Controllers
 */
class Role extends Controller
{

    /**
     * 允許的權限
     * 
     * @param string $Role
     * @return bool 回傳結果
     */
    static public function allowRole($Role)
    {
        $RoleArray = explode('|', $Role);
        foreach ($RoleArray as $role) {
            if ($role === Auth::user()->authorization) {
                return true;
            }
        }
        return false;
    }

    /**
     * 拒絕的權限
     * 
     * @param string $Role
     * @param string $route
     * @return bool 回傳結果
     */
    static public function delineRole($Role, $route = '')
    {
        $RoleArray = explode('|', $Role);
        foreach ($RoleArray as $role) {
            if ($role === Auth::user()->authorization) {
                return true;
            }
        }
        return false;
    }
}