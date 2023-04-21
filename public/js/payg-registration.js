import {CheckoutApi} from './components/checkout-api.js';
import {HospitalityHelper} from './components/hospitality.js';

function addRemoveCard(e) {
    if (e.target.checked) {
        $('#card-container').show();
    } else {
        $('#card-container').hide();
    }
}

function getRegistrantInfo() {
    return {
        firstName: $('#firstName').val(),
        lastName: $('#lastName').val(),
        email: $('#shopperEmail').val(),
        data: {
          amount: {
            currency: "GBP",
            value: 0
          },
          reference: uuidv4()
        }
    };
}

function handleResult(result, isError) {
    $('#register-success').hide();
    $('#register-error').hide();
    if (!isError) {
        $('#shopperReference').text(result.data.shopperReference);
        $('#emailResult').text(result.data.email);
        $('#nfcUid').text(result.data.nfcUid);
        $('#success-message').text(result.message);
        $('#register-success').show();
    } else {
        let msg = result.responseJSON.message;
        let data = result.responseJSON.data;
        $('#error-message').text(msg);
        $('#error-data').text(JSON.stringify(data));
        $('#register-error').show();
    }
}

function registerIndividual(e) {
    e.preventDefault();
    $('#register-success').hide();
    $('#register-error').hide();
    let isCardEnabled = $('#enable-card').is(":checked");
    if (isCardEnabled) {
        card.submit();
    } else {
        let hospitalityHelper = new HospitalityHelper(getRegistrantInfo());
        hospitalityHelper.addRegistrant().then((result) => {
            console.log(result);
            handleResult(result);
        },
        (error) => {
            console.error(error);
            handleResult(error, true);
        });
    }
}

function removeRegistrant(e) {
  e.preventDefault();
  $('#register-success').hide();
  $('#register-error').hide();
  let hospitalityHelper = new HospitalityHelper(getRegistrantInfo());
  hospitalityHelper.removeRegistrant().then((result) => {
      handleResult(result);
  },
  (error) => {
      console.error(error);
      handleResult(error, true);
  });
}

function handleOnSubmit(state, component) {
    let hospitalityHelper = new HospitalityHelper(getRegistrantInfo());
    hospitalityHelper.addRegistrant().then((result) => {
        hospitalityHelper.setData("id", result.data.id);
        let dataObj = {
            "paymentMethod": state.data.paymentMethod,
            "reference": uuidv4(),
            "shopperInteraction": "Ecommerce",
            "merchantAccount": adyenConfig.merchantAccount,
            "amount": {
              "currency": "GBP",
              "value": 0
            },
            "shopperReference": result.data.shopperReference,
            "recurringProcessingModel": "UnscheduledCardOnFile",
            "storePaymentMethod": true,
            "shopperEmail": $('#shopperEmail').val()
          };
      
          let checkoutApi = new CheckoutApi(dataObj);
      
          component.setStatus('loading');
          checkoutApi.submitPayment(state, component).then(function (result) {
            if (result.response.action) {
              component.handleAction(result.response.action);
            } else {
              let rdr = result.response.additionalData["recurring.recurringDetailReference"];
              let cardSummary = result.response.additionalData["cardSummary"];
              let cardType = result.response.additionalData["paymentMethod"];
              hospitalityHelper.updateRegistrant({psp_card_token: rdr, psp_card_type: cardType, psp_last_four: cardSummary}).then((result) => {
                  handleResult(result);
              });
            }
          });
    },
    (error) => {
        console.error(error);
        handleResult(error, true);
    });
  };

var configuration = {
    environment: "test", // When you're ready to accept live payments, change the value to one of our live environments https://docs.adyen.com/checkout/components-web#testing-your-integration.
    clientKey: adyenConfig.clientKey, // Your client key. To find out how to generate one, see https://docs.adyen.com/development-resources/client-side-authentication. Web Components versions before 3.10.1 use originKey instead of clientKey.
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
  
  var checkout = await AdyenCheckout(configuration);
  var card = checkout.create('card', {showPayButton: false}).mount('#card-container');
  
  // Event handlers
  document.getElementById('register-individual').addEventListener("submit", registerIndividual);
  document.getElementById('enable-card').addEventListener("change", addRemoveCard);
  document.getElementById('remove-registrant').addEventListener("click", removeRegistrant);