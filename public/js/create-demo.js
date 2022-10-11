// demoSession global variable that is always available containing demo settings
$(function() {
  $(".custom-file-input").change(function() {
    if (this.files && this.files[0]) {
      let fileName = this.files[0].name;
      let idSelector = '#' + this.name + 'Label';
      $(idSelector).text(fileName);
      if (this.name == 'configFile') {
        $('#uploadFile').attr('disabled', false);
      } else {
        $('#screenshotThumb').attr('src', '');
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

  var checkboxArrayYes = [];
  var topLevelCheckboxes = ['enableEcom', 'enableMoto', 'enableAfp', 'enableHotelCheckin'];
  // This means they are on the edit demo page, and we can populate all fields
  if (demoSession && window.location.pathname == '/edit-demo') {
    for (const property in demoSession) {
      // Feature checkboxes are denoted by the parent topic, IE enableEcom, and then the feature name, IE adyenGiving.
      // So in the JS object, this would be enableEcom_adyenGiving, but in the HTML, it's enableEcom.adyenGiving
      // So do a quick check and sub out any _ for .
      let isCheckbox = property.includes("_") || topLevelCheckboxes.includes(property);
      let htmlId = property.replace('_', '.');
      if (isCheckbox) {
        checkboxArrayYes.push(htmlId);
      }

      $('#' + htmlId).val(demoSession[property]);

      if (property === 'screenshotUrl') {
        $('#screenshotThumb').attr('src', demoSession[property]);
      }
    }

    // Loop through checkboxes, which denotes feature, and tick / untick them
    $("#featureList input:checkbox").each(function() {
      if (checkboxArrayYes.includes(this.id)) {
        this.checked = true;
      } else {
        this.checked = false;
      }
    });
  }
});