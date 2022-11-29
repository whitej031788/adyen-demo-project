import {TerminalApi} from './components/terminal-api.js';
import {HospitalityHelper} from './components/hospitality.js';

var hospitalityHelper;
var entireTotal;

function getLineItemInfo() {
    let obj = {
        itemName: $('#itemName').val(),
        itemSku: $('#itemSku').val(),
        unitPrice: $('#unitPrice').val(),
        quantity: $('#quantity').val(),
        reference: uuidv4()
    };

    let amount = {currency: "GBP", value: obj.unitPrice * 100 * obj.quantity};

    obj.amount = amount;

    return obj;
}

function removeLineItem() {
    var lineItemId = $(this).attr("data-item-id");
    var lineItemAmount = $(this).attr("data-item-amount");
    hospitalityHelper.removeLineItem(lineItemId).then((result) => {
        $('#line-item-' + lineItemId).remove();
        entireTotal -= lineItemAmount;
        $('#grand-total').text("Grand Total: £" + parseFloat(entireTotal).toFixed(2));
    });
}

function payFinalBill() {
    hospitalityHelper.setData('reference', uuidv4());
    hospitalityHelper.payFinalBill().then((result) => {
        console.log(result);
        if (result.PaymentResponse.Response.Result === "Success") {
            $('#line-items-table > table > tbody').empty();
        } else {

        }
    });
}

function buildLineItemsTable(result) {
    // Here we want to populate the customer name, and build the table of their current bill
    entireTotal = 0;
    for (let i = 0; i < result.lineItems.length; i++) {
        let lineTotal = result.lineItems[i].quantity * result.lineItems[i].unit_price / 100;
        let lineItemRow = "<tr id='line-item-" + result.lineItems[i].id + "'>";
        lineItemRow += "<td>" + result.lineItems[i].id + "</td>";
        lineItemRow += "<td>" + result.lineItems[i].item_name + "</td>";
        lineItemRow += "<td>" + result.lineItems[i].store + "</td>";
        lineItemRow += "<td>" + result.lineItems[i].quantity + "</td>";
        lineItemRow += "<td>£" + parseFloat(result.lineItems[i].unit_price / 100).toFixed(2) + "</td>";
        lineItemRow += "<td>£" + parseFloat(lineTotal).toFixed(2) + "</td>";
        lineItemRow += "<td><button data-item-amount='" + lineTotal + "' data-item-id='" + result.lineItems[i].id + "' class='btn btn-primary txt-brand-color-one bkg-brand-color-two bdr-brand-color-two remove-line-item' type='button'>Remove</button></td>";
        lineItemRow += "</tr>";
        $('#line-items-table > table > tbody').append(lineItemRow);
        entireTotal += lineTotal;
    }
    let finalTotalRow = "<tr><td id='grand-total' class='font-weight-bold text-capitalize font-italic' colspan='5'>Grand Total: £" + parseFloat(entireTotal).toFixed(2) + "</td>";
    finalTotalRow += "<td><button id='pay-bill' class='btn btn-secondary txt-brand-color-one bkg-brand-color-two bdr-brand-color-two' type='button'>Pay Bill</button></td>";
    finalTotalRow += "</tr>";
    $('#line-items-table > table > tbody').append(finalTotalRow);
    $('#customer-name').text(result.customerName);
    $('#line-items-table').show();
}

function submitLineItem(e) {
    e.preventDefault();
    $('#line-items-table').hide();
    sendLineItem(getLineItemInfo());
}

function sendLineItem(lineItemInfo) {
    hospitalityHelper = new HospitalityHelper(lineItemInfo);
    $('#line-items-table > table > tbody').empty();

    hospitalityHelper.addLineItem().then((result) => {
        hospitalityHelper.setData('registrantId', result.id);
        buildLineItemsTable(result);
    }, (error) => {
        alert(error.responseJSON.message)
    });
}

function quickLineItem() {
    var lineItemName = $(this).attr("data-item-name");
    var lineItemAmount = $(this).attr("data-item-amount");
    var lineItemSku = $(this).attr("data-item-sku");

    sendLineItem({
        itemName: lineItemName,
        itemSku: lineItemSku,
        unitPrice: lineItemAmount,
        quantity: 1,
        reference: uuidv4(),
        amount: {value: lineItemAmount * 100, currency: "GBP"}
    });
}

document.getElementById('cash-register').addEventListener("submit", submitLineItem);
$(document.body).on("click", ".remove-line-item", removeLineItem);
$(document.body).on("click", "#pay-bill", payFinalBill);
$(document.body).on("click", ".quick-checkout", quickLineItem);