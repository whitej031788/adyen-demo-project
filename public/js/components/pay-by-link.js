export class PayByLink {
  // Pass all data needed for the PBL API to set in the constructor
  constructor(data) {
    this.data = data;
  }

  // Call Laravel REST API with data to get QR code image
  getQRCode() {
    return $.ajax({
      url: '/api/adyen/getPaymentLinkQR',
      dataType: 'json',
      type: 'post',
      data: this.data
    });
  }

  getLink() {
    console.log('getlink');
  }

  sendLinkSMS(link) {
    console.log('sendsms');
  }

  sendLinkEmail(link) {
    console.log('sendemail');
  }
}
