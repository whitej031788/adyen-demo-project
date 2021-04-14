export class TerminalApi {
  constructor(data) {
    this.data = data;
  }

  cloudApiRequest(terminal) {
    return $.ajax({
      url: '/api/adyen/terminalCloudApiRequest',
      dataType: 'json',
      type: 'post',
      data: {data: this.data, terminal: terminal}
    });
  }

  cloudApiRequestInput(terminal) {
    return $.ajax({
      url: '/api/adyen/terminalCloudApiRequestInput',
      dataType: 'json',
      type: 'post',
      data: {data: this.data, terminal: terminal}
    });
  }

}
