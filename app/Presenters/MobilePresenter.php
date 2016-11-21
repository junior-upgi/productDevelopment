<?php
namespace App\Presenters;

use App\Repositories\ProductDevelopment\ProjectRepository;

class MobilePresenter
{
    public $project;

    public function __construct(
        ProjectRepository $project
    ) {
        $this->project = $project;
    }
    public function error()
    {
        $show = "<h2>頁面顯示錯誤</h2><br/>".
                "<h3>請聯絡資訊課人員</h3>";
        return $show;
    }
    public function para($type, $value)
    {
        return $this->project->getParaValue($type, $value);
    }
    public function getDate($date)
    {
        return date('Y-m-d', strtotime($date));
    }
}