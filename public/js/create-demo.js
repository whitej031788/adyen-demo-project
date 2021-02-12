// demoSession global variable that is always available containing demo settings
$(function() {
  $("#configFile").change(function() {
    if (this.files && this.files[0]) {
      let fileName = this.files[0].name;
      $('#configFileLabel').text(fileName);
      $('#uploadFile').attr('disabled', false);
    }
  });
});
