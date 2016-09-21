<?php
namespace App\Presenters;

class MobilePresenter
{
    public function error()
    {
        $show = "<h2>頁面顯示錯誤</h2><br/>".
                "<h3>請聯絡資訊課人員</h3>";
        return $show;
    }
}