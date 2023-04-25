import {CheckoutApi} from './components/checkout-api.js';
import {HospitalityHelper} from './components/hospitality.js';

var hospitalityHelper;
var entireTotal;

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
      hospitalityHelper = new HospitalityHelper(getRegistrantInfo());
      hospitalityHelper.addRegistrant().then((result) => {
          handleResult(result);
          getTheRegistrants();
      },
      (error) => {
          console.error(error);
          handleResult(error, true);
      });
    }
}

function findIndividualById(e) {
  e.preventDefault();
  hospitalityHelper.setData('type', 'id');
  hospitalityHelper.findRegistrant('id').then((result) => {
    hospitalityHelper.setData('registrantId', result.id);
    buildLineItemsTable(result);
    $('#registrant-record').show();
    document.getElementById('registrant-record').scrollIntoView({
      behavior: 'auto',
      block: 'center',
      inline: 'center'
    });
  },
  (error) => {
      console.error(error);
      handleResult(error, true);
  });
}

function findIndividualByQr(e) {
  e.preventDefault();
  hospitalityHelper.setData('type', 'qr');
  hospitalityHelper.findRegistrant('qr').then((result) => {
    hospitalityHelper.setData('registrantId', result.id);
    buildLineItemsTable(result);
    $('#registrant-record').show();
    document.getElementById('registrant-record').scrollIntoView({
      behavior: 'auto',
      block: 'center',
      inline: 'center'
    });
  },
  (error) => {
      console.error(error);
      handleResult(error, true);
  });
}

function removeRegistrant(e) {
  e.preventDefault();
  $('#register-success').hide();
  $('#register-error').hide();
  hospitalityHelper.removeRegistrant().then((result) => {
      handleResult(result);
      getTheRegistrants();
  },
  (error) => {
      console.error(error);
      handleResult(error, true);
  });
}

function handleOnSubmit(state, component) {
    hospitalityHelper = new HospitalityHelper(getRegistrantInfo());
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

function buildRegistrantsTable(result) {
  $('#registrants-table > table > tbody').empty();
  for (let i = 0; i < result.length; i++) {
      let lineItemRow = "<tr id='registrant-" + result[i].id + "'>";
      lineItemRow += "<td>" + result[i].id + "</td>";
      lineItemRow += "<td>" + result[i].first_name + "</td>";
      lineItemRow += "<td>" + result[i].last_name + "</td>";
      lineItemRow += "<td>" + result[i].email + "</td>";
      lineItemRow += "<td>" + result[i].created_at + "</td>";
      lineItemRow += "<td><button data-item-id='" + result[i].id + "' class='btn btn-primary mt-1 txt-brand-color-one bkg-brand-color-two bdr-brand-color-two view-reg-record' type='button'>View Record</button></td>";
      lineItemRow += "</tr>";
      $('#registrants-table > table > tbody').append(lineItemRow);
  }
  let finalTotalRow = "</tr>";
  $('#registrants-table > table > tbody').append(finalTotalRow);
}

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

function getTheRegistrants() {
  hospitalityHelper = new HospitalityHelper(getRegistrantInfo());
  hospitalityHelper.getRegistrants().then((result) => {
    buildRegistrantsTable(result);
  });
}

function viewRegRecord() {
  var regId = $(this).attr("data-item-id");

  hospitalityHelper.getRegistrant(regId).then((result) => {
    hospitalityHelper.setData('registrantId', result.id);
    buildLineItemsTable(result);
    $('#registrant-record').show();
    document.getElementById('registrant-record').scrollIntoView({
      behavior: 'auto',
      block: 'center',
      inline: 'center'
    });
  });
}

// This is duplicated, need to make this better and a shared module function / view
function buildLineItemsTable(result) {
  $('#line-items-table > table > tbody').empty();
  // Here we want to populate the customer name, and build the table of their current bill
  entireTotal = 0;
  for (let i = 0; i < result.lineItems.length; i++) {
      let lineTotal = result.lineItems[i].quantity * result.lineItems[i].unit_price / 100;
      let lineItemRow = "<tr id='line-item-" + result.lineItems[i].id + "'>";
      lineItemRow += "<td>" + result.lineItems[i].id + "</td>";
      lineItemRow += "<td>" + result.lineItems[i].item_name + "</td>";
      lineItemRow += "<td>" + result.lineItems[i].store + "</td>";
      lineItemRow += "<td>" + result.lineItems[i].quantity + "</td>";
      lineItemRow += "<td>£" + parseFloat(result.lineItems[i].unit_price / 100).toFixed(2) + "</td>";
      lineItemRow += "<td>£" + parseFloat(lineTotal).toFixed(2) + "</td>";
      lineItemRow += "<td><button data-item-amount='" + lineTotal + "' data-item-id='" + result.lineItems[i].id + "' class='btn btn-primary txt-brand-color-one bkg-brand-color-two bdr-brand-color-two remove-line-item' type='button'>Remove</button></td>";
      lineItemRow += "</tr>";
      $('#line-items-table > table > tbody').append(lineItemRow);
      entireTotal += lineTotal;
  }
  let finalTotalRow = "<tr><td id='grand-total' class='font-weight-bold text-capitalize font-italic' colspan='5'>Grand Total: £" + parseFloat(entireTotal).toFixed(2) + "</td>";
  finalTotalRow += "<td><button id='show-receipt' class='btn btn-secondary txt-brand-color-one bkg-brand-color-two bdr-brand-color-two' type='button'>Show Receipt</button></td>";
  finalTotalRow += "<td><button id='pay-bill' class='btn btn-secondary txt-brand-color-one bkg-brand-color-two bdr-brand-color-two' type='button'>Pay Bill</button></td>";
  finalTotalRow += "</tr>";
  $('#line-items-table > table > tbody').append(finalTotalRow);
  $('#customer-name').text(result.customerName);
  $('#line-items-table').show();
}

function payFinalBill() {
  hospitalityHelper.setData('reference', uuidv4());
  hospitalityHelper.payFinalBill().then((result) => {
      console.log(result);
      if (result.response[1].SaleToPOIResponse.PaymentResponse.Response.Result === "Success") {
          $('#line-items-table > table > tbody').empty();
      } else {

      }
  });
}

function showFinalReceipt() {
  hospitalityHelper.showVirtualReceipt().then((result) => {
      console.log(result);
  });
}

function removeLineItem() {
  var lineItemId = $(this).attr("data-item-id");
  var lineItemAmount = $(this).attr("data-item-amount");
  hospitalityHelper.removeLineItem(lineItemId).then((result) => {
      $('#line-item-' + lineItemId).remove();
      entireTotal -= lineItemAmount;
      $('#grand-total').text("Grand Total: £" + parseFloat(entireTotal).toFixed(2));
  });
}
// End duplication
  
var checkout = await AdyenCheckout(configuration);
var card = checkout.create('card', {showPayButton: false}).mount('#card-container');

getTheRegistrants();

// Event handlers
document.getElementById('register-individual').addEventListener("submit", registerIndividual);
document.getElementById('enable-card').addEventListener("change", addRemoveCard);
$(document.body).on("click", ".view-reg-record", viewRegRecord);
document.getElementById('remove-registrant').addEventListener("click", removeRegistrant);
document.getElementById('find-individual-by-id').addEventListener("click", findIndividualById);
document.getElementById('find-individual-by-qr').addEventListener("click", findIndividualByQr);

// DUPLICATED
$(document.body).on("click", "#pay-bill", payFinalBill);
$(document.body).on("click", "#show-receipt", showFinalReceipt);
$(document.body).on("click", ".remove-line-item", removeLineItem);