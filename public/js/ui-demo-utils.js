function UpdateBrandOne(brandColorOne, brandColorTwo) {
  let brandOne = brandColorOne ? brandColorOne : '#F7F8F9';
  let brandTwo = brandColorTwo ? brandColorTwo : '#00112C';

  $(".txt-brand-color-one").css("color", brandOne);
  $(".bkg-brand-color-one").css("background-color", brandOne);
  $(".bdr-brand-color-one").css("border-color", brandOne);

  $(".bkg-brand-color-one:not(.no-hover)").hover(function() {
    $(this).css("background-color", brandTwo);
    $(this).css("color", brandOne);
    $(this).children(":first").css("color", brandOne);
  }, function() {
    $(this).css("background-color", brandOne);
    $(this).css("color", brandTwo);
    $(this).children(":first").css("color", brandTwo);
  });
};

function UpdateBrandTwo(brandColorOne, brandColorTwo) {
  let brandOne = brandColorOne ? brandColorOne : '#F7F8F9';
  let brandTwo = brandColorTwo ? brandColorTwo : '#00112C';

  $("nav").css("background-color", brandTwo);
  $(".txt-brand-color-two").css("color", brandTwo);
  $(".bkg-brand-color-two").css("background-color", brandTwo);
  $(".bdr-brand-color-two").css("border-color", brandTwo);

  $(".bkg-brand-color-two:not(.no-hover)").hover(function() {
    $(this).css("background-color", brandOne);
    $(this).css("color", brandTwo);
    $(this).children(":first").css("color", brandTwo);
  }, function() {
    $(this).css("background-color", brandTwo);
    $(this).css("color", brandOne);
    $(this).children(":first").css("color", brandOne);
  });
};

function UpdateMerchantLogo(theLogo) {
  let logos = document.getElementsByClassName('merchant-logo');

  [].slice.call(logos).forEach(function(logo) {
    logo.src = theLogo ? theLogo : '/img/Adyen-White-Dark-Background-Logo.wine.png';
  });
}

function UpdateMerchantName(name) {
  let spans = document.getElementsByClassName('merchant-name');

  [].slice.call(spans).forEach(function(span) {
    span.innerHTML = name ? name : 'Test Merchant';
  });
}

function UpdateMerchantCheckout(checkout) {
  let checkouts = document.getElementsByClassName('merchant-checkout');

  [].slice.call(checkouts).forEach(function(screenshot) {
    screenshot.src = checkout ? checkout : '/img/default-checkout-picture.png';
  });
}

function UpdateAdyenDropInAndComponents(brandColorOne, brandColorTwo) {
  let brandOne = brandColorOne ? brandColorOne : '#F7F8F9';
  let brandTwo = brandColorTwo ? brandColorTwo : '#00112C';

  $(".adyen-checkout__payment-method").css("background-color", brandTwo);
  $(".adyen-checkout__payment-method__name").css("color", brandOne);

  $("<style>").text(".adyen-checkout__button--pay { background-color: " + brandOne + " }").appendTo("head");
  $("<style>").text(".adyen-checkout__button--pay { color: " + brandTwo + " }").appendTo("head");
  $("<style>").text(".adyen-checkout__button--pay:hover { background-color: " + brandTwo + " }").appendTo("head");
  $("<style>").text(".adyen-checkout__button--pay:hover { color: " + brandOne + " }").appendTo("head");
  $("<style>").text(".adyen-checkout__button--pay:hover { border: 1px solid " + brandOne + " }").appendTo("head");
  $("<style>").text(".adyen-checkout__dropin label span { color: " + brandOne + " }").appendTo("head");
}

function uuidv4() {
  return ([1e7]+-1e3+-4e3+-8e3+-1e11).replace(/[018]/g, c =>
      (c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16)
  );
}