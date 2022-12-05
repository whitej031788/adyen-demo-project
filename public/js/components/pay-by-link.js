export class PayByLink {
  // Pass all data needed for the PBL API to set in the constructor
  constructor(data) {
    this.data = data;
  }

  // Call Laravel REST API with data to get QR code SVG example
  // The result of this promise is SVG XML and can be rendered to an HTML page
  getQRCode() {
    return $.ajax({
      url: '/api/adyen/getPaymentLinkQR',
      dataType: 'json',
      type: 'post',
      data: this.data
    });
  }

  getLink() {
    return $.ajax({
      url: '/api/adyen/generateAndSendPaymentLink',
      dataType: 'json',
      type: 'post',
      data: {type: 'fetch', data: this.data}
    });
  }

  sendLinkSMS() {
    return $.ajax({
      url: '/api/adyen/generateAndSendPaymentLink',
      dataType: 'json',
      type: 'post',
      data: {type: 'sms', data: this.data}
    });
  }

  sendLinkEmail() {
    return $.ajax({
      url: '/api/adyen/generateAndSendPaymentLink',
      dataType: 'json',
      type: 'post',
      data: {type: 'email', data: this.data}
    });
  }

  sendQRToTerminal(terminal) {
    return $.ajax({
      url: '/api/adyen/sendQRToTerminal',
      dataType: 'json',
      type: 'post',
      data: {data: this.data, terminal: terminal}
    });
  }
}
