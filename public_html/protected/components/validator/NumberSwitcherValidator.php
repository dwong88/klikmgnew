<?php
/**
 *
 * @author Tan Wiyanto
 */
class NumberSwitcherValidator extends CValidator
{
    /**
     * Validates the attribute of the object.
     * @param CModel the object being validated
     * @param string the attribute being validated
     */
    protected function validateAttribute($object,$attribute)
    {
    	if(is_array($object->$attribute)) {
    		$temp = array();
			foreach ($object->$attribute as $key => $value) {
				$temp[$key] = str_replace(",","",strval(trim($value)));
			}
			$object->$attribute = $temp;
    	} else {
    		$value = str_replace(",","",strval(trim($object->$attribute)));
        	$object->$attribute = $value;
    	}
    }
}