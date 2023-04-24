import {DemoStorage} from "./components/demo-storage.js";

// Check what features are enabled for the demo
document.getElementById("callCenter").style.display = !demoSession.enableMoto ? "none" : "block";
document.getElementById("unifiedCommerce").style.display = !demoSession.enableEcom ? "none" : "block";
document.getElementById("hotelCheckin").style.display = !demoSession.enableHotelCheckin ? "none" : "block";
document.getElementById("downloadDemoHref").href = "/storage/demos/" + demoSession.merchantName + ".json";

// New create demo UI items
let demoType = (demoSession.merchantVertical || '') + "-" + (demoSession.merchantSubtype || '');
// If the demo type was basically not set, they did a manual setup, so don't use the below
// experience let journey code
if (demoType != "-") {
    document.getElementById("saasSubscriptions").style.display = (demoType === "digital-saas") ? "block" : "none";
    document.getElementById("unifiedCommerce").style.display = (demoType === "retail-unified") ? "block" : "none";
    document.getElementById("payAsYouGo-register").style.display = (demoType === "hotel-payasyougo") ? "block" : "none";
    document.getElementById("payAsYouGo-interface").style.display = (demoType === "hotel-payasyougo") ? "block" : "none";
}

function getShareUrl(e) {
    e.preventDefault();
    DemoStorage.getShareUrl().then(function (result) {
        console.log(result);
        $('#share-url-input').val(result);
        $('#share-url-modal').modal('show');
    });
}

document.getElementById('getShareUrl').addEventListener("click", getShareUrl);
$('#copy-url-button').bind('click', function() {
    var text_to_copy = document.getElementById("share-url-input").value;

    if (!navigator.clipboard){
        var input = document.querySelector('#share-url-input');
        input.setSelectionRange(0, input.value.length + 1);
        document.execCommand('copy');
    } else{
        navigator.clipboard.writeText(text_to_copy).then(
            function(){
                alert("Copied to clipboard"); // success 
            })
          .catch(
             function() {
                alert("Failed to copy to clipboard, please do manually"); // error
          });
    }    
});