<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />

	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/print.css" media="print" />
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/main.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/form.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/global.css" />

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/sitemenu.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/site.css" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body onLoad="buildHtmlTable('#excelDataTable')">

<div class="container" id="page">

	<div id="header">
		<div id="logo"><?php echo CHtml::encode(Yii::app()->name); ?></div>
	</div><!-- header -->

	<div id="mainmenu">
		<!-- Start PureCSSMenu.com MENU -->
		<ul class="pureCssMenu pureCssMenum">
			<?php if (Yii::app()->user->isGuest) { ?>
                <li class="pureCssMenui0"><a class="pureCssMenui0" href="<?php echo CHtml::normalizeUrl(array('/site/login'));?>">Login</a></li>
			<?php } else { ?>
                <li class="pureCssMenui0"><a class="pureCssMenui0" href="<?php echo CHtml::normalizeUrl(array('/mg/hoteldata/Searchhotel'));?>">Search Hotel</a></li>
                <!--<li class="pureCssMenui0"><a class="pureCssMenui0" href="<?php echo CHtml::normalizeUrl(array('/mg/hoteldata/gethoteldetail'));?>">Get Hotel Detail</a></li>
                <li class="pureCssMenui0"><a class="pureCssMenui0" href="<?php echo CHtml::normalizeUrl(array('/mg/hoteldata/ViewCancelPolicy'));?>">View Cancel Policy</a></li>
                <li class="pureCssMenui0"><a class="pureCssMenui0" href="<?php echo CHtml::normalizeUrl(array('/mg/hoteldata/BookHoteltest'));?>">Book Hotel</a></li>
                <li class="pureCssMenui0"><a class="pureCssMenui0" href="<?php echo CHtml::normalizeUrl(array('/mg/hoteldata/Getrsvninfo'));?>">RSVN Info</a></li>
                <li class="pureCssMenui0"><a class="pureCssMenui0" href="<?php echo CHtml::normalizeUrl(array('/mg/hoteldata/AcceptBooking'));?>">Accept Booking</a></li>-->
                <li class="pureCssMenui0"><a class="pureCssMenui0" href="<?php echo CHtml::normalizeUrl(array('/mg/bookmg/admin'));?>">My Booking</a></li>
                <li class="pureCssMenui0"><a class="pureCssMenui0" href="<?php echo CHtml::normalizeUrl(array('/mg/hoteldata/Getcancelpolicy'));?>">Get Cancel Policy</a></li>
                <!--<li class="pureCssMenui0"><a class="pureCssMenui0" href="<?php echo CHtml::normalizeUrl(array('/mg/hoteldata/AmendHotel'));?>">Amend Hotel</a></li>
                <li class="pureCssMenui0"><a class="pureCssMenui0" href="<?php echo CHtml::normalizeUrl(array('/mg/hoteldata/Cancelrsvn'));?>">Cancel RSVN</a></li>-->
                <li class="pureCssMenui0"><a class="pureCssMenui0" href="<?php echo CHtml::normalizeUrl(array('/site/logout'));?>">Logout (<?php echo Yii::app()->user->name; ?>)</a></li>
			<?php } ?>
		</ul>
		<div class="clear"></div>
		<!-- End PureCSSMenu.com MENU -->
	</div><!-- mainmenu -->

	<?php if(isset($this->breadcrumbs)):?>
		<?php $this->widget('zii.widgets.CBreadcrumbs', array(
			'links'=>$this->breadcrumbs,
			'homeLink'=>CHtml::link('Home', Yii::app()->homeUrl),
		)); ?><!-- breadcrumbs -->
	<?php endif?>

	<?php echo $content; ?>

	<div class="clear"></div>
	<div id="footer">
		Copyright &copy; <?php echo date('Y'); ?> by Developer Team.<br/>
		All Rights Reserved.<br/>
	</div><!-- footer -->

</div><!-- page -->

</body>
</html>
