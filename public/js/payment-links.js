import { PayByLink } from './components/pay-by-link.js';
import { ChatBot } from './components/chatbot-widget.js';

let initialEmail = window.demoSession.demoEmail ? window.demoSession.demoEmail : "";
let initialAmount = window.demoSession.checkoutAmount ? parseFloat(window.demoSession.checkoutAmount) * 100 : 4498;
let initialShopperReference = window.demoSession.shopperReference ? window.demoSession.shopperReference : "11223344556677";

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

function handleSendPaymentLink(e) {
    e.preventDefault();
    $('#payment-link-success').hide();
    let theEmailOrPhone = $(this).parent().parent().prev().find('input').val();
    if (!theEmailOrPhone) {
        alert('Please enter an email address or phone in order to use this feature');
        return;
    }

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

// Chatbot attaches to the DOM with the ID in the constructor
new ChatBot("chatBot", function () {
    $('#chat-modal').modal('hide');
    generateQrCode();
});

function chatShow() {
    $('#chat-modal').modal('show');
}

function generateQrCode() {
    $('#qr-code').empty();
    newPbl.getQRCode().then(function (result) {
        $('#qr-code').append(result.qrSvg);
        $('#qr-code').show();
        $('#action-modal').modal('show');
    });
}

// Event handlers
$(".shopperEmailField").val(initialEmail);
let elementsArray = document.querySelectorAll(".payment-link");
elementsArray.forEach(function(elem) {
    elem.addEventListener("click", handleSendPaymentLink);
});

// Chatbot listener
document.querySelector('#chat-show').addEventListener("click", chatShow);

// Event Handlers for page
document.querySelector('#create-qr-code').addEventListener("click", generateQrCode);

// Listen for authorisation notifications
Pusher.logToConsole = true;

let pusher = new Pusher('47e2eb4a3e296716c3fd', {
    cluster: 'eu'
});

var channel = pusher.subscribe('adyen-demo');

channel.bind('payment-success', function (data) {
    if (data.eventCode == "AUTHORISATION" && data.success == 'true' && (newPbl.data.reference == data.merchantReference)) {
        $('#qr-code').empty();
        $('#qr-code').hide();
        $('#success-or-failure').show();
        $('#success-or-failure').html('<div class="alert-success p-3"><div class="text-center"><i class="fas fa-check-circle" style="font-size: 40px;"></i></div><div>The customers payment for order #' + data.merchantReference + ' has been processed successfully</div></div>');
    }
});