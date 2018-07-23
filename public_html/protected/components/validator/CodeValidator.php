<?php
/**
 *
 * @author Tan Wiyanto
 */
class CodeValidator extends CValidator
{
    /**
     * Validates the attribute of the object.
     * @param CModel the object being validated
     * @param string the attribute being validated
     */
    protected function validateAttribute($object,$attribute)
    {
        $value = strtoupper($object->$attribute);
		if(ctype_alnum($value) == false)
			$this->addError($object,$attribute,'Code hanya dapat diinput alpha numeric (a-z dan 0-9)');
		else
			$object->$attribute = $value;
    }
}