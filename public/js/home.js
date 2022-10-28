// Check what features are enabled for the demo
document.getElementById("callCenter").style.display = !demoSession.enableMoto ? "none" : "block";
document.getElementById("unifiedCommerce").style.display = !demoSession.enableEcom ? "none" : "block";
document.getElementById("hotelCheckin").style.display = !demoSession.enableHotelCheckin ? "none" : "block";
document.getElementById("downloadDemoHref").href = "/storage/demos/" + demoSession.merchantName + ".json";

// New create demo UI items
let demoType = demoSession.merchantVertical + "-" + demoSession.merchantSubtype;
document.getElementById("saasSubscriptions").style.display = (demoType === "digital-saas") ? "block" : "none";
document.getElementById("unifiedCommerce").style.display = (demoType === "retail-unified") ? "block" : "none";
document.getElementById("payAsYouGo-register").style.display = (demoType === "hotel-payasyougo") ? "block" : "none";
document.getElementById("payAsYouGo-interface").style.display = (demoType === "hotel-payasyougo") ? "block" : "none";