<?php

namespace App\Http\Controllers\ProductDevelopment;

use App\Http\Controllers\Controller;
use DB;

class ProductDevelopmentController extends Controller
{
    /*
    public function __construct()
    {
        $this->middleware('auth');
    }
    */
    
    public function ProductList()
    {
        $project =  DB::table('project')->get();
        return $project;
        //return view('home');
    }
}