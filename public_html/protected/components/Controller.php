<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
error_reporting(E_ALL);
class Controller extends CController
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//layouts/column1';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();

    /**
     * show all query executed with CActiveRecord.
     * @var boolean set it to true, will show all query executed with CActiveRecord.
     */
    public static $showSql = false;

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    public function init()
    {
        Yii::app()->clientScript->registerScript('___submitbutton', "
		$(\"input[type='submit'][value!='Search']\").click(function(){
			$(this).attr(\"disabled\",true);
			this.form.submit();
		});
		");
    }

    public function accessRules()
    {
        $rules = array(
            array('allow','users'=>array('@')),
            array('deny', 'users' => array('*')),
        );

        return $rules;
    }

    protected function beforeAction($action)
    {
        return true;
    }
}