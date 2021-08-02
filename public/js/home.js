// Check what features are enabled for the demo
document.getElementById("callCenter").style.display = !demoSession.enableMoto ? "none" : "block";
document.getElementById("standardEcom").style.display = !demoSession.enableEcom ? "none" : "block";
document.getElementById("hotelCheckin").style.display = !demoSession.enableHotelCheckin ? "none" : "block";
