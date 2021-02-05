// Show / hide sub options
window.onload = function() {
  $("input.expand-under-ul").click(function() {
    $(this).parent().next('.sub-options').slideToggle();
  });

  let spans = document.getElementsByClassName('merchant-name');
  let logos = document.getElementsByClassName('merchant-logo');

  [].slice.call(spans).forEach(function(span) {
      span.innerHTML = demoSession ? demoSession.merchantName : '';
  });

  [].slice.call(logos).forEach(function(logo) {
      logo.src = demoSession ? demoSession.merchantLogoUrl : '';
  });

  $("nav").css("background-color", demoSession ? demoSession.brandColorTwo : 'black');
  $(".txt-brand-color-one").css("color", demoSession ? demoSession.brandColorOne : 'white');
  $(".bkg-brand-color-two").css("background-color", demoSession ? demoSession.brandColorTwo : 'white');
  $(".bdr-brand-color-two").css("border-color", demoSession ? demoSession.brandColorTwo : 'white');
}
