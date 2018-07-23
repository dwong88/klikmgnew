<!--<form method="post" name="viewcancelpolicy-form" action="<?php //echo Yii::app()->baseUrl; ?>/index.php?r=mg/hoteldata/ViewCancelPolicy">
      <input type="text" name="hotelCode" placeholder="hotelCode"><br>
      <input type="text" name="internalCode" placeholder="CL005"><br>
      <input type="text" name="checkIn" placeholder="2018-06-11"><br>
      <input type="text" name="checkOut" placeholder="2018-06-12"><br>
      <input type="text" name="SeqNo" placeholder="1"><br>
      <input type="text" name="AdultNum" placeholder="AdultNum"><br>
      <input type="text" name="RoomType" placeholder="RoomType"><br>
      <input type="text" name="flagAvail" placeholder="True"><br>
      <div style="margin-bottom:5px;">
               <button type="submit"  name="simpan" value="Simpan"   onClick="return validate();"> View </button>
               <button onclick="goBack()">Go Back</button>
     </div>
</form>
-->

<div style="overflow-x:auto;">
<table id="excelDataTable" border="1">
  </table>
  <?php
    print_r($hotelList);
  ?>
</div>

  <script>
var myList = [
  <?php echo $hotelList;?>
];

// Builds the HTML Table out of myList.
function buildHtmlTable(selector) {
  var columns = addAllColumnHeaders(myList, selector);

  for (var i = 0; i < myList.length; i++) {
    var row$ = $('<tr/>');
    for (var colIndex = 0; colIndex < columns.length; colIndex++) {
      var cellValue = myList[i][columns[colIndex]];
      console.log(cellValue);
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
<?php
echo $jsonResult;
?>
