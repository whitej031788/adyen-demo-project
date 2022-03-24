export class CheckoutApi {

    constructor(data) {
        this.data = data;
    }

    setData(key, value) {
        this.data[key] = value;
    }

    getPaymentMethods(data, allowedPaymentMethods) {
        // Can override with your own info, but otherwise use data from constructor
        if (!data) {
            data = {
                "merchantAccount": this.data.merchantAccount,
                "countryCode": this.data.countryCode
            }
        }

        if (allowedPaymentMethods) {
            data.allowedPaymentMethods = allowedPaymentMethods.split(',');
        }

        return $.ajax({
            url: '/api/adyen/getPaymentMethods',
            dataType: 'json',
            type: 'post',
            data: data
        });
    }

    submitPayment(state, component) {
        let combinedData = Object.assign(this.data, state.data);

        // Allow 3DS2
        combinedData.additionalData.allow3DS2 = true;
        combinedData.channel = "web";
        combinedData.origin = window.location.origin;

        return $.ajax({
            url: '/api/adyen/makePayment',
            dataType: 'json',
            type: 'post',
            data: combinedData
        });

    }

    adjustPayment(data) {
        return $.ajax({
            url: '/api/adyen/adjustPayment',
            dataType: 'json',
            type: 'post',
            data: data
        });
    }

    capturePayment(data) {
        return $.ajax({
            url: '/api/adyen/capturePayment',
            dataType: 'json',
            type: 'post',
            data: data
        });
    }

    checkBalance(giftCard) {
        return $.ajax({
            url: '/api/adyen/checkBalance',
            dataType: 'json',
            type: 'post',
            data: {
                merchantAccount: this.data.merchantAccount,
                paymentMethod: giftCard.paymentMethod,
                amount: this.data.amount
            }
        });
    }

    getCostEstimate(encryptedCardNumber) {
        return $.ajax({
            url: '/api/adyen/getCostEstimate',
            dataType: 'json',
            type: 'post',
            data: {
                amount: this.data.amount,
                encryptedCardNumber: encryptedCardNumber,
                merchantAccount: this.data.merchantAccount
            }
        });
    }

    //for paypal stuff
    submitDetails(data) {
        if (!data) {
            data = {
                "details": this.data.details,
                "paymentData": this.data.paymentData,
                "returnUrl": this.data
            }
        }

    return $.ajax({
      url: '/api/adyen/submitAdditionalDetails',
      dataType: 'json',
      type: 'post',
      data: data,
      error: function (req, textStatus,errorThrown) {
        alert('oops ' + textStatus + '' + errorThrown);
      }
    });
  }

    makeDonation(data) {
        return $.ajax({
            url: '/api/adyen/makeDonation',
            dataType: 'json',
            type: 'post',
            data: data
        });
    }
}
