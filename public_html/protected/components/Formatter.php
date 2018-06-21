<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Extend the default yii framework of CFormatter.
 *
 */
class Formatter extends CFormatter {
    public $dateFormat='d M Y';
    public $datetimeFormat='d M Y, H:i';
    public $numberFormat=array('decimals'=>'2', 'decimalSeparator'=>'.', 'thousandSeparator'=>',');
    public $timeFormat='H:i';
    
    //put your code here
    public function formatDate($value)
    {
        if(!is_numeric($value)) $value=strtotime ($value);
        return date($this->dateFormat,$value);
    }

    public function formatDatetime($value)
    {
            if(!is_numeric($value)) $value=strtotime ($value);
            return date($this->datetimeFormat,$value);
    }
    
    public function formatTime($value)
    {
            if(!is_numeric($value)) $value=strtotime ($value);
            return date($this->timeFormat,$value);
    }
	
	public function formatNumber0($value)
	{
		return number_format($value,0,'.',',');
	}
}
?>
