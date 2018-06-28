<?php

/**
 * Status contains status constant and checking for all flow
*/
class Status
{
	public static $is_status     = array(0=>'No',1=>'Yes');
	public static $subscr_status = array(0=>'New',1=>'Rejected',2=>'Approved');
	public static $signcusto_status = array('0'=>'No','1'=>'Yes','2'=>'Para Pihak');
	public static $kontrak_status = array('0'=>'New','1'=>'On Going','2'=>'Pencairan','3'=>'Break Contract','4'=>'Rollover','5'=>'Rollover Split');
	public static $kontrak_action = array('1'=>'Rollover','2'=>'Break Kontrak');
	public static $komisi_source = array('1'=>'Kontrak Perjanjain Baru','2'=>'Break Kontrak');
	
	public static $sales_type = array('0'=>'Sales','1'=>'Branch Manager','2'=>'Referral 1','3'=>'Referral 2');
	public static $payintereston = array('0'=>'Every Month', '1'=>'End of Subscription');
	
	public static $penjaminType = array('COMPANY'=>'COMPANY', 'INDIVIDU'=>'INDIVIDU');
	
	public static $transaction_status = array(0=>'New', 1=>'Unposted', 2=>'Posted', 3=>'Void');
	
	const IS_DEFAULT_YES = 1;
	const IS_DEFAULT_NO = 0;
	
	const IS_STATUS_YES = 1;
	const IS_STATUS_NO  = 0;
	const SUBSCR_STATUS_NEW = 0;
	const SUBSCR_STATUS_REJECT = 1;
	const SUBSCR_STATUS_OK = 2;
	const SIGNCUSTO_NO = 0;
	const SIGNCUSTO_YES = 1;
	const SIGNCUSTO_PARAPIHAK = 2;
	
	const KONTRAK_NEW = 0;
	const KONTRAK_ONGOING = 1;
	const KONTRAK_FINISHED = 2;
	const KONTRAK_BREAKED = 3;
	const KONTRAK_ROLLOVER = 4;
	const KONTRAK_ROLLOVER_SPLIT = 5;
	
	const KONTRAKACTION_ROLLOVER = 1;
	const KONTRAKACTION_BREAK = 2;
	const KONTRAKACTION_PENCAIRAN = 3;
	
	const KOMISISOURCE_NEW = 1;
	const KOMISISOURCE_BREAK = 2;
	
	const SALESTYPE_SALES = 0;
	const SALESTYPE_BRANCH_MANAGER = 1;
	const SALESTYPE_REFERRAL1 = 2;
	const SALESTYPE_REFERRAL2 = 3;
	
	const PAYINTEREST_EVERYMONTH = 0;
	const PAYINTEREST_LASTMONTHONLY = 1;
	
	const TRANSACTION_NEW = 0;
	const TRANSACTION_UNPOSTED = 1;
	const TRANSACTION_POSTED = 2;
	const TRANSACTION_VOID = 3;
	
	public static function getSalesType($sts)
	{
		return Status::$sales_type[intval($sts)];
	}
	
	public static function getStatus($sts)
	{
		return Status::$is_status[intval($sts)];
	}
	
	public static function getSubscrStatus($sts)
	{
		return Status::$subscr_status[intval($sts)];
	}
	
	public static function getSigncustoStatus($sts)
	{
		return Status::$signcusto_status[intval($sts)];
	}
	
	public static function getKontrakstatus($sts)
	{
		return Status::$kontrak_status[intval($sts)];
	}
	
	public static function getKontrakaction($sts)
	{
		return Status::$kontrak_action[intval($sts)];
	}
	
	public static function getPayinterestOn($sts)
	{
		return Status::$payintereston[intval($sts)];
	}
	
	public static function getTransactionStatus($sts)
	{
		return Status::$transaction_status[intval($sts)];
	}
}