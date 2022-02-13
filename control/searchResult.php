<?php 
function getEnableRoomsNumByHotelid($hotelid, $startDate, $endDate){
	$hotel=new Hotel($hotelid);
	echo $hotel->getEnableRoomsNum($startDate, $endDate);
}
?>