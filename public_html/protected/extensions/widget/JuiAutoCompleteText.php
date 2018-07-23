<?php
Yii::import('application.extensions.widget.JuiAutoComplete');
class JuiAutoCompleteText extends JuiAutoComplete
{
	public function run()
	{
		list($name,$id)=$this->resolveNameID();

		if(isset($this->htmlOptions['id']))
			$id=$this->htmlOptions['id'];
		else
			$this->htmlOptions['id']=$id;
		if(isset($this->htmlOptions['name']))
			$name=$this->htmlOptions['name'];

		if($this->hasModel())
			echo CHtml::activeTextField($this->model,$this->attribute,$this->htmlOptions);
		else
			echo CHtml::textField($name,$this->value,$this->htmlOptions);

		$jxlink = CHtml::normalizeUrl($this->ajaxlink);
		$this->options['minLength'] = 1;
		$this->options['source']="js:function(request, response) {
										$.ajax({
											type: 'POST',
											url: '{$jxlink}',
											data: {'term': request.term},
											dataType: 'json',
											success: function(data) {
												response(data);
											},
										});
									}";
		$this->options['focus']='js:function(event, ui) {
											$(this).val(ui.item.label);
											return false;
										}';
		if(!isset($this->htmlOptions['onfocus'])) {
			$this->htmlOptions['onfocus']='this.value="";';
		}

		$options=CJavaScript::encode($this->options);
		Yii::app()->getClientScript()->registerScript(__CLASS__.'#'.$id,"jQuery('#{$id}').autocomplete($options);");
		Yii::app()->getClientScript()->registerScript(__CLASS__.'#'.$id,"jQuery('#{$id}').autocomplete($options).data('ui-autocomplete')._renderItem = function(ul, item) {return $( \"<li>\" ).append( $( \"<a>\" ).html( (typeof item.labelhtml != 'undefined')? item.labelhtml : item.label ) ).appendTo( ul );};");
	}
}