export class TerminalApi {
  constructor(data) {
    this.data = data;
  }

  cloudApiRequest() {
    return $.ajax({
      url: '/api/adyen/terminalCloudApiRequest',
      dataType: 'json',
      type: 'post',
      data: this.data
    });
  }
}
