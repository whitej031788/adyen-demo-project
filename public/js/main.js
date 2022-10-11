// Listen to all AJAX requests to update technical demo fields and do other stuff
$(document).ajaxComplete(function (event, XMLHttpRequest, ajaxOptions) {
  let ajaxResponse = $.parseJSON(XMLHttpRequest.responseText);
  // If it's a technical demo, add the data into the DOM
  if (demoSession.technicalDemo && demoSession.technicalDemo === "on") {
    $('#apiUrlOrMethod').empty();
    $('#apiRequest').empty();
    $('#apiResponse').empty();

    populatePostman(ajaxResponse);
  }
});

// Show / hide sub options
window.onload = function() {
  $("input.expand-under-ul").click(function() {
    $(this).parent().next('.sub-options').slideToggle();
  });

	let defaultBrandColorOne = '#ffffff';
	let defaultBrandColorTwo = '#000000';

	// These global functions are defined in ui-demo-utils.js
	UpdateMerchantName((demoSession && demoSession.merchantName) ? demoSession.merchantName : 'Test Merchant');
	UpdateMerchantLogo((demoSession && demoSession.merchantLogoUrl) ? demoSession.merchantLogoUrl : '/img/Adyen-White-Dark-Background-Logo.wine.png');
	UpdateMerchantCheckout((demoSession && demoSession.screenshotUrl) ? demoSession.screenshotUrl : '/img/default-checkout-picture.png');
	UpdateBrandOne(checkIfDemoVarExistis('brandColorOne') ? demoSession.brandColorOne : defaultBrandColorOne, checkIfDemoVarExistis('brandColorTwo') ? demoSession.brandColorTwo : defaultBrandColorTwo);
	UpdateBrandTwo(checkIfDemoVarExistis('brandColorTwo') ? demoSession.brandColorTwo : defaultBrandColorTwo, checkIfDemoVarExistis('brandColorOne') ? demoSession.brandColorOne : defaultBrandColorOne);

}

function populatePostman(data) {
  $('#apiUrlOrMethod').html("<span>" + data.method + "</span>");
  $('#apiRequest').html(jsonPrettyHighlightToId(data.request));
  $('#apiResponse').html(jsonPrettyHighlightToId(data.response));
}

function jsonPrettyHighlightToId(jsonobj) {
  var json = JSON.stringify(jsonobj, undefined, 2);

  json = json.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
  json = json.replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g, function (match) {
    var cls = 'color: darkorange;';
    if (/^"/.test(match)) {
      if (/:$/.test(match)) {
        cls = 'color: red;';
      } else {
        cls = 'color: green;';
      }
    } else if (/true|false/.test(match)) {
      cls = 'color: blue;';
    } else if (/null/.test(match)) {
      cls = 'color: magenta;';
    }
    return '<span style="' + cls + '">' + match + '</span>';
  });

  return json;
}

function checkIfDemoVarExistis(param) {
  if (!demoSession) {
    return false;
  }

  if (demoSession[param] && demoSession[param] !== "" && demoSession[param] !== null && demoSession[param] !== undefined) {
    return true;
  } else {
    return false;
  }
}
