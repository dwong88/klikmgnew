<?php
/**
 * Created by PhpStorm.
 * User: wiyantotan
 * Date: 07/06/18
 * Time: 21:06
 */

class CommController extends CController
{
    public function getTheme()
    {
        return array();
    }

    public function getViewFile($viewName)
    {
        echo $viewName;
//        if(($theme=Yii::app()->getTheme())!==null && ($viewFile=$theme->getViewFile($this,$viewName))!==false)
//            return $viewFile;
//        $moduleViewPath=$basePath=Yii::app()->getViewPath();
//        if(($module=$this->getModule())!==null)
//            $moduleViewPath=$module->getViewPath();
//        return $this->resolveViewFile($viewName,$this->getViewPath(),$basePath,$moduleViewPath);
    }
}