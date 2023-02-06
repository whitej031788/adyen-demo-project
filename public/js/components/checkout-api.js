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
        let combinedData = Object.assign(state.data, this.data);

        // Allow 3DS2
        if (!combinedData.additionalData) {
            combinedData.additionalData = {};
        }
        combinedData.additionalData.allow3DS2 = true;
        combinedData.channel = "web";
        combinedData.origin = window.location.origin;

        if (this.data.giftAmount && this.data.giftAmount.value < this.data.amount.value) {
            combinedData.amount = this.data.giftAmount;
        }

        delete combinedData.giftAmount;

        return $.ajax({
            url: '/api/adyen/makePayment',
            dataType: 'json',
            type: 'post',
            data: combinedData
        });
    }

    makeCashPayment(submitData) {
        let merchantAccount = submitData.merchantAccount;
        let combinedData = Object.assign(submitData, this.data);

        combinedData.merchantAccount = merchantAccount;

        delete combinedData.additionalData;
        delete combinedData.countryCode;
        delete combinedData.shopperEmail;
        delete combinedData.shopperLocale;

        return $.ajax({
            url: '/api/adyen/makeCashPayment',
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

    createOrder(orderRef) {
        return $.ajax({
            url: '/api/adyen/createOrder',
            dataType: 'json',
            type: 'post',
            data: {
                merchantAccount: this.data.merchantAccount,
                reference: orderRef,
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
