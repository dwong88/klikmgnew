<?php
header('Access-Control-Allow-Origin: *');
class SiteController extends Controller
{
	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		$this->render('index');
	}

    public function actionHome()
    {
        // renders the view file 'protected/views/site/index.php'
        // using the default layout 'protected/views/layouts/main.php'
        $this->render('index');
    }

    public function actionLogin()
    {
        $model=new LoginForm;

        // if it is ajax validation request
        if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if(isset($_POST['LoginForm']))
        {
            $model->attributes=$_POST['LoginForm'];
            // validate user input and redirect to the previous page if valid
            if($model->validate() && $model->login())
                //$this->redirect(Yii::app()->user->returnUrl);
                $this->redirect(array('site/home'));
            else
                $model->password = '';
        }
        // display the login form
        $this->render('login',array('model'=>$model));
    }

	/**
	 * Displays the login page
	 */
	public function actionLoginmobile()
	{
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		if($_REQUEST['email']==null || $_REQUEST['email']=""){
			echo "string";
			$email=$_REQUEST['email'];
			$password=Encryption::encrypt($_REQUEST['password']);
			//echo ("SELECT fullName,email,phoneNo FROM tghusers where email='".$email."' AND password='".$password."';");
			$userpegi=DAO::queryAllSql("SELECT fullName,email,phoneNo FROM tghusers where email='".$email."' AND password='".$password."';");

			$countselect = count($userpegi);
			if($countselect>0)
			{
				echo json_encode(array('message' => 'Congratulations'));
			}
			else{
				echo $error['message'];
			}
		}


		// display the login form
		//$this->render('login',array('model'=>$model));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(array('/site/login'));
	}

	public function actionChangepassword()
	{
		$model=User::model()->findByPk(Yii::app()->user->id);
		$model->scenario = 'changepassword';

		if(isset($_POST['User']))
		{
			$model->attributes=$_POST['User'];
			if(Encryption::encrypt($model->oldpass) != $model->password) {
				$model->addError('oldpass','Wrong Password!');
			} else {
				$model->setAttribute('password', Encryption::encrypt($model->newpass));
				if($model->save()) {
					$model->updateSuccessfull = 1;
				}
			}
		}

		$model->unsetAttributes(array('oldpass'));
		$model->unsetAttributes(array('newpass'));
		$model->unsetAttributes(array('reenterpass'));
		$this->render('changepass',array(
			'model'=>$model,
		));
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

    public function accessRules()
    {
        return array_merge(array(
            array('deny',
                'actions'=>array('login'),
                'users'=>array('@'),
            ),
            array('allow',
                'actions'=>array('changepassword','home'),
                'users'=>array('@'),
            ),
            array('allow',
                'actions' => array('login','logout'),
                'users' => array('*'),
            )
        ),parent::accessRules());
    }
}
