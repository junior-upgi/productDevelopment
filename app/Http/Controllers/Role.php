<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Auth;
use Redirect;

class Role extends Controller
{
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
    static public function allowRoleToRedirect($Role)
    {
        $RoleArray = explode('|', $Role);
        foreach ($RoleArray as $role) {
            if ($role === Auth::user()->authorization) {
                return true;
            }
        }
        return Redirect::route('errorRoute');
    }
}