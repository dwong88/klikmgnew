<form method="post" name="gethoteldetial-form" action="<?php echo Yii::app()->baseUrl; ?>/index.php?r=mg/hoteldata/gethoteldetail">
      <input type="text" name="hotelCode">
      <div style="margin-bottom:5px;">
               <button type="submit"  name="simpan" value="Simpan"   onClick="return validate();"> Submit </button>
               <button onclick="goBack()">Go Back</button>
     </div>
</form>


<?php
//print_r($jsonResult);
?>
<div style="overflow-x:auto;">
<table id="excelDataTable" border="1">
  </table>
  <table>
      <tr>
        <td>
            <?php echo 'Our Facility: '.$myFacility;?>
        </td>
      </tr>
  </table>
</div>

  <script>
var myList = [
  <?php echo $myJSON;?>
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
