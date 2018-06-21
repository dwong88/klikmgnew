<?php
/**
 * Created by PhpStorm.
 * User: wiyantotan
 * Date: 19/04/18
 * Time: 09:29
 */

class WebModule extends CWebModule
{
    private $_assetsUrl;

    /**
     * @return string the base URL that contains all published asset files of gii.
     */
    public function getAssetsUrl()
    {

        if($this->_assetsUrl===null)
            $this->_assetsUrl=Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias($this->getName().'.assets'));
        return $this->_assetsUrl;
    }

    /**
     * @param string $value the base URL that contains all published asset files of gii.
     */
    public function setAssetsUrl($value)
    {
        $this->_assetsUrl=$value;
    }
}