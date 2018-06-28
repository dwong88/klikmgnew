<Service_BookHotel>
    <?php $this->renderPartial('/layouts/req_agentlogin'); ?>
<BookHotel>
	<RefRsvnNo></RefRsvnNo>
	<OSRefNo>9923012912395</OSRefNo>
	  <PaxPassport><?php echo ApiRequestor::PAX_PASSPORT; ?></PaxPassport>
    <RPCurrency>IDR</RPCurrency>
    <HotelList Seq="<?php echo $SeqNo; ?>" InternalCode="<?php echo $InternalCode; ?>" flagAvail="<?php echo $flagAvail; ?>">
		<OrgHBId></OrgHBId>
		<OrgResId></OrgResId>
		<HotelId><?php echo $hotelCode; ?></HotelId>
		<RequestDes>High floor, Close to the elevator</RequestDes>
		<RoomCatg CatgId="<?php echo $CatgId; ?>" CatgName="<?php echo $CatgName; ?>" checkIn="<?php echo $checkIn; ?>" checkOut="<?php echo $checkOut; ?>" BFType="<?php echo $BFType; ?>">
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
</BookHotel>
</Service_BookHotel>
