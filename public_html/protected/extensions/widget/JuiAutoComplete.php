<?php
Yii::import('zii.widgets.jui.CJuiAutoComplete');
class JuiAutoComplete extends CJuiAutoComplete
{
	/**
	 * 
	 * @var mixed CHtml::normalizeUrl() will be applied to this property to convert the property
	 */
	public $ajaxlink;
	
	/**
	 * 
	 * @var string value of the autocomplete field on first load.
	 */
	public $valuetext;
	
	public function init()
	{
		$this->options['minLength']=2;
		parent::init();
	}
}