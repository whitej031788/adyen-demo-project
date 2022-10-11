import {CheckoutApi} from './components/checkout-api.js';

function subscribeNow() {
    let paymentDataObj = {
        "countryCode": "GB",
        "merchantAccount": adyenConfig.merchantAccount,
        "shopperLocale": "en-GB",
        "reference": uuidv4(),
        "shopperReference": uuidv4(),
        "shopperEmail": window.demoSession.demoEmail ? window.demoSession.demoEmail : "",
        "amount": {
            "value": parseInt(this.id)*100,
            "currency": "GBP"
        }
    };

    let checkoutApi = new CheckoutApi(paymentDataObj);

    // Restrict to recurring payment methods
    checkoutApi.getPaymentMethods(paymentDataObj, 'klarna, scheme, applepay, paywithgoogle, paypal, ideal').then(async function (paymentMethodsResponse) {
        let configuration = {
            amount: checkoutApi.data.amount,
            environment: "test",
            clientKey: adyenConfig.clientKey,
            locale: paymentDataObj.shopperLocale,
            paymentMethodsResponse: paymentMethodsResponse.response,
            onSubmit: function (state, dropin) {
                dropin.setStatus('loading');
                checkoutApi.submitPayment(state, dropin).then(function (result) {
                    console.log(result.response);
                    if (result.response.action) {
                        dropin.handleAction(result.response.action);
                    } else {
                        switch (result.response.resultCode) {
                            case 'Cancelled':
                                dropin.setStatus('error', { message: 'Transaction Cancelled' });
                                break;
                            case 'Authorised':
                                dropin.setStatus('success');
                                break;
                            default:
                                dropin.setStatus('error', { message: 'Something went wrong' });
                        }
                    }
                });
            },
            //Submit additional details for paypal
            onAdditionalDetails: function (state, component) {
                checkoutApi.submitDetails(state.data).then(function (result) {
                    component.setStatus("success");
                    console.log(result);
                })
            },
            paymentMethodsConfiguration: {
                onError: function (error) {
                    console.log(error)
                },
                card: {
                    hasHolderName: true,
                    holderNameRequired: true
                }
            }
        };

        let checkout = await window.AdyenCheckout(configuration);
        let dropin = checkout.create('dropin');
        dropin.mount('#dropin-container');
        $('#subscribe-modal').modal('show');
    });
}

$(".subscribe-now").on('click', subscribeNow);