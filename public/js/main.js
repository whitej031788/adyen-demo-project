// Show / hide sub options
window.onload = function() {
  $("input.expand-under-ul").click(function() {
    $(this).parent().next('.sub-options').slideToggle();
  });

  let spans = document.getElementsByClassName('merchant-name');
  let logos = document.getElementsByClassName('merchant-logo');
  let checkouts = document.getElementsByClassName('merchant-checkout');

  [].slice.call(spans).forEach(function(span) {
      span.innerHTML = demoSession ? demoSession.merchantName : '';
  });

  [].slice.call(logos).forEach(function(logo) {
      logo.src = (demoSession && demoSession.merchantLogoUrl) ? demoSession.merchantLogoUrl : '/img/adyen-vector-logo-wide.png';
  });

  [].slice.call(checkouts).forEach(function(screenshot) {
      screenshot.src = demoSession ? demoSession.screenshotUrl : '';
  });

  $("nav").css("background-color", checkIfDemoVarExistis('brandColorTwo') ? demoSession.brandColorTwo : 'black');
  $(".txt-brand-color-one").css("color", checkIfDemoVarExistis('brandColorOne') ? demoSession.brandColorOne : 'white');
  $(".txt-brand-color-two").css("color", checkIfDemoVarExistis('brandColorTwo') ? demoSession.brandColorTwo : 'black');
  $(".bkg-brand-color-one").css("background-color", checkIfDemoVarExistis('brandColorOne') ? demoSession.brandColorOne : 'white');
  $(".bkg-brand-color-two").css("background-color", checkIfDemoVarExistis('brandColorTwo') ? demoSession.brandColorTwo : 'black');
  $(".bdr-brand-color-one").css("border-color", checkIfDemoVarExistis('brandColorOne') ? demoSession.brandColorOne : 'white');
  $(".bdr-brand-color-two").css("border-color", checkIfDemoVarExistis('brandColorTwo') ? demoSession.brandColorTwo : 'black');
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
