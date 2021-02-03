import { PayByLink } from './components/pay-by-link.js';

let dataObj = {
  "countryCode": "GB",
  "merchantAccount": merchantAccount,
  "reference": "YOUR_PAYMENT_REFERENCE",
  "amount": {
    "value": 4200,
    "currency": "GBP"
  },
};

$.ajax({
  url: '/api/adyen/getPaymentMethods',
  dataType: 'json',
  type: 'post',
  data: dataObj,
  success: function(retData, textStatus, jQxhr) {
    console.log(retData);
  },
  error: function(jqXhr, textStatus, errorThrown) {
    console.log(errorThrown);
  }
});

let newPbl = new PayByLink(dataObj);

newPbl.getQRCode().then(function(qrCodeSvg) {
  $('#qr-code').append(qrCodeSvg);
});
