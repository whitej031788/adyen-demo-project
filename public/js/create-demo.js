// demoSession global variable that is always available containing demo settings
$(function() {
  $(".custom-file-input").change(function() {
    if (this.files && this.files[0]) {
      let fileName = this.files[0].name;
      let idSelector = '#' + this.name + 'Label';
      $(idSelector).text(fileName);
      if (this.name == 'configFile') {
        $('#uploadFile').attr('disabled', false);
      }
    }
  });

  // Change brand colors as they change in the creation so people can see what it might look like
  $("#brandColorOne").on('input', function() {
    UpdateBrandOne(this.value);
  });
  $("#brandColorTwo").on('input', function() {
    UpdateBrandTwo(this.value);
  });
});
