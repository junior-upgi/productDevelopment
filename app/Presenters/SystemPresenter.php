<?php
namespace App\Presenters;
use App\Http\Controllers\Common;
use App\Repositories\ProductDevelopment\ProjectRepositories;
class SystemPresenter
{
    public $common;
    public $project;
    public function __construct(
        Common $common,
        ProjectRepositories $project
    ) {
        $this->common = $common;
        $this->project = $project;
    }
    public function getUTF8($str)
    {
        $car = mb_detect_encoding($str);
        if ($car != "UTF-8") {
            $newStr = iconv("BIG-5", "UTF-8", $str);
            return $newStr;
        }
        return $str;
    }
    public function getPic($id, $w, $h)
    {
        if (!isset($id)) return '';
        $pic = $this->common->getFile($id);
        if (isset($id) && isset($pic)) {
            return "<img src='$pic' alt='' class='img-thumbnail' width=$w height=$h>";
        }
        return '';
    }
    public function getImgSrc($id)
    {
        if (!isset($id)) return '';
        $pic = $this->common->getFile($id);
        if (isset($id) && isset($pic)) {
            return $pic;
        }
        return '';
    }
    public function getModalPic($id, $w, $h)
    {
        if (!isset($id)) return '';
        $pic = $this->common->getFile($id);
        if (isset($id) && isset($pic)) {
            return "<img src='$pic' alt='' class='img-thumbnail' width=$w height=$h 
                    onmouseover=\"\"
                    onmouseout=\"\"
                    onclick=\"showimage('$pic')\" />";
        }
        return '';
    }
    public function getIconPic($id)
    {
        if (!isset($id)) return '';
        $pic = $this->common->getFile($id);
        if (isset($id) && isset($pic)) {
            return "<span class='glyphicon glyphicon-picture' 
                    onclick=\"showimage('$pic')\"></span>";
        }
        return '';
    }
    public function getProductPic($id)
    {
        $img = $this->project->getProductPic($id);
        if (!isset($img)) return '';
        $pic = $this->common->getFile($img);
        if (isset($id) && isset($pic)) {
            return "<span class='glyphicon glyphicon-picture' 
                    onclick=\"showimage('$pic')\"></span>";
        }
        return '';
    }
    public function replaceBR($str)
    {
        $replace = nl2br($str);
        return $replace;
    }
}