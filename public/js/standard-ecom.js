let dataObj = {
  "countryCode": "GB",
  "merchantAccount": merchantAccount
};

$.ajax({
  url: '/api/adyen/getPaymentMethods',
  dataType: 'json',
  type: 'post',
  data: dataObj,
  success: function(retData, textStatus, jQxhr) {
    console.log(retData);
  },
  error: function(jqXhr, textStatus, errorThrown) {
    console.log(errorThrown);
  }
});
