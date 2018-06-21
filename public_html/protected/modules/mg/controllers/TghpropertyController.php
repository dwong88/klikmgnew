<?php
header('Access-Control-Allow-Origin: *');
class TghpropertyController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				//'users'=>array('@'),
				'users'=>array('*'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Tghproperty;
		//print_r($_REQUEST);
		$now=date('Y-m-d H:i:s');
		$namaDepan=$_REQUEST['namaDepan'];
		$namaBelakang=$_REQUEST['namaBelakang'];
		echo ("INSERT INTO tghproperty (property_cd,market_name, update_dt)VALUES('$namaDepan','$namaBelakang','$now')");
		DAO::executeSql("INSERT INTO tghproperty (property_cd,market_name, update_dt)VALUES('$namaDepan','$namaBelakang','$now')");


		echo json_encode(array('message' => 'Congratulations the record ' . $namaDepan . ' was added to the database'));
		//Yii::app()->end();
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		/*if(isset($_POST['Tghproperty']))
		{
			$model->attributes=$_POST['Tghproperty'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->property_id));
		}

		$this->render('create',array(
			'model'=>$model,
		));*/
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Tghproperty']))
		{
			$model->attributes=$_POST['Tghproperty'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->property_id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$hotels = DAO::queryAllSql("SELECT property_cd, property_name
																						FROM tghproperty");
		$countselect = count($hotels);
		for($c=0;$c<$countselect;$c++)
		{
			$property_cd = $hotels[$c]['property_cd'];
			$property_name = $hotels[$c]['property_name'];
		}

		echo json_encode($hotels);
		/*$dataProvider=new CActiveDataProvider('Tghproperty');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));*/
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Tghproperty('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Tghproperty']))
			$model->attributes=$_GET['Tghproperty'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Tghproperty the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Tghproperty::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Tghproperty $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='tghproperty-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
