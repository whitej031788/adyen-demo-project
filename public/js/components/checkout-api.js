export class CheckoutApi {

  constructor(data) {
    this.data = data;
  }

  getPaymentMethods(data) {
    // Can override with your own info, but otherwise use data from constructor
    if (!data) {
      data = {
        "merchantAccount": this.data.merchantAccount,
        "countryCode": this.data.countryCode
      }
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

    return $.ajax({
      url: '/api/adyen/makePayment',
      dataType: 'json',
      type: 'post',
      data: combinedData
    });

  }

//for paypal stuff
    submitDetails(data) {
       // let data = Object.assign(this.data, state.data);
 if(!data) {
     data = {
         "details": this.data.details,
         "paymentData": this.data.paymentData,
         "returnUrl" : this.data
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

}

