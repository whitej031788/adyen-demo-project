import { AccountNumber, BranchCode, AccountHolderCode } from './components/predefined-fakes.js'
import * as faker from 'https://rawgit.com/Marak/faker.js/master/examples/browser/js/faker.js';

function createAccountHolder(e) {
  e.preventDefault();

  const data = {
    "accountHolderCode": $('#sellerId').val(),
    "accountHolderDetails": {
        "address": {
            "city": "PASSED",
            "country": "GB",
            "houseNumberOrName": "1",
            "postalCode": "NG147NE",
            "street": window.faker.address.streetName()
        },
        "bankAccountDetails": [
            {
                "countryCode": "GB",
                "currencyCode": "GBP",
                "accountNumber": AccountNumber(),
                "branchCode": BranchCode(),
                "ownerCity": "PASSED",
                "ownerCountryCode": "GB",
                "ownerHouseNumberOrName": "1",
                "ownerName": "TestData",
                "ownerPostalCode": "NG147NE",
                "ownerStreet": window.faker.address.streetName()
            }
        ],
        "email": $('#email').val(),
        "individualDetails": {
            "name": {
                "firstName": $('#firstName').val(),
                "gender": $('#gender').val(),
                "lastName": "TestData"
            },
            "personalData": {
                "dateOfBirth": "2001-04-27"
            }
        },
        "phoneNumber": {
            "phoneCountryCode": "GB",
            "phoneNumber": "01234567890",
            "phoneType": "Landline"
        },
        "webAddress": "https://www.example.com",
        "merchantCategoryCode": 7111
    },
    "legalEntity": "Individual",
    "processingTier": 3
}

  $.ajax({
    url: '/api/platforms/createAccountHolder',
    dataType: 'json',
    type: 'post',
    data: data,
    success: function(retData, textStatus, jQxhr) {
      if (retData.accountHolderStatus.status === "Active") {
        $('#onboardingForm').addClass("d-none");
        $("#accountCreated").removeClass("d-none");
        $("#nextButton").removeClass("d-none");
      } else { // Failed MOTO payment
        $('#card-payment-error').show();
      }
      window.scrollTo(0,0);
    },
    error: function(jqXhr, textStatus, errorThrown) {
      console.log(errorThrown);
    }
  });
}

function createSellerId(){
  const firstName = $('#firstName').val();
  $('input[id=sellerId]').val(AccountHolderCode(firstName));
}


// Event handlers
//$('#accountCreated').hide();
$('#nextButton').click(() => {
  location.href = 'afp-dashboard';
});
$('#createAccountHolder').click(createAccountHolder);
$('#firstName').change(createSellerId);
$('#email').val(window.faker.internet.exampleEmail())
