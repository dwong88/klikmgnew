<?php
class Helper
{
	public static function showFlash()
	{
		Yii::app()->clientScript->registerScript(
			    '_myHideEffectFlash',
			    '$(".info").animate({opacity: 1.0}, 30000).fadeOut("slow");', // aslinya 3000
		CClientScript::POS_READY
		);
		
		foreach(Yii::app()->user->getFlashes() as $key => $message) :
			echo '<div class="flash-' . $key . ' info">' . $message . "</div>\n";
		endforeach;
	}
	
	public static function registerNumberField($selector)
	{
		Helper::registerJsKarlwei();
		Yii::app()->clientScript->registerScript(
					    '__registerNumberField',
					    "
					    $(document).on('focus','{$selector}', function() {
							this.value = Karlwei.helper.number.removeCommas(this.value);
						});
						$(document).on('blur','{$selector}', function() {
							this.value = Karlwei.helper.number.addCommas(this.value);
						});
					    $('{$selector}').each(function() {
							$(this).trigger('blur');
						});",
		CClientScript::POS_READY
		);
	}
	
	public static function registerJsKarlwei()
	{
		Yii::app()->getClientScript()->registerScriptFile(Yii::app()->baseUrl.'/js/karlwei.js');
	}
	
	public static function getGender($gender)
	{
		$result = '';
		switch($gender) {
			case 1: $result = 'Pria'; break;
			case 0: $result = 'Wanita'; break;
		}
		return $result;
	}
	
	public static function getMasterTypeName($mastercode, $tablename, $othercode='', $othertxt='') 
	{
		$result = "";
		if(!empty($mastercode)) 
		{
			if($mastercode==$othercode) $result = $othertxt;
			else {
				$master = Mastertype::model()->find('mastertype_code=:mcd AND table_name=:tnm',array(':mcd'=>$mastercode,':tnm'=>$tablename));
				if($master != null) $result = $master->mastertype_name;
			} 
		}
		
		return $result;
	}
	
	public static function terbilangID($satuan)
	{
		$huruf = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
    	if ($satuan < 12)
    		return " " . $huruf[intval($satuan)];
    	elseif ($satuan < 20)
    		return Helper::terbilangID($satuan - 10) . " Belas";
    	elseif ($satuan < 100)
    		return Helper::terbilangID($satuan / 10) . " Puluh" . Helper::terbilangID($satuan % 10);
    	elseif ($satuan < 200)
    		return " Seratus" . Helper::terbilangID($satuan - 100);
    	elseif ($satuan < 1000)
    		return Helper::terbilangID($satuan / 100) . " Ratus" . Helper::terbilangID($satuan % 100);
    	elseif ($satuan < 2000)
    		return " Seribu" . Helper::terbilangID($satuan - 1000);
    	elseif ($satuan < 1000000)
    		return Helper::terbilangID($satuan / 1000) . " Ribu" . Helper::terbilangID($satuan % 1000);
    	elseif ($satuan < 1000000000)
    		return Helper::terbilangID($satuan / 1000000) . " Juta" . Helper::terbilangID($satuan % 1000000);
    	elseif ($satuan < 1000000000000) {
    		$depan = floor($satuan / 1000000000);
    		return Helper::terbilangID($depan) . " Milyar" . Helper::terbilangID($satuan - (1000000000*$depan));
    	}
	}
	
	public static function terbilangEN($n)
	{
		if ($n < 0) return 'Minus ' . Helper::terbilangEN(-$n);
		else if ($n < 10) {
			switch ($n) {
				case 0: return 'Zero';
				case 1: return 'One';
				case 2: return 'Two';
				case 3: return 'Three';
				case 4: return 'Four';
				case 5: return 'Five';
				case 6: return 'Six';
				case 7: return 'Seven';
				case 8: return 'Eight';
				case 9: return 'Nine';
			}
		}
		else if ($n < 100) {
			$kepala = floor($n/10);
			$sisa = $n % 10;
			if ($kepala == 1) {
				if ($sisa == 0) return 'Ten';
				else if ($sisa == 1) return 'Eleven';
				else if ($sisa == 2) return 'Twelve';
				else if ($sisa == 3) return 'Thirteen';
				else if ($sisa == 5) return 'Fifteen';
				else if ($sisa == 8) return 'Eighteen';
				else return Helper::terbilangEN($sisa) . 'teen';
			}
			else if ($kepala == 2) $hasil = 'Twenty';
			else if ($kepala == 3) $hasil = 'Thirty';
			else if ($kepala == 5) $hasil = 'Fifty';
			else if ($kepala == 8) $hasil = 'Eighty';
			else $hasil = Helper::terbilangEN($kepala) . 'ty';
		}
		else if ($n < 1000) {
			$kepala = floor($n/100);
			$sisa = $n % 100;
			$hasil = Helper::terbilangEN($kepala) . ' Hundred';
		}
		else if ($n < 1000000) {
			$kepala = floor($n/1000);
			$sisa = $n % 1000;
			$hasil = Helper::terbilangEN($kepala) . ' Thousand';
		}
		else if ($n < 1000000000) {
			$kepala = floor($n/1000000);
			$sisa = $n % 1000000;
			$hasil = Helper::terbilangEN($kepala) . ' Million';
		}
		else if ($n < 1000000000000) {
			$kepala = floor($n/1000000000);
			$sisa = $n - (1000000000*$kepala);
			$hasil = Helper::terbilangEN($kepala) . ' Billion';
		}
		else return false;
	
		if ($sisa > 0) $hasil .= ' ' . Helper::terbilangEN($sisa);
		return $hasil;
	}
	
	public static function getListMonths() {
		return array('1'=>'January'
				,'2'=>'Febuary'
				,'3'=>'March'
				,'4'=>'April'
				,'5'=>'May'
				,'6'=>'June'
				,'7'=>'July'
				,'8'=>'Augustus'
				,'9'=>'September'
				,'10'=>'October'
				,'11'=>'November'
				,'12'=>'December'
		);
	}
	
	public static function getListYears() {
		$thn = '';
		$thisYear = date('Y');
		for($i=($thisYear-2);$i<=($thisYear+1);$i++) {
			$thn["$i"] = $i;
		}
			
		return $thn;
	}
	
	public static function getListDay() {
		$dt = array();
		for($i=1; $i<=31; $i++) {
			$dt[$i] = $i;
		}
		
		return $dt;
	}
	
	public static function gridAjaxAction($gridId, $confirmationStr, $afterAction=null) {
		if(is_string($confirmationStr))
			$confirmation="if(!confirm(".CJavaScript::encode($confirmationStr).")) return false;";
		else
			$confirmation='';

		if(Yii::app()->request->enableCsrfValidation)
		{
			$csrfTokenName = Yii::app()->request->csrfTokenName;
			$csrfToken = Yii::app()->request->csrfToken;
			$csrf = "\n\t\tdata:{ '$csrfTokenName':'$csrfToken' },";
		}
		else
			$csrf = '';

		if($afterAction===null)
			$afterAction='function(){}';

		$clickStr=<<<EOD
function() {
	$confirmation
	var th = this,
		afterAction = $afterAction;
	jQuery('#{$gridId}').yiiGridView('update', {
		type: 'POST',
		url: jQuery(this).attr('href'),$csrf
		success: function(data) {
			jQuery('#{$gridId}').yiiGridView('update');
			afterAction(th, true, data);
		},
		error: function(XHR) {
			return afterAction(th, false, XHR);
		}
	});
	return false;
}
EOD;

	return $clickStr;
	}
	
	public static function registerPrintExportGridView() {
		Yii::app()->clientScript->registerScript(
					    '__registerPrintExportGridView',
					    "
					    function getPrintTable(jGridView) {
							var jProcess = jGridView.clone();
							
							jProcess.find('.summary').remove();
							jProcess.find('.filters').remove();
							jProcess.find('.pager').remove();
							jProcess.find('.keys').remove();
							jProcess.find('.items').attr('border',1);
							jProcess.find('.items').attr('cellspacing',0);
							
							jProcess.find('a').each(function() {
								var jThis = $(this);
								jThis.parents('td:first, th:first').html(jThis.text());
							});
							
							return jProcess;
						}
						
						function exportTableToCSV(\$table, filename) {
							
					        var \$rows = \$table.find('tr:has(td), tr:has(th)'),
					
					            // Temporary delimiter characters unlikely to be typed by keyboard
					            // This is to avoid accidentally splitting the actual contents
					            tmpColDelim = String.fromCharCode(11), // vertical tab character
					            tmpRowDelim = String.fromCharCode(0), // null character
					
					            // actual delimiter characters for CSV format
					            colDelim = '\",\"',
					            rowDelim = '\"\\r\\n\"',
					
					            // Grab text from table into CSV formatted string
					            csv = '\"' + \$rows.map(function (i, row) {
					                var \$row = \$(row),
					                    \$cols = \$row.find('td:not(.no-print-csv), th:not(.no-print-csv)');
										
					                return \$cols.map(function (j, col) {
					                    var \$col = \$(col),
					                    	vhtml = \$col.html(),
					                    	text = '';
					                    	
					                    	\$col.html(vhtml.replace(\"&nbsp;\", \" \"));
					                        text = \$col.text();
					                        
					                    return text.replace(/\"/g, '\"\"'); // escape double quotes
					
					                }).get().join(tmpColDelim);
					
					            }).get().join(tmpRowDelim)
					                .split(tmpRowDelim).join(rowDelim)
					                .split(tmpColDelim).join(colDelim) + '\"',
					
					            // Data URI
					            csvData = 'data:application/csv;charset=utf-8,' + encodeURIComponent(csv);
					
					        $(this)
					            .attr({
					            'download': filename,
					                'href': csvData,
					                'target': '_blank'
					        });
					    }
					    ",
		CClientScript::POS_HEAD
		);
	}

	public static function putStringCSV($container, $strToPut, $type='str', $delimiter=',') {
		switch ($type) {
			case 'date':
				$strToPut = str_replace('-','',$strToPut);
				break;
			case 'str':
				$strToPut = '"'.trim($strToPut).'"';
				break;
			case 'void':
			case 'number':
				break;
		}
		
		return $container.(($container === '')? '' : $delimiter).$strToPut;
	}
	
	public function listStrToArray($delimiter = ',', $strList)
	{
		$arr = explode($delimiter,$strList);
		$result = array();
		foreach ($arr as $cArr) {
			$theValue = trim($cArr);
			$result[$theValue] = $theValue;
		}
		return $result;
	}

	// Generate random number max 9 digit integer.
	public static function getRandomNumber()
    {
        $rd = rand(1,5);
        $numbers = range(1, 300);
        for ($i = 1; $i <= $rd; $i++) {
            shuffle($numbers);
        }

        return $numbers[0].$numbers[1].$numbers[2];
    }
}

