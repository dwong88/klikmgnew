<?php
Yii::import('zii.widgets.jui.CJuiWidget');
/**
 * JuiTooltipOnImg show tooltip on image tag html.
 * 
 * 
 */
class JuiTooltipJs extends CJuiWidget
{	
	/**
	 * Run this widget.
	 * This method registers necessary javascript and renders the needed HTML code.
	 */
	public function run()
	{
		$id=$this->getId();

		$options=CJavaScript::encode($this->options);
		Yii::app()->getClientScript()->registerScript(__CLASS__.'#'.$id,"jQuery(document).tooltip($options);");
	}
}