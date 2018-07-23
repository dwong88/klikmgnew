<!--<form method="post" name="searchhotel-form" action="<?php echo Yii::app()->baseUrl; ?>/index.php?r=mg/hoteldata/Searchhotel">
      <input type="text" name="destCountry" placeholder="destCountry"><br>
      <input type="text" name="city" placeholder="city"><br>
      <input type="text" name="hotelCode" placeholder="hotelCode"><br>
      <input type="text" name="rommCatCode" placeholder="room Category Code"><br>
      <input type="text" name="checkIn" placeholder="2018-06-11"><br>
      <input type="text" name="checkOut" placeholder="2018-06-12"><br>
      <input type="text" name="AdultNum" placeholder="AdultNum"><br>
      <input type="text" name="ChildNum" placeholder="ChildNum"><br>
      <input type="text" name="RQBedChild" placeholder="RQBedChild"><br>
      <input type="text" name="ChildAge" placeholder="ChildAge"><br>
      <div style="margin-bottom:5px;">
               <button type="submit"  name="simpan" value="Search"   onClick="return validate();"> Search </button>
               <button onclick="goBack()">Go Back</button>
     </div>
</form>-->

<form method="post" name="searchhotel-form" action="<?php echo Yii::app()->baseUrl; ?>/index.php?r=mg/hoteldata/Searchhotel">
  <table border="1">
      <tr>
          <td width="20%"><label>Destination</label></td>
          <td><input type="text" name="city" placeholder="Destination"></td>
      </tr>
      <tr>
          <td><label>Check In</label></td>
          <td><input type="text" name="checkin" placeholder="2018-06-11"></td>
      </tr>
      <tr>
          <td><label>Duration</label></td>
          <td><select name='duration'>
            <?php
            for($dr=1;$dr<=30;$dr++)
            {
              echo "<option value='".$dr."' >".$dr."</option>";
            }
            ?>
          </select></td>
      </tr>
      <tr>
          <td>
          </td>
          <td>
            <label>Tamu</label>
                <select onchange="getval(this);" name='AdultNum'>
                <?php
                for($ad=1;$ad<=10;$ad++)
                {
                  echo "<option value='".$ad."' >".$ad."</option>";
                }
                ?>
            </select>
            <label>Kamar</label>
              <select name='room'>
                <?php
                for($km=1;$km<=10;$km++)
                {
                  echo "<option value='".$km."' >".$km."</option>";
                }
                ?>
            </select>
        </td>
      </tr>
      <tr>
          <td><button type="submit"  name="simpan" value="Search"   onClick="return validate();"> Search </button></td>
          <td><button onclick="goBack()">Go Back</button></td>
      </tr>
  </table>
</form>
<table border="1">
<tr><td>Search Result</td></tr>
    <?php
      echo "<tr><td width=20%>Destination</td><td>".$destCity."</td></tr>";
      echo "<tr><td>check_in</td><td>".$check_in."</td></tr>";
      echo "<tr><td>check_out</td><td>".$check_out."</td></tr>";
      echo "<tr><td>AdultNum.</td><td>".$AdultNum." People</td></tr>";
      echo "<tr><td>NumRooms</td><td>".$NumRooms." Room</td></tr>";
    ?>

</table>
<hr>
<table border="1">
    <tr>
      <th>Name Hotel</th>
      <th>Address</th>
      <th>Price</th>
      <th>Action</th>
    </tr>
    <?php
    //print_r($hotels);
    for($ht=0;$ht<$countselecthotels;$ht++)
    {
      echo "<tr>";
      echo "<td>".$hotels[$ht]['name']."</td>";
      echo "<td>".$hotels[$ht]['displayAddress']."</td>";
      echo "<td>".$hotels[$ht]['price']."</td>";
      echo "<td><a href='".CHtml::normalizeUrl(array('/mg/hoteldata/gethoteldetail&hotelCode='.$hotels[$ht]['hotelCode']))."'>Detail</a></td>";
      echo "<td><a href='".CHtml::normalizeUrl(array('/mg/hoteldata/Viewhoteldetail&hotelCode='.$hotels[$ht]['hotelCode'].'&checkIn='.$check_in.'&checkOut='.$check_out.'&AdultNum='.$AdultNum.'&NumRooms='.$NumRooms))."'>Rooms</a></td>";
      echo "<td><a href='".CHtml::normalizeUrl(array('/mg/hoteldata/ViewCancelPolicy&hotelCode='.$hotels[$ht]['hotelCode'].'&checkIn='.$check_in.'&checkOut='.$check_out))."'>Cancel Policy</a></td>";
      echo "</tr>";
    }
    ?>
</table>

<script>
function getval(sel)
{
  alert(sel.value);
}
</script>
