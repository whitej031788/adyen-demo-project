import { PayByLink } from './components/pay-by-link.js';
import { TerminalApi } from './components/terminal-api.js';

let pblDataObj = {
  "countryCode": "GB",
  "merchantAccount": merchantAccount,
  "merchantName": demoSession.merchantName,
  "reference": "YOUR_PAYMENT_REFERENCE",
  "shopperEmail": "jamie.white@adyen.com",
  "amount": {
    "value": 4200,
    "currency": "GBP"
  },
};

let newPbl = new PayByLink(pblDataObj);
let terminalApi = new TerminalApi(pblDataObj);

<<<<<<< HEAD
function createQR(){
  newPbl.getQRCode().then(function(qrCodeSvg) {
    $('#qr-code').append(qrCodeSvg);
  });
}

document.querySelector('#qr-button').addEventListener("click", createQR)
=======
newPbl.getQRCode().then(function(qrCodeSvg) {
  $('#qr-code').append(qrCodeSvg);
});

terminalApi.cloudApiRequest().then(function(result) {
  console.log(result);
});

Pusher.logToConsole = true;

let pusher = new Pusher('47e2eb4a3e296716c3fd', {
  cluster: 'eu'
});

var channel = pusher.subscribe('adyen-demo');

channel.bind('payment-success', function(data) {
  if (newPbl.data.reference == data.merchantReference) {
    alert(JSON.stringify(data));
  }
});
>>>>>>> ed6f88a7b5aad1450e51c8e301ce0fb6be906ac0
