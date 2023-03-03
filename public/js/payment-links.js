import { PayByLink } from './components/pay-by-link.js';

let initialEmail = window.demoSession.demoEmail ? window.demoSession.demoEmail : "";
let initialAmount = window.demoSession.checkoutAmount ? parseFloat(window.demoSession.checkoutAmount) * 100 : 4498;
let initialShopperReference = window.demoSession.shopperReference ? window.demoSession.shopperReference : "11223344556677";

function handleSendPaymentLink(e) {
    e.preventDefault();
    let theEmailOrPhone = $(this).parent().parent().prev().find('input').val();
    if (!theEmailOrPhone) {
        alert('Please enter an email address or phone in order to use this feature');
        return;
    }
    let dataObj = {
        "reference": uuidv4(),
        "countryCode": "GB",
        "merchantAccount": adyenConfig.merchantAccount,
        "amount": {
            "currency": "GBP",
            "value": initialAmount
        },
        "shopperReference": initialShopperReference,
        "merchantName": demoSession.merchantName
    };

    let newPbl = new PayByLink(dataObj);

    if (this.id === "emailPaymentLink") {
        newPbl.setData('shopperEmail', theEmailOrPhone);
        newPbl.sendLinkEmail().then(function(retData) {
            let url = retData.response.url;
            $('#payment-link').html(url);
            $('#payment-link-success').show();
            window.scrollTo(0,0);
        });
    } else if (this.id === "invoicePaymentLink") {
        newPbl.setData('shopperEmail', theEmailOrPhone);
        newPbl.sendLinkInvoice().then(function(retData) {
            let url = retData.response.url;
            $('#payment-link').html(url);
            $('#payment-link-success').show();
            window.scrollTo(0,0);
        });
    } else if (this.id === "smsPaymentLink") {
        newPbl.setData('shopperPhone', theEmailOrPhone);
        newPbl.sendLinkSMS().then(function(retData) {
            let url = retData.response.url;
            $('#payment-link').html(url);
            $('#payment-link-success').show();
            window.scrollTo(0,0);
        });
    } else if (this.id === "whatsappPaymentLink") {
        newPbl.setData('shopperPhone', theEmailOrPhone);
        newPbl.sendLinkWhatsapp().then(function(retData) {
            let url = retData.response.url;
            $('#payment-link').html(url);
            $('#payment-link-success').show();
            window.scrollTo(0,0);
        });
    }
};

// Event handlers
$(".shopperEmailField").val(initialEmail);
let elementsArray = document.querySelectorAll(".payment-link");
elementsArray.forEach(function(elem) {
    elem.addEventListener("click", handleSendPaymentLink);
});