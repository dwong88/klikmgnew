<?php
/**
 *
 * @author Tan Wiyanto
 */
class DatePickerSwitcherValidator extends CValidator
{
    /**
     * Validates the attribute of the object.
     * @param CModel the object being validated
     * @param string the attribute being validated
     */
    protected function validateAttribute($object,$attribute)
    {
    	if(!empty($object->$attribute))
    	{
    		$var = explode(Yii::app()->params['datepick_separator'], $object->$attribute);
    		if(count($var) == 3)
    		{
    			$object->$attribute = $var[$this->getIndexFormat('yy')].'-'.$var[$this->getIndexFormat('mm')].'-'.$var[$this->getIndexFormat('dd')];
    		}
    	}
    	else
    	{
    		$object->$attribute = null;
    	}
    }
    
    private function getIndexFormat($format)
    {
    	$val = "";
    	switch($format)
    	{
    		case Yii::app()->params['datepick_1st']: $val = 0; break;
    		case Yii::app()->params['datepick_2nd']: $val = 1; break;
    		case Yii::app()->params['datepick_3rd']: $val = 2; break;
    	}
    	
    	return $val;
    }
}