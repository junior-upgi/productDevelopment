<?php
namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Response;

class GitApiController extends Controller
{
    /*



    */
    public function test() 
    {
        $url = "https://raw.githubusercontent.com/NightSpark75/productDevelopment/9c8a8b84982ec142c554075231be6a14e98fe3e2/a.jpg";
        $data = $this->copy($url);
        return response()->download($data, 'download.jpg');
        //$json = $this->get_data('https://raw.githubusercontent.com/NightSpark75/productDevelopment/master/composer.json');
        
        //$data = $this->copy('https://raw.githubusercontent.com/NightSpark75/productDevelopment/master/readme.md');

        //return response()->download($data, 'download.json');
        //$json = $this->get_fcontent('https://api.github.com/repos/NightSpark75/productDevelopment/commits?file=app/Http/Controllers');
        //$obj = json_decode($json);
        //return $obj;
    }
    public function temp($url)
    {
        $file = tmpfile();
        copy($url, $file);
        return $file;
    }
    public function copy($url)
    {
        $name = 'temp';
        $temp = storage_path('framework/views/');
        $tempFile = tempnam($temp, $name);
        copy($url, $tempFile);
        return $tempFile;
    }

    public function get_data($url)
    {
        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
}