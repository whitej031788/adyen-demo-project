export class TerminalApi {
  constructor(data) {
    this.data = data;
  }

  setData(key, value) {
    this.data[key] = value;
}

  cloudApiRequest(terminal) {
    return $.ajax({
      url: '/api/adyen/terminalCloudApiRequest',
      dataType: 'json',
      type: 'post',
      data: {data: this.data, terminal: terminal}
    });
  }

  cloudApiRefund(terminal) {
    return $.ajax({
      url: '/api/adyen/terminalCloudApiRefund',
      dataType: 'json',
      type: 'post',
      data: {data: this.data, terminal: terminal}
    });
  }

  cloudApiInput(terminal) {
    return $.ajax({
      url: '/api/adyen/terminalCloudApiInput',
      dataType: 'json',
      type: 'post',
      data: {data: this.data, terminal: terminal}
    });
  }

  cloudCardAcquisitionRequest(terminal) {
    return $.ajax({
      url: '/api/adyen/terminalCloudCardAcquisitionRequest',
      dataType: 'json',
      type: 'post',
      data: {data: this.data, terminal: terminal}
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
