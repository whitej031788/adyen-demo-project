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
}
