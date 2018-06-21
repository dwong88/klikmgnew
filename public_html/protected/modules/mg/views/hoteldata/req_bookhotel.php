<Service_BookHotel>
    <?php $this->renderPartial('/layouts/req_agentlogin'); ?>
<BookHotel>
	<RefRsvnNo></RefRsvnNo>
	<OSRefNo><?php echo $random_number; ?></OSRefNo>
	  <PaxPassport><?php echo ApiRequestor::PAX_PASSPORT; ?></PaxPassport>
    <?php for($sec=0;$sec<=$countselect;$sec++){?>
      <HotelList Seq="<?php echo $SeqNo; ?>" InternalCode="<?php echo $InternalCode; ?>" flagAvail="<?php echo $flagAvail; ?>">
    		<OrgHBId></OrgHBId>
    		<OrgResId></OrgResId>
    		<HotelId><?php echo $hotelCode; ?></HotelId>
    		<RequestDes>High floor, Close to the elevator</RequestDes>
    		<RoomCatg CatgId="<?php echo $CatgId; ?>" CatgName="<?php echo $CatgName; ?>" checkIn="2018-11-25" checkOut="2018-11-26" BFType="<?php echo $BFType; ?>">
    			<RoomType Seq="<?php echo $SeqNo; ?>" TypeName="<?php echo $RoomType; ?>" RQBedChild ="N" Price="<?php echo $price; ?>">
    				<AdultNum><?php echo $AdultNum; ?></AdultNum>
    				<ChildAges>
    					<ChildAge></ChildAge>
    				</ChildAges>
    			<PaxInformation>
    				<PaxInfo Id="1">Smith Peter, Mr.</PaxInfo>
    				<PaxInfo Id="2">Smith Nancy, Mrs.</PaxInfo>
    			</PaxInformation>
    			</RoomType>
    		</RoomCatg>
    	</HotelList>
    <?php } ?>
</BookHotel>
</Service_BookHotel>
