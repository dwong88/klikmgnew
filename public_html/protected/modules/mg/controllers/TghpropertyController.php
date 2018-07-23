<?php
header('Access-Control-Allow-Origin: *');
class Resultec {} #create
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
				'actions'=>array('index','view','search','searchsession','searchsessionfacility','searchsessiondetail','searchsessionionic','Currentuser','Autouser','Viewhoteldetail'),
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

	public function actionSearch()
	{
		$destCity = $_GET['destCity'];
		$hotels = DAO::queryAllSql("SELECT location_type, location_code,location_name as description
																						FROM tghsearchlocation
																						WHERE location_name like '".$destCity."%'");
		$countselect = count($hotels);

		echo json_encode($hotels);
		/*$dataProvider=new CActiveDataProvider('Tghproperty');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));*/
	}

	public function actionSearchsession($destCitycode,$checkin,$duration,$code,$guest,$room)
	{
			//echo "string";
			//echo $guest;
		//$checkOut=$_POST['checkOut'];

			$checkIn = substr($checkin,0,10);
		//$newDate = date("Y-m-d", strtotime($originalDate));

        if($duration!=null){
            $tambahhari = '+'.$duration. 'day';
            $newdate = strtotime ( $tambahhari , strtotime ( $checkIn ) ) ;
            $checkOut = date ( 'Y-m-d' , $newdate );
        }
        else{
            $checkOut=$_GET['checkout'];
        }

				$hotels='';
		if($code==2){
		 $hotels = DAO::queryAllSql("SELECT property_cd,property_name,roomcateg_name as name,price,check_in,check_out,roomcateg_id,roomcateg_name,totalprice,bftype,roomtype
																						FROM tghsearchprice
																						WHERE property_cd = '".$destCitycode."'
																						AND check_in = '".$checkIn."'
																						AND check_out = '".$checkOut."'
																						GROUP BY tghsearchprice.roomcateg_name
																						");
		}

		if($code==3){
			if ($room > 1) {
					$avgAdultNumInRoom = ceil($guest / $room);
					$arrRooms = array();
					for ($i = 0; $i < $room; $i++) {
							$arrRooms[] = array('numAdults' => $avgAdultNumInRoom);
							//echo "kamar isi";
							//echo $arrRooms[$i]['numAdults'];
					}
					$hitungkamar=count($arrRooms);
					for($kr=0;$kr<$hitungkamar;$kr++){
						if($arrRooms[$kr]['numAdults']==1){
							$RoomType = 'Single';
						}
						if($arrRooms[$kr]['numAdults']==2){
							$RoomType = 'Twin';
						}
						if($arrRooms[$kr]['numAdults']==3){
							$RoomType = 'Triple';
						}
						if($arrRooms[$kr]['numAdults']==4){
							$RoomType = 'Quad';
						}
						$hotels = DAO::queryAllSql("SELECT tghproperty.property_cd as property_cd,tghproperty.country_cd as country_cd,tghproperty.city_cd as city_cd,tghproperty.property_name as name,tghsearchprice.totalprice,tghsearchprice.roomprice as price,tghproperty.numberofstar as numberofstar,
								tghproperty.gmaps_latitude,tghproperty.gmaps_longitude,tghproperty.addressline1 as displayAddress,tghproperty.addressline1 as address,tghsearchprice.roomtype as RoomType,
								tghsearchprice.roomcateg_id as roomcateg_id,tghsearchprice.roomcateg_name as roomcateg_name,tghsearchprice.BFType as BFType,tghsearchprice.check_in as check_in,tghsearchprice.check_out as check_out
																										FROM tghsearchprice
																						LEFT JOIN tghproperty
																												ON  Tghproperty.property_cd = tghsearchprice.property_cd
																						LEFT JOIN tghpropertyphoto
																												ON  tghpropertyphoto.property_id = Tghproperty.property_id
																												AND tghpropertyphoto.propertyphototype_id='1'
																										WHERE tghsearchprice.property_cd like '".$destCitycode."%'
																										AND tghsearchprice.check_in = '".$checkIn."'
																										AND tghsearchprice.check_out = '".$checkOut."'
																										AND tghsearchprice.roomtype = '".$RoomType."'
																										GROUP BY tghproperty.property_name
																										");
																										//GROUP BY tghsearchprice.roomcateg_name,tghsearchprice.RoomType,tghproperty.property_name
					}

			}else{
						$arrRooms[] = array('numAdults' => $guest);
						//echo "kamar isi";
						//echo $arrRooms[0]['numAdults'];
						if($arrRooms[0]['numAdults']==1){
							$RoomType = 'Single';
						}
						if($arrRooms[0]['numAdults']==2){
							$RoomType = 'Twin';
						}
						if($arrRooms[0]['numAdults']==3){
							$RoomType = 'Triple';
						}
						if($arrRooms[0]['numAdults']==4){
							$RoomType = 'Quad';
						}
						/*echo ("SELECT tghproperty.country_cd as country_cd,tghproperty.city_cd as city_cd,tghproperty.property_name as name,tghsearchprice.roomprice,
								tghproperty.gmaps_latitude,tghproperty.gmaps_longitude,tghproperty.addressline1 as displayAddress,tghproperty.addressline1 as address,tghsearchprice.roomtype as RoomType,
								tghsearchprice.roomcateg_id as roomcateg_id,tghsearchprice.roomcateg_name as roomcateg_name,tghsearchprice.BFType as BFType,tghsearchprice.check_in as check_in,tghsearchprice.check_out as check_out
																										FROM tghsearchprice
																					LEFT JOIN tghproperty
																											ON  Tghproperty.property_cd = tghsearchprice.property_cd
																					LEFT JOIN tghpropertyphoto
																											ON  tghpropertyphoto.property_id = Tghproperty.property_id
																											AND tghpropertyphoto.propertyphototype_id='1'
																										WHERE tghsearchprice.property_cd like '".$destCitycode."%'
																										AND tghsearchprice.check_in = '".$checkIn."'
																										AND tghsearchprice.check_out = '".$checkOut."'
																										AND tghsearchprice.roomtype = '".$RoomType."'
																										GROUP BY tghsearchprice.roomcateg_name,tghsearchprice.RoomType
																										");*/
						$hotels = DAO::queryAllSql("SELECT tghproperty.property_cd as property_cd,tghproperty.country_cd as country_cd,tghproperty.city_cd as city_cd,tghproperty.property_name as name,tghsearchprice.totalprice,tghsearchprice.roomprice as price,tghproperty.numberofstar as numberofstar,
								tghproperty.gmaps_latitude,tghproperty.gmaps_longitude,tghproperty.addressline1 as displayAddress,tghproperty.addressline1 as address,tghsearchprice.roomtype as RoomType,
								tghsearchprice.roomcateg_id as roomcateg_id,tghsearchprice.roomcateg_name as roomcateg_name,tghsearchprice.BFType as BFType,tghsearchprice.check_in as check_in,tghsearchprice.check_out as check_out
																										FROM tghsearchprice
																					LEFT JOIN tghproperty
																											ON  Tghproperty.property_cd = tghsearchprice.property_cd
																					LEFT JOIN tghpropertyphoto
																											ON  tghpropertyphoto.property_id = Tghproperty.property_id
																											AND tghpropertyphoto.propertyphototype_id='1'
																										WHERE tghsearchprice.property_cd like '".$destCitycode."%'
																										AND tghsearchprice.check_in = '".$checkIn."'
																										AND tghsearchprice.check_out = '".$checkOut."'
																										AND tghsearchprice.roomtype = '".$RoomType."'
																										GROUP BY tghproperty.property_name
																										");
																										//GROUP BY tghsearchprice.roomcateg_name,tghsearchprice.RoomType,tghproperty.property_name
				//echo json_encode($hotels);
			}
			if($room <= 1) {
					$arrRooms[] = array('numAdults' => $guest);
						//echo "kamar isi";
						if($arrRooms[0]['numAdults']==1){
							$RoomType = 'Single';
						}
						if($arrRooms[0]['numAdults']==2){
							$RoomType = 'Twin';
						}
						if($arrRooms[0]['numAdults']==3){
							$RoomType = 'Triple';
						}
						if($arrRooms[0]['numAdults']==4){
							$RoomType = 'Quad';
						}
						//echo $arrRooms[0]['numAdults'];
						$hotels = DAO::queryAllSql("SELECT tghproperty.property_cd as property_cd,tghproperty.country_cd as country_cd,tghproperty.city_cd as city_cd,tghproperty.property_name as name,tghsearchprice.totalprice,tghsearchprice.roomprice as price,tghproperty.numberofstar as numberofstar,
								tghproperty.gmaps_latitude,tghproperty.gmaps_longitude,tghproperty.addressline1 as displayAddress,tghproperty.addressline1 as address,tghsearchprice.roomtype as RoomType,
								tghsearchprice.roomcateg_id as roomcateg_id,tghsearchprice.roomcateg_name as roomcateg_name,tghsearchprice.BFType as BFType,tghsearchprice.check_in as check_in,tghsearchprice.check_out as check_out
																										FROM tghsearchprice
																					LEFT JOIN tghproperty
																											ON  Tghproperty.property_cd = tghsearchprice.property_cd
																					LEFT JOIN tghpropertyphoto
																											ON  tghpropertyphoto.property_id = Tghproperty.property_id
																											AND tghpropertyphoto.propertyphototype_id='1'
																										WHERE tghsearchprice.property_cd like '".$destCitycode."%'
																										AND tghsearchprice.check_in = '".$checkIn."'
																										AND tghsearchprice.check_out = '".$checkOut."'
																										AND tghsearchprice.roomtype = '".$RoomType."'
																										GROUP BY tghproperty.property_name
																										");
																										//GROUP BY tghsearchprice.roomcateg_name,tghsearchprice.RoomType,tghproperty.property_name

			}
		/*$hotels = DAO::queryAllSql("SELECT Tghproperty.property_name as name,tghsearchprice.price,
				Tghproperty.gmaps_latitude,Tghproperty.gmaps_longitude,Tghproperty.addressline1 as displayAddress,Tghproperty.addressline1 as address,Tghproperty.numberofstar,
				Tghproperty.property_cd as hotelcode,tghsearchprice.check_in as check_in, tghsearchprice.check_out as check_out,tghpropertyphoto.filename as filename
																						FROM tghsearchprice
																LEFT JOIN tghproperty
																						ON  Tghproperty.property_cd = tghsearchprice.property_cd
																LEFT JOIN tghpropertyphoto
																						ON  tghpropertyphoto.property_id = Tghproperty.property_id
																						AND tghpropertyphoto.propertyphototype_id='1'
																						WHERE tghsearchprice.property_cd like '".$destCitycode."%'
																						AND tghsearchprice.check_in = '".$checkIn."'
																						AND tghsearchprice.check_out = '".$checkOut."'
																						GROUP BY Tghproperty.property_name
																						");*/
		}
		$countselect = count($hotels);

				echo json_encode($hotels);
		/*$dataProvider=new CActiveDataProvider('Tghproperty');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));*/
	}

	public function actionSearchsessiondetail1($destCitycode,$checkin,$duration=null,$checkout)
	{
		//print_r($_GET);
		/*$duration='';
		$tempcheckIn='';
		$location_type='';
		$hotels='';*/
		/*if(isset($_GET['duration'])){
			$destCitycode = $_GET['destCitycode'];
			$duration=$_GET['duration'];
			$location_type=$_GET['code'];
			$tempcheckIn=$_GET['checkin'];
		}*/

		//$checkOut=$_POST['checkOut'];

			$checkIn = substr($checkin,0,10);
		//$newDate = date("Y-m-d", strtotime($originalDate));

        if($duration!=null){
            $tambahhari = '+'.$duration. 'day';
            $newdate = strtotime ( $tambahhari , strtotime ( $checkIn ) ) ;
            $checkOut = date ( 'Y-m-d' , $newdate );
        }
        else{
            $checkOut=$_GET['checkout'];
						$checkOut=$checkout;
        }

				$hotels='';
				$code=2;
		if($code==2){
			/*echo ("SELECT tghsearchprice.property_cd,tghsearchprice.property_name,tghsearchprice.roomcateg_name as name,
 			 tghsearchprice.price,tghsearchprice.check_in,tghsearchprice.check_out,tghsearchprice.roomcateg_id,
 			 tghsearchprice.roomcateg_name,tghsearchprice.TotalPrice,tghsearchprice.BFType,tghsearchprice.RoomType,
 			 tghmspropertyfeatures.features_name
 																						FROM tghsearchprice
															LEFT JOIN tghpropertyfeatures
																					ON  tghpropertyfeatures.property_id = tghsearchprice.property_cd
 															LEFT JOIN tghmspropertyfeatures
 					  															ON  tghmspropertyfeatures.prop_features_id = tghpropertyfeatures.prop_features_id
 																						WHERE property_cd = '".$destCitycode."'
 																						AND check_in = '".$checkIn."'
 																						AND check_out = '".$checkOut."'
 																						GROUP BY tghsearchprice.roomcateg_name
 																						");*/
			/*echo ("SELECT tghsearchprice.property_cd,tghsearchprice.property_name,tghsearchprice.roomcateg_name as name,
 			tghsearchprice.price,tghsearchprice.check_in,tghsearchprice.check_out,tghsearchprice.roomcateg_id,
 			tghsearchprice.roomcateg_name,tghsearchprice.TotalPrice,tghsearchprice.BFType,tghsearchprice.RoomType
 																					 FROM tghsearchprice
 														 						 WHERE property_cd = '".$destCitycode."'
 																					 AND check_in = '".$checkIn."'
 																					 AND check_out = '".$checkOut."'
 																					 GROUP BY tghsearchprice.roomcateg_name,tghsearchprice.RoomType
 																					 ");*/
			 /*echo ("SELECT tghproperty.country_cd as country_cd,tghproperty.city_cd as city_cd,tghproperty.property_name as name,tghsearchprice.price,
	         tghproperty.gmaps_latitude,tghproperty.gmaps_longitude,tghproperty.addressline1 as displayAddress,tghproperty.addressline1 as address,tghsearchprice.RoomType as RoomType,
	         tghsearchprice.roomcateg_id as roomcateg_id,tghsearchprice.roomcateg_name as roomcateg_name,tghsearchprice.BFType as BFType
	                                             FROM tghsearchprice
	                                 INNER JOIN tghproperty
	                                             ON  tghproperty.property_cd = tghsearchprice.property_cd
	                                             WHERE tghsearchprice.property_cd ='".$destCitycode."'
	                                             AND tghsearchprice.check_in = '".$checkIn."'
	                                             AND tghsearchprice.check_out = '".$checkOut."'
	                                             GROUP BY tghsearchprice.roomcateg_name,tghsearchprice.RoomType
	                                             ");*/
		 $hotels = DAO::queryAllSql("SELECT tghproperty.country_cd as country_cd,tghproperty.city_cd as city_cd,tghproperty.property_name as hotel_name,tghsearchprice.price,
				 tghproperty.gmaps_latitude,tghproperty.gmaps_longitude,tghproperty.addressline1 as displayAddress,tghproperty.addressline1 as address,tghsearchprice.RoomType as RoomType,
				 tghsearchprice.roomcateg_id as roomcateg_id,tghsearchprice.roomcateg_name as name,tghsearchprice.BFType as BFType,tghsearchprice.TotalPrice,tghsearchprice.check_in as check_in,tghsearchprice.check_out as check_out
																						 FROM tghsearchprice
																 INNER JOIN tghproperty
																						 ON  tghproperty.property_cd = tghsearchprice.property_cd
																						 WHERE tghsearchprice.property_cd ='".$destCitycode."'
																						 AND tghsearchprice.check_in = '".$checkIn."'
																						 AND tghsearchprice.check_out = '".$checkOut."'
																						 GROUP BY tghsearchprice.roomcateg_name,tghsearchprice.RoomType
																						 ");
		}

		if($code==3){
			/*echo ("SELECT Tghproperty.property_name as name,tghsearchprice.price
																							FROM tghsearchprice
																	INNER JOIN tghproperty
							  															ON  Tghproperty.property_cd = tghsearchprice.property_cd
																							WHERE tghsearchprice.property_cd like '".$destCitycode."%'
																							AND tghsearchprice.check_in = '".$checkIn."'
																							AND tghsearchprice.check_out = '".$checkOut."'
																							GROUP BY Tghproperty.property_name
																							");*/
		$hotels = DAO::queryAllSql("SELECT Tghproperty.property_name as name,tghsearchprice.price,
				Tghproperty.gmaps_latitude,Tghproperty.gmaps_longitude,Tghproperty.addressline1 as displayAddress,Tghproperty.addressline1 as address,
				Tghproperty.property_cd as hotelcode,tghsearchprice.check_in as check_in, tghsearchprice.check_out as check_out
																						FROM tghsearchprice
																INNER JOIN tghproperty
						  															ON  Tghproperty.property_cd = tghsearchprice.property_cd
																						WHERE tghsearchprice.property_cd like '".$destCitycode."%'
																						AND tghsearchprice.check_in = '".$checkIn."'
																						AND tghsearchprice.check_out = '".$checkOut."'
																						GROUP BY Tghproperty.property_name
																						");
		}
		$countselect = count($hotels);

		echo json_encode($hotels);
		/*$dataProvider=new CActiveDataProvider('Tghproperty');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));*/
	}

	public function actionSearchsessionfacility($destCitycode,$checkin,$duration=null,$checkout)
	{
		//print_r($_GET);
		/*$duration='';
		$tempcheckIn='';
		$location_type='';
		$hotels='';*/
		/*if(isset($_GET['duration'])){
			$destCitycode = $_GET['destCitycode'];
			$duration=$_GET['duration'];
			$location_type=$_GET['code'];
			$tempcheckIn=$_GET['checkin'];
		}*/

		//$checkOut=$_POST['checkOut'];

			$checkIn = substr($checkin,0,10);
		//$newDate = date("Y-m-d", strtotime($originalDate));

        if($duration!=null){
            $tambahhari = '+'.$duration. 'day';
            $newdate = strtotime ( $tambahhari , strtotime ( $checkIn ) ) ;
            $checkOut = date ( 'Y-m-d' , $newdate );
        }
        else{
            $checkOut=$_GET['checkout'];
						$checkOut=$checkout;
        }

				$hotels='';
				$code=2;
		if($code==2){
			/*echo ("select * from tghproperty
LEFT JOIN tghpropertyphoto ON tghpropertyphoto.property_id = Tghproperty.property_id
LEFT JOIN tghpropertyfeatures ON tghpropertyfeatures.property_id = tghproperty.property_cd
LEFT JOIN tghmspropertyfeatures ON tghmspropertyfeatures.prop_features_id = tghpropertyfeatures.prop_features_id
WHERE Tghproperty.property_cd = '".$destCitycode."'
GROUP BY tghmspropertyfeatures.features_name
 																					 ");*/
		 $hotels = DAO::queryAllSql("select * from tghproperty
LEFT JOIN tghpropertyphoto ON tghpropertyphoto.property_id = Tghproperty.property_id
LEFT JOIN tghpropertyfeatures ON tghpropertyfeatures.property_id = tghproperty.property_cd
LEFT JOIN tghmspropertyfeatures ON tghmspropertyfeatures.prop_features_id = tghpropertyfeatures.prop_features_id
WHERE Tghproperty.property_cd = '".$destCitycode."'
GROUP BY tghmspropertyfeatures.features_name
																					");
		}

		if($code==3){
			/*echo ("SELECT Tghproperty.property_name as name,tghsearchprice.price
																							FROM tghsearchprice
																	INNER JOIN tghproperty
							  															ON  Tghproperty.property_cd = tghsearchprice.property_cd
																							WHERE tghsearchprice.property_cd like '".$destCitycode."%'
																							AND tghsearchprice.check_in = '".$checkIn."'
																							AND tghsearchprice.check_out = '".$checkOut."'
																							GROUP BY Tghproperty.property_name
																							");*/
		$hotels = DAO::queryAllSql("SELECT Tghproperty.property_name as name,tghsearchprice.price,
				Tghproperty.gmaps_latitude,Tghproperty.gmaps_longitude,Tghproperty.addressline1 as displayAddress,Tghproperty.addressline1 as address,
				Tghproperty.property_cd as hotelcode,tghsearchprice.check_in as check_in, tghsearchprice.check_out as check_out
																						FROM tghsearchprice
																INNER JOIN tghproperty
						  															ON  Tghproperty.property_cd = tghsearchprice.property_cd
																						WHERE tghsearchprice.property_cd like '".$destCitycode."%'
																						AND tghsearchprice.check_in = '".$checkIn."'
																						AND tghsearchprice.check_out = '".$checkOut."'
																						GROUP BY Tghproperty.property_name
																						");
		}
		$countselect = count($hotels);

		echo json_encode($hotels);
		/*$dataProvider=new CActiveDataProvider('Tghproperty');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));*/
	}


    public function actionSearchsessionionic()
    {
        //
        //print_r($_GET);
        //print_r($_POST);
				$hotelCode='';
				$rommCatCode='';
				$duration='';
				$currency="IDR";
				$nationalities="WSASSG";
        //echo "string";
        //print_r($_REQUEST);
        // mg/hoteldata/searchhotel&destCountry=WSASTH&city=WSASTHBKK&hotelCode=&rommCatCode=&checkIn=2018-06-06&checkOut=2018-06-07
        if(isset($_POST['city']))
        {
            $destCity=$_POST['city'];
            $duration=$_POST['duration'];
            $tempcheckIn=$_POST['checkin'];
            //$checkOut=$_POST['checkOut'];

            $checkIn = substr($tempcheckIn,0,10);
            $tambahhari = '+'.$duration. 'day';
            $newdate = strtotime ( $tambahhari , strtotime ( $checkIn ) ) ;
            $checkOut = date ( 'Y-m-d' , $newdate );
            $AdultNum=$_POST['guest'];
            //$ChildNum=$_POST['ChildNum'];
            //$NumRooms=$_POST['NumRooms'];
            $NumRooms=$_POST['room'];

            if($NumRooms== NULL || $NumRooms==0)
            {
                $NumRooms=1;
            }
            if($AdultNum== NULL || $AdultNum==0)
            {
                $AdultNum=1;
            }

            $jumlah_tamu=$AdultNum;
            $check_in=$checkIn;
            $check_out=$checkOut;
            $now=date('Y-m-d H:i:s');

            $searchloc = DAO::queryAllSql("SELECT location_type, location_code
                                                  FROM tghsearchlocation
                                                  WHERE location_name = '".$destCity."'");
            $countsearch= count($searchloc);
            for($cs=0;$cs<$countsearch;$cs++)
            {
                $location_type = $searchloc[$cs]['location_type'];
                $location_code = $searchloc[$cs]['location_code'];
                #query kondisi dari search location_type
                if($location_type==2){
                    $country = DAO::queryAllSql("SELECT country_cd, city_cd
                                                        FROM tghproperty
                                                        WHERE property_name = '".$destCity."'");
                }

                if($location_type==3){
                    $country = DAO::queryAllSql("SELECT country_cd, city_cd
                                                        FROM tghcitymg
                                                        WHERE city_cd ='".$location_code."'");
                }
                //echo ("INSERT INTO tghsearchsession (location_code,location_type,check_in,check_out,jumlah_kamar,jumlah_tamu, update_dt)VALUES('$location_code','$location_type','$check_in','$check_out','$NumRooms','$jumlah_tamu','$now')");
                DAO::executeSql("INSERT INTO tghsearchsession (location_code,location_type,check_in,check_out,jumlah_kamar,jumlah_tamu, update_dt)VALUES('$location_code','$location_type','$check_in','$check_out','$NumRooms','$jumlah_tamu','$now')");

            }
            //  Yii::app()->end();
            $countselect = count($country);
            for($c=0;$c<$countselect;$c++)
            {
                $destCountry = $country[$c]['country_cd'];
                $city = $country[$c]['city_cd'];
            }
            //Yii::app()->end();
            if($NumRooms<=1){

                $arrRooms[] = array('numAdults'=>$NumRooms);
                //print_r($arrRooms);
            }
            else{
                $avgAdultNumInRoom = ceil($AdultNum/$NumRooms);
                $arrRooms = array();
                for($i=0; $i<$NumRooms; $i++) {
                    /*$sisaGuest = 0;
                    if($i == ($NumRooms-1) ) {
                      $sisaGuest = $AdultNum - ($avgAdultNumInRoom * $NumRooms);
                    }*/
                    $arrRooms[] = array('numAdults'=>$avgAdultNumInRoom);
                }
            }
						$arrRooms = array(
								array('numAdults' => $NumRooms)
						);
						if ($NumRooms > 1) {
								$avgAdultNumInRoom = ceil($AdultNum / $NumRooms);
								$arrRooms = array();
								for ($i = 0; $i < $NumRooms; $i++) {
										$arrRooms[] = array('numAdults' => $avgAdultNumInRoom);
										//echo "kamar isi";
										//echo $arrRooms[$i]['numAdults'];
								}
						}else{
									$arrRooms[] = array('numAdults' => $AdultNum);
									//echo "kamar isi";
									//  echo $arrRooms[0]['numAdults'];
						}
						if($NumRooms == 1) {
								$arrRooms[] = array('numAdults' => $AdultNum);
									//echo "kamar isi";
									//echo $arrRooms[0]['numAdults'];
						}

            //Yii::app()->end();
            #looping json
						$qCekSession = DAO::queryRowSql('SELECT update_dt, session_id
																						FROM tghsearchsession
																						WHERE nationalities = :nat
																						AND currency = :cur
																						AND location_code = :lcd
																						AND location_type = :lty
																						AND check_in = :cin
																						AND check_out = :cot
																						AND jumlah_kamar = :kmr
																						AND jumlah_tamu = :tmu'
																		, array(':nat'=>$nationalities, ':cur'=>$currency, ':lcd'=>$city
																				, ':lty'=>$location_type, ':cin'=>$check_in, ':cot'=>$check_out, ':kmr'=>$NumRooms, ':tmu'=>$AdultNum));

						$sessionId = 0;
						if($qCekSession !== false) {
								$nowDate = date_create();
								$sessionDate = date_create($qCekSession['update_dt']);
								$diff = date_diff( $sessionDate, $nowDate );
								if(abs($diff->i) <= 30) {
										$sessionId = $qCekSession['session_id'];
								}
						}

						if($sessionId == 0){
								$arrRooms = array(
										array('numAdults' => $NumRooms)
								);
								if ($NumRooms > 1) {
										$avgAdultNumInRoom = ceil($AdultNum / $NumRooms);
										$arrRooms = array();
										for ($i = 0; $i < $NumRooms; $i++) {
												$arrRooms[] = array('numAdults' => $avgAdultNumInRoom);
												//echo "kamar isi";
												//echo $arrRooms[$i]['numAdults'];
										}
								}else{
											$arrRooms[] = array('numAdults' => $AdultNum);
											//echo "kamar isi";
											//  echo $arrRooms[0]['numAdults'];
								}
								if($NumRooms == 1) {
										$arrRooms[] = array('numAdults' => $AdultNum);
											//echo "kamar isi";
											//echo $arrRooms[0]['numAdults'];
								}

								$xmlRequest = $this->renderPartial('req_searchhotelionic'
										, array(
												'nationalities' => $nationalities,
												'currency' => $currency,
												'destCountry'=> $destCountry,
												'city' => $city,
												'hotelCode' => $hotelCode,
												'rommCatCode' => '',
												'checkIn' => $checkIn,
												'checkOut' => $checkOut,
												'AdultNum' => $AdultNum,
												'NumRooms' => $NumRooms,
												'arrRooms' => $arrRooms
										), true);

								$jsonResult = ApiRequestor::post(ApiRequestor::URL_SEARCH_HOTEL, $xmlRequest);
								//echo $jsonResult;
								//Yii::app()->end();
									//print_r($jsonResult);
									$hotelResponse = json_decode($jsonResult,TRUE);

									$hotelList = array();
									if(isset($hotelResponse['SearchHotel_Response']['Hotel'])){
											if(isset($hotelResponse['SearchHotel_Response']['Hotel']['@attributes'])) {
													$hotelList = array(
															$hotelResponse['SearchHotel_Response']['Hotel']
													);
											} else {
													$hotelList = $hotelResponse['SearchHotel_Response']['Hotel'];
											}
									}
								$jsonResult = ApiRequestor::post(ApiRequestor::URL_SEARCH_HOTEL, $xmlRequest);
								$hotelResponse = json_decode($jsonResult,TRUE);
								//echo "string12";
								//echo $AdultNum;
								//echo $xmlRequest;
								//echo '<pre>';
								//var_dump($hotelResponse);
								//print_r($hotelResponse);
								//echo '</pre>';
								//Yii::app()->end();
								$hotelList = array();
								if(isset($hotelResponse['SearchHotel_Response']['Hotel'])){
										if(isset($hotelResponse['SearchHotel_Response']['Hotel']['@attributes'])) {
												$hotelList = array(
														$hotelResponse['SearchHotel_Response']['Hotel']
												);
										} else {
												$hotelList = $hotelResponse['SearchHotel_Response']['Hotel'];
										}
								}

								// @todo request ke mg dan insert ke tghsearchprice
								$mSession = new Searchsession();
								$mSession->nationalities = $nationalities;
								$mSession->currency = $currency;
								$mSession->location_code = $city;
								$mSession->location_type = $location_type;
								$mSession->check_in = $check_in;
								$mSession->check_out = $check_out;
								$mSession->jumlah_kamar = $NumRooms;
								$mSession->jumlah_tamu = $AdultNum;
								$mSession->update_dt = $now;
								$mSession->save(false);

								$sessionId = $mSession->session_id;

								foreach ($hotelList as $listHotel) {
//                        var_dump($listHotel);
//                        break;
										$HotelId = $listHotel['@attributes']['HotelId'];
										$HotelName = $listHotel['@attributes']['HotelName'];
										//$MarketName = $listHotel['@attributes']['MarketName'];
										$location_code = $city;
										$currency = $listHotel['@attributes']['Currency'];
										$rating = $listHotel['@attributes']['Rating'];
										//$avail = $listHotel['@attributes']['avail'];
										$avail = ($listHotel['@attributes']['avail'] == 'True' ? 1 : 0);

										if (isset($listHotel['RoomCateg']['@attributes'])) {
												$listHotel['RoomCateg']= array(0=>$listHotel['RoomCateg']);
										}
										foreach ($listHotel['RoomCateg'] as $key => $category) {
											$roomcateg_id = $category['@attributes']['Code'];
											$roomcateg_name = $category['@attributes']['Name'];
											$roomcateg_net_price = $category['@attributes']['NetPrice'];
											$roomcateg_gross_price = $category['@attributes']['GrossPrice'];
											$roomcateg_comm_price = $category['@attributes']['CommPrice'];
											$roomcateg_price = $category['@attributes']['Price'];
											$roomcateg_BFType = $category['@attributes']['BFType'];
											$roomtype_name = $category['RoomType']['@attributes']['TypeName'];
											$roomtype_numrooms = $category['RoomType']['@attributes']['NumRooms'];
											$roomtype_totalprice = $category['RoomType']['@attributes']['TotalPrice'];
											$roomtype_avrNightPrice = $category['RoomType']['@attributes']['avrNightPrice'];
											$roomtype_RTGrossPrice = $category['RoomType']['@attributes']['RTGrossPrice'];
											$roomtype_RTCommPrice = $category['RoomType']['@attributes']['RTCommPrice'];
											$roomtype_RTNetPrice = $category['RoomType']['@attributes']['RTNetPrice'];
											if(!empty($category['RoomType']['Rate']['RoomRate']['RoomSeq'])){
												/*if(!empty($category['RoomType']['Rate']['RoomRate']['RoomSeq']['@attributes'])){
													$AdultNums = $category['RoomType']['Rate']['RoomRate']['RoomSeq']['@attributes']['AdultNum'];
													$ChildNums = $category['RoomType']['Rate']['RoomRate']['RoomSeq']['@attributes']['ChildNum'];
												}*/
												if (isset($category['RoomType']['Rate']['RoomRate']['RoomSeq']['@attributes'])) {
														$category['RoomType']['Rate']['RoomRate']['RoomSeq']= array(0=>$category['RoomType']['Rate']['RoomRate']['RoomSeq']);
														/*echo "<pre>";
														print_r($category['RoomType']['Rate']['RoomRate']['RoomSeq']);
														echo "</pre>";*/
												}
												foreach ($category['RoomType']['Rate']['RoomRate']['RoomSeq'] as $roomrt) {
													//echo "trace3";
													$AdultNums = $roomrt['@attributes']['AdultNum'];
													$ChildNums = $roomrt['@attributes']['ChildNum'];
													$RoomPrice = $roomrt['@attributes']['RoomPrice'];
													//echo "string";
												}
											}
											if(!empty($category['RoomType']['Rate']['RoomRateInfo']['Promotion'])){
												if (isset($category['RoomType']['Rate']['RoomRateInfo'])) {
														$category['RoomType']['Rate']['RoomRateInfo']= array(0=>$category['RoomType']['Rate']['RoomRateInfo']);
												}
												foreach ($category['RoomType']['Rate']['RoomRateInfo'] as $romrate) {
													$promo_name = $romrate['Promotion']['@attributes']['Name'];
													$promo_value = $romrate['Promotion']['@attributes']['Value'];
													$promo_code = $romrate['Promotion']['@attributes']['PromoCode'];
												}
												/*$promo_name = $category['RoomType']['Rate']['RoomRateInfo']['Promotion']['@attributes']['Name'];
												$promo_value = $category['RoomType']['Rate']['RoomRateInfo']['Promotion']['@attributes']['Value'];
												$promo_code = $category['RoomType']['Rate']['RoomRateInfo']['Promotion']['@attributes']['PromoCode'];*/
											}
											if($NumRooms>1 || $duration>1){
												//echo "string kamar";
												//echo sizeof($hotelList);

												if (isset($category['RoomType']['Rate']['RoomRate'])) {
														$category['RoomType']['Rate'] = array(0=>$category['RoomType']['Rate']);
														/*echo "<pre>";
														print_r($category['RoomType']['Rate']['RoomRate']['RoomSeq']);
														echo "</pre>";*/
												}
												foreach ($category['RoomType']['Rate'] as $key => $valueroom) {
													$rtc = sizeof($valueroom);
													//echo $valueroom['RoomRate']['RoomSeq'][0]['@attributes']['RoomPrice'];
													$rpc = sizeof($valueroom['RoomRate']['RoomSeq']);
													/*echo "<pre>";
													print_r($valueroom);
													echo "</pre>";*/
													$promo_name='';
													$promo_value='';
													$promo_code='';
													/*$promo_name = $valueroom['RoomRateInfo']['Promotion']['@attributes']['Name'];
													$promo_value = $valueroom['RoomRateInfo']['Promotion']['@attributes']['Value'];
													$promo_code = $valueroom['RoomRateInfo']['Promotion']['@attributes']['PromoCode'];*/
													if($rpc>1){
														for($rp=0;$rp<$rpc;$rp++){
																$RoomPrice = $valueroom['RoomRate']['RoomSeq'][$rp]['@attributes']['RoomPrice'];
																		if ($roomtype_numrooms >= $NumRooms) {

																			/*echo ("INSERT INTO tghsearchprice
																				(session_id,property_cd,property_name,avail,
																				check_in,check_out,roomcateg_id,roomcateg_name,
																				net_price,gross_price,comm_price,price,BFType,
																				RoomType,numrooms,TotalPrice,avrNightPrice,RTGrossPrice,
																				RTCommPrice,RTNetPrice,promo_name,promo_value,promo_code,update_dt
																				)
																				VALUES
																				((SELECT session_id from tghsearchsession ORDER BY session_id DESC limit 0,1)
																				,'$HotelId','$HotelName','$avail'
																				,'$check_in','$check_out','$roomcateg_id','$roomcateg_name'
																				,'$roomcateg_net_price','$roomcateg_gross_price','$roomcateg_comm_price','$roomcateg_price','$roomcateg_BFType'
																				,'$roomtype_name','$roomtype_numrooms','$roomtype_totalprice','$roomtype_avrNightPrice','$roomtype_RTGrossPrice'
																				,'$roomtype_RTCommPrice','$roomtype_RTNetPrice','$promo_name','$promo_value','$promo_code','$now'
																			);");
																			*/
																			DAO::executeSql("INSERT INTO tghsearchprice
																				(session_id,property_cd,property_name,avail,
																				check_in,check_out,roomcateg_id,roomcateg_name,
																				net_price,gross_price,comm_price,price,BFType,
																				RoomType,numrooms,TotalPrice,avrNightPrice,RTGrossPrice,
																				RTCommPrice,RTNetPrice,roomprice,promo_name,promo_value,promo_code,update_dt
																				)
																				VALUES
																				((SELECT session_id from tghsearchsession ORDER BY session_id DESC limit 0,1)
																				,'$HotelId','$HotelName','$avail'
																				,'$check_in','$check_out','$roomcateg_id','$roomcateg_name'
																				,'$roomcateg_net_price','$roomcateg_gross_price','$roomcateg_comm_price','$roomcateg_price','$roomcateg_BFType'
																				,'$roomtype_name','$roomtype_numrooms','$roomtype_totalprice','$roomtype_avrNightPrice','$roomtype_RTGrossPrice'
																				,'$roomtype_RTCommPrice','$roomtype_RTNetPrice','$RoomPrice','$promo_name','$promo_value','$promo_code','$now'
																			);");
																	}
															}
													}
												}
												/*if($duration==1){
													$rtc = 1;
												}
												else{
													$rtc = sizeof($category['RoomType']['Rate']);
												}*/

												//echo $rtc;
											}
											else{
													if ($roomtype_numrooms >= $NumRooms) {

														/*echo ("INSERT INTO tghsearchprice
															(session_id,property_cd,property_name,avail,
															check_in,check_out,roomcateg_id,roomcateg_name,
															net_price,gross_price,comm_price,price,BFType,
															RoomType,numrooms,TotalPrice,avrNightPrice,RTGrossPrice,
															RTCommPrice,RTNetPrice,promo_name,promo_value,promo_code,update_dt
															)
															VALUES
															((SELECT session_id from tghsearchsession ORDER BY session_id DESC limit 0,1)
															,'$HotelId','$HotelName','$avail'
															,'$check_in','$check_out','$roomcateg_id','$roomcateg_name'
															,'$roomcateg_net_price','$roomcateg_gross_price','$roomcateg_comm_price','$roomcateg_price','$roomcateg_BFType'
															,'$roomtype_name','$roomtype_numrooms','$roomtype_totalprice','$roomtype_avrNightPrice','$roomtype_RTGrossPrice'
															,'$roomtype_RTCommPrice','$roomtype_RTNetPrice','$promo_name','$promo_value','$promo_code','$now'
														);");
														*/
														DAO::executeSql("INSERT INTO tghsearchprice
															(session_id,property_cd,property_name,avail,
															check_in,check_out,roomcateg_id,roomcateg_name,
															net_price,gross_price,comm_price,price,BFType,
															RoomType,numrooms,TotalPrice,avrNightPrice,RTGrossPrice,
															RTCommPrice,RTNetPrice,roomprice,promo_name,promo_value,promo_code,update_dt
															)
															VALUES
															((SELECT session_id from tghsearchsession ORDER BY session_id DESC limit 0,1)
															,'$HotelId','$HotelName','$avail'
															,'$check_in','$check_out','$roomcateg_id','$roomcateg_name'
															,'$roomcateg_net_price','$roomcateg_gross_price','$roomcateg_comm_price','$roomcateg_price','$roomcateg_BFType'
															,'$roomtype_name','$roomtype_numrooms','$roomtype_totalprice','$roomtype_avrNightPrice','$roomtype_RTGrossPrice'
															,'$roomtype_RTCommPrice','$roomtype_RTNetPrice','$RoomPrice','$promo_name','$promo_value','$promo_code','$now'
														);");
												}
											}


												}
											}
						}
            #select data searchsession
        }
        /*$this->render('form_searchsession',array(
                'hotelCode'=>$hotelCode
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

	public function actionCurrentuser($email,$password)
	{
		//$model=new Tghproperty('search');
		//$model->unsetAttributes();  // clear any default values
		//$model=Users::model()->findByPk($id);
		$pass=Encryption::encrypt($password);
	/*	echo ("SELECT fullname, email,phoneno,password
																						FROM tghusers where email='".$email."' and password='".$pass."'");;
*/
		$model = DAO::queryAllSql("SELECT fullname, email,phoneno,password
																						FROM tghusers where email='".$email."' and password='".$pass."'");
		//print_r($model);
		if(!empty($model)){
			$response = new Resultec();
			$response->fullName = $model[0]['fullname'];
			$response->email = $model[0]['email'];
			$response->phoneno = $model[0]['phoneno'];
			$response->password = $model[0]['password'];
			$response->result = 'OK';
			$response->message = 'Create successful';
			header('Content-Type: application/json');
			echo json_encode($response);
			Yii::app()->end();
		}
		/*else{
			$response = new Resultec();
			$response->result = 'NOK';
			$response->message = 'Create Failed';
			header('Content-Type: application/json');
			echo json_encode($response);
			Yii::app()->end();
		}*/

		/*if(isset($_GET['Tghproperty']))
			$model->attributes=$_GET['Tghproperty'];

		$this->render('admin',array(
			'model'=>$model,
		));*/
	}

	public function actionAutouser($fullname)
	{

		$hotels = DAO::queryAllSql("SELECT fullname as name, email,phoneno as phone
																						FROM tghusers
																						WHERE fullname like '".$fullname."%'");
		$return = array();
		foreach ($hotels as $key => $value) {
				$return[] = array('nama'=>$value['name'], 'email'=>$value['email'], 'phone'=>$value['phone']);
		}

		echo json_encode($return);
	}

	public function actionViewhoteldetail($hotelCode,$checkIn,$checkOut,$AdultNum='1',$NumRooms='1', $national='WSASCN', $currency='IDR')
	{
		if(isset($_GET['hotelCode']))
		{
			//$hotelCode = $_POST['hotelCode'];
			$hotelCode = $_GET['hotelCode'];
			$flagAvail =1;
			//Yii::app()->end();
			if ($NumRooms > 1) {
					$avgAdultNumInRoom = ceil($AdultNum / $NumRooms);
					$arrRooms = array();
					for ($i = 0; $i < $NumRooms; $i++) {
							$arrRooms[] = array('numAdults' => $avgAdultNumInRoom);
							//echo "kamar isi";
							//echo $arrRooms[$i]['numAdults'];
					}
					$hitungkamar=count($arrRooms);
					for($kr=0;$kr<$hitungkamar;$kr++){
						if($arrRooms[$kr]['numAdults']==1){
							$RoomType = 'Single';
						}
						if($arrRooms[$kr]['numAdults']==2){
							$RoomType = 'Twin';
						}
						if($arrRooms[$kr]['numAdults']==3){
							$RoomType = 'Triple';
						}
						if($arrRooms[$kr]['numAdults']==4){
							$RoomType = 'Quad';
						}
						$hotels = DAO::queryAllSql("SELECT tghproperty.country_cd as country_cd,tghproperty.city_cd as city_cd,tghproperty.property_name as name,tghsearchprice.totalprice,tghsearchprice.roomprice,tghproperty.numberofstar as numberofstar,
								tghproperty.gmaps_latitude,tghproperty.gmaps_longitude,tghproperty.addressline1 as displayAddress,tghproperty.addressline1 as address,tghsearchprice.roomtype as RoomType,
								tghsearchprice.roomcateg_id as roomcateg_id,tghsearchprice.roomcateg_name as roomcateg_name,tghsearchprice.BFType as BFType,tghsearchprice.check_in as check_in,tghsearchprice.check_out as check_out
																										FROM tghsearchprice
																				INNER JOIN tghproperty
																										ON  tghproperty.property_cd = tghsearchprice.property_cd
																										WHERE tghsearchprice.property_cd ='".$hotelCode."'
																										AND tghsearchprice.check_in = '".$checkIn."'
																										AND tghsearchprice.check_out = '".$checkOut."'
																										AND tghsearchprice.roomtype = '".$RoomType."'
																										GROUP BY tghsearchprice.roomcateg_name,tghsearchprice.RoomType
																										");
					}

			}else{
						$arrRooms[] = array('numAdults' => $AdultNum);
						//echo "kamar isi";
						//echo $arrRooms[0]['numAdults'];
						if($arrRooms[0]['numAdults']==1){
							$RoomType = 'Single';
						}
						if($arrRooms[0]['numAdults']==2){
							$RoomType = 'Twin';
						}
						if($arrRooms[0]['numAdults']==3){
							$RoomType = 'Triple';
						}
						if($arrRooms[0]['numAdults']==4){
							$RoomType = 'Quad';
						}
						$hotels = DAO::queryAllSql("SELECT tghproperty.country_cd as country_cd,tghproperty.city_cd as city_cd,tghproperty.property_name as name,tghsearchprice.totalprice,tghsearchprice.roomprice,tghsearchprice.price,
								tghproperty.gmaps_latitude,tghproperty.gmaps_longitude,tghproperty.addressline1 as displayAddress,tghproperty.addressline1 as address,tghsearchprice.roomtype as RoomType,
								tghsearchprice.roomcateg_id as roomcateg_id,tghsearchprice.roomcateg_name as roomcateg_name,tghsearchprice.BFType as BFType,tghsearchprice.check_in as check_in,tghsearchprice.check_out as check_out
																										FROM tghsearchprice
																				INNER JOIN tghproperty
																										ON  tghproperty.property_cd = tghsearchprice.property_cd
																										WHERE tghsearchprice.property_cd ='".$hotelCode."'
																										AND tghsearchprice.check_in = '".$checkIn."'
																										AND tghsearchprice.check_out = '".$checkOut."'
																										AND tghsearchprice.roomtype = '".$RoomType."'
																										GROUP BY tghsearchprice.roomcateg_name,tghsearchprice.RoomType
																										");
			}
			if($NumRooms <= 1) {
					$arrRooms[] = array('numAdults' => $AdultNum);
						//echo "kamar isi";
						if($arrRooms[0]['numAdults']==1){
							$RoomType = 'Single';
						}
						if($arrRooms[0]['numAdults']==2){
							$RoomType = 'Twin';
						}
						if($arrRooms[0]['numAdults']==3){
							$RoomType = 'Triple';
						}
						if($arrRooms[0]['numAdults']==4){
							$RoomType = 'Quad';
						}
						//echo $arrRooms[0]['numAdults'];
					/*  echo ("SELECT tghproperty.country_cd as country_cd,tghproperty.city_cd as city_cd,tghproperty.property_name as name,tghsearchprice.roomprice,
								tghproperty.gmaps_latitude,tghproperty.gmaps_longitude,tghproperty.addressline1 as displayAddress,tghproperty.addressline1 as address,tghsearchprice.RoomType as RoomType,
								tghsearchprice.roomcateg_id as roomcateg_id,tghsearchprice.roomcateg_name as roomcateg_name,tghsearchprice.BFType as BFType,tghsearchprice.check_in as check_in,tghsearchprice.check_out as check_out
																										FROM tghsearchprice
																				INNER JOIN tghproperty
																										ON  tghproperty.property_cd = tghsearchprice.property_cd
																										WHERE tghsearchprice.property_cd ='".$hotelCode."'
																										AND tghsearchprice.check_in = '".$checkIn."'
																										AND tghsearchprice.check_out = '".$checkOut."'
																										GROUP BY tghsearchprice.roomcateg_name,tghsearchprice.RoomType
																										");*/
						$hotels = DAO::queryAllSql("SELECT tghproperty.country_cd as country_cd,tghproperty.city_cd as city_cd,tghproperty.property_name as name,tghsearchprice.totalprice,tghsearchprice.roomprice,tghsearchprice.price,
								tghproperty.gmaps_latitude,tghproperty.gmaps_longitude,tghproperty.addressline1 as displayAddress,tghproperty.addressline1 as address,tghsearchprice.RoomType as RoomType,
								tghsearchprice.roomcateg_id as roomcateg_id,tghsearchprice.roomcateg_name as roomcateg_name,tghsearchprice.BFType as BFType,tghsearchprice.check_in as check_in,tghsearchprice.check_out as check_out
																										FROM tghsearchprice
																				INNER JOIN tghproperty
																										ON  tghproperty.property_cd = tghsearchprice.property_cd
																										WHERE tghsearchprice.property_cd ='".$hotelCode."'
																										AND tghsearchprice.check_in = '".$checkIn."'
																										AND tghsearchprice.check_out = '".$checkOut."'
																										AND tghsearchprice.roomtype = '".$RoomType."'
																										GROUP BY tghsearchprice.roomcateg_name,tghsearchprice.RoomType
																										");
			}


			/*echo("SELECT tghproperty.country_cd as country_cd,tghproperty.city_cd as city_cd,tghproperty.property_name as name,tghsearchprice.roomprice,
					tghproperty.gmaps_latitude,tghproperty.gmaps_longitude,tghproperty.addressline1 as displayAddress,tghproperty.addressline1 as address,tghsearchprice.RoomType as RoomType,
					tghsearchprice.roomcateg_id as roomcateg_id,tghsearchprice.roomcateg_name as roomcateg_name,tghsearchprice.BFType as BFType,tghsearchprice.check_in as check_in,tghsearchprice.check_out as check_out
																							FROM tghsearchprice
																	INNER JOIN tghproperty
																							ON  tghproperty.property_cd = tghsearchprice.property_cd
																							WHERE tghsearchprice.property_cd ='".$hotelCode."'
																							AND tghsearchprice.check_in = '".$checkIn."'
																							AND tghsearchprice.check_out = '".$checkOut."'
																							GROUP BY tghsearchprice.roomcateg_name,tghsearchprice.RoomType
																							");
																							*/


			$countselect = count($hotels);
			//echo $countselect;
			for($c=0;$c<$countselect;$c++)
			{
				$destCountry = $hotels[$c]['country_cd'];
				$city = $hotels[$c]['city_cd'];
				$property_name = $hotels[$c]['name'];
			}



			$xmlRequest = $this->renderPartial('req_searchhotelionic'
						, array(
								'nationalities'=>$national,
								'currency'=>$currency,
								'destCountry'=>$destCountry,
								'city'=>$city,
								'hotelCode'=>$hotelCode,
								'rommCatCode'=>'',
								'checkIn'=>$checkIn,
								'checkOut'=>$checkOut,
								'AdultNum'=>$AdultNum,
								'NumRooms'=>$NumRooms,
								'arrRooms'=>$arrRooms,
								'flagAvail'=>$flagAvail
						), true);
				$jsonResult = ApiRequestor::post(ApiRequestor::URL_SEARCH_HOTEL, $xmlRequest);
				//echo $jsonResult;
				//Yii::app()->end();
					//print_r($jsonResult);
					$hotelResponse = json_decode($jsonResult,TRUE);

					$hotelList = array();
					if(isset($hotelResponse['SearchHotel_Response']['Hotel'])){
							if(isset($hotelResponse['SearchHotel_Response']['Hotel']['@attributes'])) {
									$hotelList = array(
											$hotelResponse['SearchHotel_Response']['Hotel']
									);
							} else {
									$hotelList = $hotelResponse['SearchHotel_Response']['Hotel'];
							}
					}
				//print_r($hotels);
				echo json_encode($hotels);
				Yii::app()->end();
				//echo json_encode($hotels);
		}
		/*$this->render('view_hoteldetails',array(
						'hotelCode'=>$hotelCode,
						'hotels'=>$hotels,
						'myJSON'=>$myJSON,
						'checkIn'=>$checkIn,
						'checkOut'=>$checkOut
		));*/
	}

	public function actionSearchsessiondetail($destCitycode,$checkin,$duration=null,$checkout,$AdultNum,$NumRooms)
	{
			$checkIn = substr($checkin,0,10);
		//$newDate = date("Y-m-d", strtotime($originalDate));

				if($duration!=null){
						$tambahhari = '+'.$duration. 'day';
						$newdate = strtotime ( $tambahhari , strtotime ( $checkIn ) ) ;
						$checkOut = date ( 'Y-m-d' , $newdate );
				}
				else{
						$checkOut=$_GET['checkout'];
						$checkOut=$checkout;
				}

				$hotels='';
				$code=3;
		if($code==2){
			/*echo ("SELECT tghsearchprice.property_cd,tghsearchprice.property_name,tghsearchprice.roomcateg_name as name,
			 tghsearchprice.price,tghsearchprice.check_in,tghsearchprice.check_out,tghsearchprice.roomcateg_id,
			 tghsearchprice.roomcateg_name,tghsearchprice.TotalPrice,tghsearchprice.BFType,tghsearchprice.RoomType,
			 tghmspropertyfeatures.features_name
																						FROM tghsearchprice
															LEFT JOIN tghpropertyfeatures
																					ON  tghpropertyfeatures.property_id = tghsearchprice.property_cd
															LEFT JOIN tghmspropertyfeatures
																					ON  tghmspropertyfeatures.prop_features_id = tghpropertyfeatures.prop_features_id
																						WHERE property_cd = '".$destCitycode."'
																						AND check_in = '".$checkIn."'
																						AND check_out = '".$checkOut."'
																						GROUP BY tghsearchprice.roomcateg_name
																						");*/
			/*echo ("SELECT tghsearchprice.property_cd,tghsearchprice.property_name,tghsearchprice.roomcateg_name as name,
			tghsearchprice.price,tghsearchprice.check_in,tghsearchprice.check_out,tghsearchprice.roomcateg_id,
			tghsearchprice.roomcateg_name,tghsearchprice.TotalPrice,tghsearchprice.BFType,tghsearchprice.RoomType
																					 FROM tghsearchprice
																				 WHERE property_cd = '".$destCitycode."'
																					 AND check_in = '".$checkIn."'
																					 AND check_out = '".$checkOut."'
																					 GROUP BY tghsearchprice.roomcateg_name,tghsearchprice.RoomType
																					 ");*/
			 /*echo ("SELECT tghproperty.country_cd as country_cd,tghproperty.city_cd as city_cd,tghproperty.property_name as name,tghsearchprice.price,
					 tghproperty.gmaps_latitude,tghproperty.gmaps_longitude,tghproperty.addressline1 as displayAddress,tghproperty.addressline1 as address,tghsearchprice.RoomType as RoomType,
					 tghsearchprice.roomcateg_id as roomcateg_id,tghsearchprice.roomcateg_name as roomcateg_name,tghsearchprice.BFType as BFType
																							 FROM tghsearchprice
																	 INNER JOIN tghproperty
																							 ON  tghproperty.property_cd = tghsearchprice.property_cd
																							 WHERE tghsearchprice.property_cd ='".$destCitycode."'
																							 AND tghsearchprice.check_in = '".$checkIn."'
																							 AND tghsearchprice.check_out = '".$checkOut."'
																							 GROUP BY tghsearchprice.roomcateg_name,tghsearchprice.RoomType
																							 ");*/
		 $hotels = DAO::queryAllSql("SELECT tghproperty.country_cd as country_cd,tghproperty.city_cd as city_cd,tghproperty.property_name as hotel_name,tghsearchprice.totalprice,tghsearchprice.price,
				 tghproperty.gmaps_latitude,tghproperty.gmaps_longitude,tghproperty.addressline1 as displayAddress,tghproperty.addressline1 as address,tghsearchprice.RoomType as RoomType,
				 tghsearchprice.roomcateg_id as roomcateg_id,tghsearchprice.roomcateg_name as name,tghsearchprice.BFType as BFType,tghsearchprice.totalprice,tghsearchprice.check_in as check_in,tghsearchprice.check_out as check_out
																						 FROM tghsearchprice
																 INNER JOIN tghproperty
																						 ON  tghproperty.property_cd = tghsearchprice.property_cd
																						 WHERE tghsearchprice.property_cd ='".$destCitycode."'
																						 AND tghsearchprice.check_in = '".$checkIn."'
																						 AND tghsearchprice.check_out = '".$checkOut."'
																						 GROUP BY tghsearchprice.roomcateg_name,tghsearchprice.RoomType
																						 ");
		}

		if($code==3){
			if ($NumRooms > 1) {
					$avgAdultNumInRoom = ceil($AdultNum / $NumRooms);
					$arrRooms = array();
					for ($i = 0; $i < $NumRooms; $i++) {
							$arrRooms[] = array('numAdults' => $avgAdultNumInRoom);
							//echo "kamar isi";
							//echo $arrRooms[$i]['numAdults'];
					}
					$hitungkamar=count($arrRooms);
					for($kr=0;$kr<$hitungkamar;$kr++){
						if($arrRooms[$kr]['numAdults']==1){
							$RoomType = 'Single';
						}
						if($arrRooms[$kr]['numAdults']==2){
							$RoomType = 'Twin';
						}
						if($arrRooms[$kr]['numAdults']==3){
							$RoomType = 'Triple';
						}
						if($arrRooms[$kr]['numAdults']==4){
							$RoomType = 'Quad';
						}
						$hotels = DAO::queryAllSql("SELECT tghproperty.country_cd as country_cd,tghproperty.city_cd as city_cd,tghproperty.property_name as name,tghsearchprice.totalprice,tghsearchprice.roomprice,
								tghproperty.gmaps_latitude,tghproperty.gmaps_longitude,tghproperty.addressline1 as displayAddress,tghproperty.addressline1 as address,tghsearchprice.roomtype as RoomType,
								tghsearchprice.roomcateg_id as roomcateg_id,tghsearchprice.roomcateg_name as roomcateg_name,tghsearchprice.BFType as BFType,tghsearchprice.check_in as check_in,tghsearchprice.check_out as check_out
																										FROM tghsearchprice
																				INNER JOIN tghproperty
																										ON  tghproperty.property_cd = tghsearchprice.property_cd
																										WHERE tghsearchprice.property_cd ='".$destCitycode."'
																										AND tghsearchprice.check_in = '".$checkIn."'
																										AND tghsearchprice.check_out = '".$checkOut."'
																										AND tghsearchprice.roomtype = '".$RoomType."'
																										GROUP BY tghsearchprice.roomcateg_name,tghsearchprice.RoomType
																										");
					}
				}
					else{
						$arrRooms[] = array('numAdults' => $AdultNum);
						//echo "kamar isi";
						//echo $arrRooms[0]['numAdults'];
						if($AdultNum==1){
							$RoomType = 'Single';
						}
						if($AdultNum==2){
							$RoomType = 'Twin';
						}
						if($AdultNum==3){
							$RoomType = 'Triple';
						}
						if($AdultNum==4){
							$RoomType = 'Quad';
						}
						$hotels = DAO::queryAllSql("SELECT tghproperty.country_cd as country_cd,tghproperty.city_cd as city_cd,tghproperty.property_name as name,tghsearchprice.totalprice,tghsearchprice.roomprice,
								tghproperty.gmaps_latitude,tghproperty.gmaps_longitude,tghproperty.addressline1 as displayAddress,tghproperty.addressline1 as address,tghsearchprice.roomtype as RoomType,
								tghsearchprice.roomcateg_id as roomcateg_id,tghsearchprice.roomcateg_name as roomcateg_name,tghsearchprice.BFType as BFType,tghsearchprice.check_in as check_in,tghsearchprice.check_out as check_out
																										FROM tghsearchprice
																				INNER JOIN tghproperty
																										ON  tghproperty.property_cd = tghsearchprice.property_cd
																										WHERE tghsearchprice.property_cd ='".$destCitycode."'
																										AND tghsearchprice.check_in = '".$checkIn."'
																										AND tghsearchprice.check_out = '".$checkOut."'
																										AND tghsearchprice.roomtype = '".$RoomType."'
																										GROUP BY tghsearchprice.roomcateg_name,tghsearchprice.RoomType
																										");
					}
		}
		$countselect = count($hotels);

		echo json_encode($hotels);
		/*$dataProvider=new CActiveDataProvider('Tghproperty');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));*/
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
