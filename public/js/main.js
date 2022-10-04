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
	UpdateBrandOne(checkIfDemoVarExistis('brandColorOne') ? demoSession.brandColorOne : defaultBrandColorOne);
	UpdateBrandTwo(checkIfDemoVarExistis('brandColorTwo') ? demoSession.brandColorTwo : defaultBrandColorTwo);
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
