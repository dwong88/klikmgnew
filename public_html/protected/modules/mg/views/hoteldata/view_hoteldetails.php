<?php
//print_r($jsonResult);
?>
<div style="overflow-x:auto;">
<table id="excelDataTable" border="1">
  </table>
  <table>
      <tr>
        <td>
            <?php //echo 'Our Facility: '.$myFacility;?>
        </td>
      </tr>
  </table>
</div>

  <script>
var myList = [
  <?php echo $myJSON;
  ?>
];

// Builds the HTML Table out of myList.
function buildHtmlTable(selector) {
  var columns = addAllColumnHeaders(myList, selector);

  for (var i = 0; i < myList.length; i++) {
    var row$ = $('<tr/>');
    for (var colIndex = 0; colIndex < columns.length; colIndex++) {
      var cellValue = myList[i][columns[colIndex]];
      if (cellValue == null) cellValue = "";
      row$.append($('<td/>').html(cellValue));
    }
    $(selector).append(row$);
  }
}

// Adds a header row to the table and returns the set of columns.
// Need to do union of keys from all records as some records may not contain
// all records.
function addAllColumnHeaders(myList, selector) {
  var columnSet = [];
  var headerTr$ = $('<tr/>');

  for (var i = 0; i < myList.length; i++) {
    var rowHash = myList[i];
    for (var key in rowHash) {
      if ($.inArray(key, columnSet) == -1) {
        columnSet.push(key);
        headerTr$.append($('<th/>').html(key));
      }
    }
  }
  $(selector).append(headerTr$);

  return columnSet;
}
</script>
<table border="1">
<tr><td>Search Result</td></tr>
    <?php
      echo "<tr><td width='20%'>AdultNum.</td><td>".$_GET['AdultNum']." People</td></tr>";
      echo "<tr><td>NumRooms.</td><td>".$_GET['NumRooms']." Room</td></tr>";
    ?>

</table>
<hr>
<table>
  <tr>
      <th>Hotel</th>
      <th>Room Category</th>
      <th>Breakfast</th>
      <th>Room Type</th>
      <th>Price</th>
  </tr>
<?php
    $countselect = count($hotels);
    for($c=0;$c<$countselect;$c++)
    {
      echo "<tr>";
      echo "<td>";
      echo $hotels[$c]['name'];
      echo "</td>";
      echo "<td>";
      echo $hotels[$c]['roomcateg_name'];
      echo "</td>";
      echo "<td>".$hotels[$c]['bftype']."</td>";
      echo "<td>";
      echo $hotels[$c]['RoomType'];
      echo "</td>";
      echo "<td>";
      echo Yii::app()->format->formatNumber($hotels[$c]['roomprice']);
//      $temp_data=array('checkIn'=>$_GET['checkIn'],'checkOut;'=>$_GET['checkOut'],'bftype'=>$hotels[$c]['bftype'],'AdultNum'=>$_GET['AdultNum'],'NumRooms'=>$_GET['NumRooms'],'roomcateg_id'=>$hotels[$c]['roomcateg_id']);
      //print_r($temp_data);
      echo "</td>";
      //$urlsubm=CHtml::normalizeUrl(array('/mg/hoteldata/BookHoteltest'));
      //echo CHtml::normalizeUrl(array('/mg/hoteldata/BookHoteltest'));
      $categid=$hotels[$c]['roomcateg_id'];
      $bftype=$hotels[$c]['bftype'];
      $roomty=$hotels[$c]['RoomType'];
      echo "<td><a href='#' id='myButton' onClick=saveQuantity('$categid','$bftype','$roomty')>Book</a></td>";
      //echo "<td><a href='".CHtml::normalizeUrl(array('/mg/hoteldata/BookHoteltest&hotelCode='.$_GET['hotelCode'].'&checkIn='.$_GET['checkIn'].'&checkOut='.$_GET['checkOut'].'&AdultNum='.$_GET['AdultNum'].'&NumRooms='.$_GET['NumRooms'].'&CatgId='.$hotels[$c]['roomcateg_id'].'&national='.$_GET['national'].'&bftype='.$hotels[$c]['bftype'].'&currency='.$_GET['currency'].'&RoomType='.$hotels[$c]['RoomType'].'&BFType='.$hotels[$c]['bftype']))."'>Book</a></td>";
      /*echo "<td>
      <form actions='/klikmgnew/public_html/index.php?r=mg/hoteldata/BookHoteltest/'>
        <input type =hidden name=hotelCode value='".$_GET['hotelCode']."'>
        <input type =hidden name=checkIn value='".$_GET['checkIn']."'>
        <input type =hidden name=checkOut value='".$_GET['checkOut']."'>
        <input type =hidden name=BFType value='".$hotels[$c]['BFType']."'>
        <input type =hidden name=AdultNum value='".$_GET['AdultNum']."'>
        <input type =hidden name=roomcateg_id value='".$hotels[$c]['roomcateg_id']."'>
        <input type =hidden name=roomcateg_name value='".$hotels[$c]['roomcateg_name']."'>
        <input type =hidden name=NumRooms value='".$_GET['NumRooms']."'>
        <input type =hidden name=RoomType value='".$hotels[$c]['RoomType']."'>
        <input type='submit' name='simpan' value='Simpan'>
      </form>
      </td>";*/
      $destCountry[$c] = $hotels[$c]['country_cd'];
      $city[$c] = $hotels[$c]['city_cd'];
      $hotels[$c]['name'];
      echo "</tr>";
    }
?>
</table>
<?php
//print_r($_SESSION);
//echo $_SESSION['_idghoursmghol__states']['employee_cd'];
?>
<script>
function saveQuantity(roomcateg_id,bftype,RoomType)
{
console.log(roomcateg_id)
  $.post("<?php echo CHtml::normalizeUrl(array('/mg/hoteldata/checkPrice'))?>",
			{
        hotelCode:'<?php echo $_GET['hotelCode']?>',
        checkIn: '<?php echo $_GET['checkIn']?>',
        checkOut:'<?php echo $_GET['checkOut']?>',
        AdultNum:<?php echo $_GET['AdultNum']?>,
        NumRooms:<?php echo $_GET['NumRooms']?>,
        CatgId:roomcateg_id,
        national:'<?php echo $_GET['national']?>',
        bftype:bftype,
        currency:'<?php echo $_GET['currency']?>',
        RoomType:RoomType
			},
			function(data) {
				//alert(data.message);
        if(data.message!='successful')
        {
          if (confirm('Price changed.')) {
              window.location.replace("<?php echo CHtml::normalizeUrl(array('/mg/hoteldata/BookHoteltest'))?>"+"&hotelCode="+data.hotelCode+"&checkIn="+data.checkIn+"&checkOut="+data.checkOut+"&AdultNum="+data.AdultNum+"&NumRooms="+data.NumRooms+"&CatgId="+data.CatgId+"&national="+data.national+"&currency="+data.currency+"&RoomType="+data.RoomType+"&bftype="+data.bftype);
          } else {
              window.location.replace("<?php echo CHtml::normalizeUrl(array('/mg/hoteldata/SearchHotel'))?>");
          }
        }
        else {
          window.location.replace("<?php echo CHtml::normalizeUrl(array('/mg/hoteldata/BookHoteltest'))?>"+"&hotelCode="+data.hotelCode+"&checkIn="+data.checkIn+"&checkOut="+data.checkOut+"&AdultNum="+data.AdultNum+"&NumRooms="+data.NumRooms+"&CatgId="+data.CatgId+"&national="+data.national+"&currency="+data.currency+"&RoomType="+data.RoomType+"&bftype="+data.bftype);
        }

			});
}
</script>
