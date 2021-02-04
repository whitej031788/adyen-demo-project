import { PayByLink } from './components/pay-by-link.js';
// demoSession global variable that is always available containing demo settings
$("#main-container").css("background-color", demoSession ? demoSession.brandColorOne : '');

function handleOnSubmit(state, component) {
  $('#card-payment-success').hide();
  $('#card-payment-error').hide();

  let dataObj = {
    "paymentMethod": state.data.paymentMethod,
    "reference": $('#reference').val(),
    "shopperInteraction": "Moto",
    "merchantAccount": merchantAccount,
    "amount": {
      "currency": $('#currency').val(),
      "value": $('#value').val() * 100
    }
  };

  $.ajax({
    url: '/api/adyen/makePayment',
    dataType: 'json',
    type: 'post',
    data: dataObj,
    success: function(retData, textStatus, jQxhr) {
      // Successful MOTO payment
      if (retData.resultCode == "Authorised") {
        $('#card-payment-success').show();
      } else { // Failed MOTO payment
        $('#card-payment-error').show();
      }
      window.scrollTo(0,0);
    },
    error: function(jqXhr, textStatus, errorThrown) {
      console.log(errorThrown);
    }
  });
};

function handleSendPaymentLink(e) {
  e.preventDefault();
  let dataObj = {
    "reference": $('#reference').val(),
    "shopperInteraction": "Moto",
    "countryCode": $('#countryCode').val(),
    "merchantAccount": merchantAccount,
    "amount": {
      "currency": $('#currency').val(),
      "value": $('#value').val() * 100
    },
    "shopperPhone": $('#shopperPhone').val(),
    "shopperEmail": $('#shopperEmail').val(),
    "merchantName": demoSession.merchantName
  };

  let newPbl = new PayByLink(dataObj);

  newPbl.sendLinkEmail().then(function(retData) {
    let url = retData.url;
    $('#payment-link').html(url);
    $('#payment-link-success').show();
    window.scrollTo(0,0);
  });
};

var configuration = {
  environment: "test", // When you're ready to accept live payments, change the value to one of our live environments https://docs.adyen.com/checkout/components-web#testing-your-integration.
  clientKey: "test_26ROBT3X3NDAXJW4KQPVMOOIUACJULB4", // Your client key. To find out how to generate one, see https://docs.adyen.com/development-resources/client-side-authentication. Web Components versions before 3.10.1 use originKey instead of clientKey.
  // The payment methods response that can be fetched using server side Java SDK, https://docs.adyen.com/checkout/components-web?tab=script_2#step-1-get-available-payment-methods, // The payment methods response returned in step 1.
  paymentMethodsResponse: {
    "groups": [{
      "name": "Credit Card",
      "types": ["visa", "mc", "amex"]
    }],
    "paymentMethods": [{
      "brands": ["visa", "mc", "amex"],
      "details": [{
        "key": "number",
        "type": "text"
      }, {
        "key": "expiryMonth",
        "type": "text"
      }, {
        "key": "expiryYear",
        "type": "text"
      }, {
        "key": "cvc",
        "type": "text"
      }, {
        "key": "holderName",
        "optional": true,
        "type": "text"
      }],
      "name": "Credit Card",
      "type": "scheme"
    }]
  },
  onSubmit: handleOnSubmit // Your function for handling the call centre agent submission event
};

var checkout = new AdyenCheckout(configuration);
var card = checkout.create('card', {showPayButton: true}).mount('#card-container');

// Event handlers
document.querySelector('.payment-link').addEventListener("click", handleSendPaymentLink);
