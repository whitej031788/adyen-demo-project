function UpdateBrandOne(brandColorOne, brandColorTwo) {
  let brandOne = brandColorOne ? brandColorOne : 'white';
  let brandTwo = brandColorTwo ? brandColorTwo : 'black';

  $(".txt-brand-color-one").css("color", brandOne);
  $(".bkg-brand-color-one").css("background-color", brandOne);
  $(".bdr-brand-color-one").css("border-color", brandOne);

  $(".bkg-brand-color-one").hover(function() {
    $(this).css("background-color", brandTwo);
    $(this).css("color", brandOne);
    $(this).children(":first").css("color", brandOne);
  }, function() {
    $(this).css("background-color", brandOne);
    $(this).css("color", brandTwo);
    $(this).children(":first").css("color", brandTwo);
  });
};

function UpdateBrandTwo(brandColorTwo, brandColorOne) {
  let brandTwo = brandColorTwo ? brandColorTwo : 'black';
  let brandOne = brandColorOne ? brandColorOne : 'white';

  $("nav").css("background-color", brandTwo);
  $(".txt-brand-color-two").css("color", brandTwo);
  $(".bkg-brand-color-two").css("background-color", brandTwo);
  $(".bdr-brand-color-two").css("border-color", brandTwo);

  $(".bkg-brand-color-two").hover(function() {
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

function uuidv4() {
  return ([1e7]+-1e3+-4e3+-8e3+-1e11).replace(/[018]/g, c =>
      (c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16)
  );
}