<Service_BookHotel>
    <?php $this->renderPartial('/layouts/req_agentlogin'); ?>
<BookHotel>
	<RefRsvnNo><?php echo $RsvnNo;?></RefRsvnNo>
	<OSRefNo><?php echo $random_number; ?></OSRefNo>
    <PaxPassport><?php echo $nationalities; ?></PaxPassport>
    <RPCurrency><?php echo $currency; ?></RPCurrency>

      <HotelList Seq="<?php echo $SeqNo; ?>" InternalCode="<?php echo $InternalCode; ?>" flagAvail="<?php echo $flagAvail; ?>">

      	<OrgHBId></OrgHBId>
    		<OrgResId></OrgResId>
    		<HotelId><?php echo $hotelCode; ?></HotelId>
    		<RequestDes>High floor, Close to the elevator</RequestDes>

    		<RoomCatg CatgId="<?php echo $CatgId; ?>" CatgName="<?php echo $CatgName; ?>" checkIn="<?php echo $checkIn; ?>" checkOut="<?php echo $checkOut; ?>" BFType="<?php echo $BFType; ?>">
          <?php for($sec=0;$sec<$NumRooms;$sec++){?>
          <RoomType Seq="<?php echo $sec+1; ?>" TypeName="<?php echo $RoomType; ?>" RQBedChild ="N" Price="<?php echo $price; ?>">
    				<AdultNum><?php echo $arrRooms[$sec]['numAdults']; ?></AdultNum>
    				<ChildAges>
    					<ChildAge></ChildAge>
    				</ChildAges>
    			</RoomType>
          <?php } ?>
    		</RoomCatg>

    	</HotelList>

</BookHotel>
</Service_BookHotel>
