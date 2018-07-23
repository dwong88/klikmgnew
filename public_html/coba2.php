<?Php
$temp='';
for($i=0;$i<4;$i++){
  for($y=0;$y<=2;$y++){
    //echo $y;
      //echo $y.'<br>';
      $temp=$y;
      if($temp!=''){
          //echo "string";
          echo $i;
          echo $temp.'<br>';
          $temp='';
      }
  }
}
?>
