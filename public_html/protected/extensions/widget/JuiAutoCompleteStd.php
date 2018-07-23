<?php
Yii::import('application.extensions.widget.JuiAutoComplete');
class JuiAutoCompleteStd extends JuiAutoComplete
{
	public $showNoteValue = true;
	
	public function run()
	{
		list($name,$id)=$this->resolveNameID();
		$id='at_'.$id;
	
		if(isset($this->htmlOptions['id']))
			$id=$this->htmlOptions['id'];
		else
			$this->htmlOptions['id']=$id;
		if(isset($this->htmlOptions['name']))
			$name=$this->htmlOptions['name'];
	
		$jxlink = CHtml::normalizeUrl($this->ajaxlink);
		$jsNotes = '';
		if($this->showNoteValue)
			$jsNotes = "$(\"#{$id}\").parent().find(\".note-autocomplete\").html(\"\");";
		$this->options['source']="js:function(request, response) {
									$.ajax({
										type: 'POST',
										url: '{$jxlink}',
										data: {'term': request.term},
										dataType: 'json',
										success: function(data) {
											$(\"#{$id}\").parent().find(\".hf-autocomplete\").val(\"\");
											{$jsNotes}
											response(data);
										},
									});
								}";
		if(!isset($this->options['select'])) {
			$jsNotes = '';
			if($this->showNoteValue)
				$jsNotes = '$(this).parent().find(".note-autocomplete").html(ui.item.value);';
			$this->options['select']="js:function(event, ui) {
												$(this).parent().find(\".hf-autocomplete\").val(ui.item.value);
												{$jsNotes}
												return false;
											}";
		}
		$this->options['focus']='js:function(event, ui) {
											$(this).val(ui.item.label);
											return false;
										}';
		if(!isset($this->htmlOptions['onfocus'])) {
			$this->htmlOptions['onfocus']='this.value=""; $(this).parent().find(".hf-autocomplete").val("");'.(($this->showNoteValue)? ' $(this).parent().find(".note-autocomplete").html("");' : '');
		}
		
		//Custom for 'CREATE' form.
		if(!isset($this->valuetext)) $this->valuetext = (isset($_POST[$id]) && $this->model->hasErrors())? $_POST[$id] : '';
		
		echo CHtml::textField($id,$this->valuetext,$this->htmlOptions);
		
		if($this->hasModel()) {
			echo CHtml::activeHiddenField($this->model, $this->attribute,array('class'=>'hf-autocomplete'));
			if($this->showNoteValue)
				echo '<span class="note-autocomplete">'.$this->model->{$this->attribute}.'</span>';
		} else {
			echo CHtml::hiddenField($name,$this->value,array('class'=>'hf-autocomplete'));
			if($this->showNoteValue)
				echo '<span class="note-autocomplete">'.$this->value.'</span>';
		}
	
		$options=CJavaScript::encode($this->options);
		Yii::app()->getClientScript()->registerScript(__CLASS__.'#'.$id,"jQuery('#{$id}').autocomplete($options).data('ui-autocomplete')._renderItem = function(ul, item) {return $( \"<li>\" ).append( $( \"<a>\" ).html( (typeof item.labelhtml != 'undefined')? item.labelhtml : item.label ) ).appendTo( ul );};");
	}
}