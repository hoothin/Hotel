<?php 
function getEnableRoomsNumByHotelid($hotelid, $startDate, $endDate){
	$hotel=new Hotel($hotelid);
	echo $hotel->getEnableRoomsNum($startDate, $endDate);
}

function getEnableRoomsNumByRoomid($roomid, $startDate, $endDate){
	$room=new Room($roomid);
	$room->initInfo();
	echo $room->getEnableRoomsNum($startDate, $endDate);
}
?>