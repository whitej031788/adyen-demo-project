function UpdateBrandOne(theColor) {
  $(".txt-brand-color-one").css("color", theColor ? theColor : 'white');
  $(".bkg-brand-color-one").css("background-color", theColor ? theColor : 'white');
  $(".bdr-brand-color-one").css("border-color", theColor ? theColor : 'white');
};

function UpdateBrandTwo(theColor) {
  $("nav").css("background-color", theColor ? theColor : 'black');
  $(".txt-brand-color-two").css("color", theColor ? theColor : 'black');
  $(".bkg-brand-color-two").css("background-color", theColor ? theColor : 'black');
  $(".bdr-brand-color-two").css("border-color", theColor ? theColor : 'black');
};

function UpdateMerchantLogo(theLogo) {
  let logos = document.getElementsByClassName('merchant-logo');

  [].slice.call(logos).forEach(function(logo) {
    logo.src = theLogo ? theLogo : '/img/adyen-vector-logo-wide.png';
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
