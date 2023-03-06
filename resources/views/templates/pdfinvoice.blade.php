<!DOCTYPE html>
<html>
<head>
    <title>Invoice</title>
</head>
<style type="text/css">
    body{
        font-family: 'Roboto Condensed', sans-serif;
    }
    .m-0{
        margin: 0px;
    }
    .p-0{
        padding: 0px;
    }
    .pt-5{
        padding-top:5px;
    }
    .mt-10{
        margin-top:10px;
    }
    .text-center{
        text-align:center !important;
    }
    .w-100{
        width: 100%;
    }
    .w-50{
        width:50%;   
    }
    .w-85{
        width:85%;   
    }
    .w-15{
        width:15%;   
    }
    .logo img{
        width:45px;
        height:45px;
        padding-top:30px;
    }
    .logo span{
        margin-left:8px;
        top:19px;
        position: absolute;
        font-weight: bold;
        font-size:25px;
    }
    .gray-color{
        color:#5D5D5D;
    }
    .text-bold{
        font-weight: bold;
    }
    .border{
        border:1px solid black;
    }
    table tr,th,td{
        border: 1px solid #d2d2d2;
        border-collapse:collapse;
        padding:7px 8px;
    }
    table tr th{
        background: #F4F4F4;
        font-size:15px;
    }
    table tr td{
        font-size:13px;
    }
    table{
        border-collapse:collapse;
    }
    .box-text p{
        line-height:10px;
    }
    .float-left{
        float:left;
    }
    .total-part{
        font-size:16px;
        line-height:12px;
    }
    .total-right p{
        padding-right:20px;
    }
</style>
<body>
<div class="head-title">
    <h1 class="text-center m-0 p-0">{{ $merchantName }} Invoice</h1>
</div>
<div class="add-detail mt-10">
    <div class="w-50 float-left mt-10">
        <p class="m-0 pt-5 text-bold w-100">Invoice Id - <span class="gray-color">#6</span></p>
        <p class="m-0 pt-5 text-bold w-100">Order Id - <span class="gray-color">162695CDFS</span></p>
        <p class="m-0 pt-5 text-bold w-100">Order Date - <span class="gray-color">03-06-2022</span></p>
    </div>
    <div style="clear: both;"></div>
</div>
<div class="table-section bill-tbl w-100 mt-10">
    <table class="table w-100 mt-10">
        <tr>
            <th class="w-50">From</th>
            <th class="w-50">To</th>
        </tr>
        <tr>
            <td>
                <div class="box-text">
                    <p>{{ $merchantName }}</p>
                    <p>123 Main St</p>
                    <p>London</p>
                    <p>EC1V</p>
                    <p>United Kingdom</p>
                </div>
            </td>
            <td>
                <div class="box-text">
                    <p>Example Customer</p>
                    <p>999 1st St</p>
                    <p>London</p>
                    <p>EC1V</p>
                    <p>United Kingdom</p>
                </div>
            </td>
        </tr>
    </table>
</div>
<div class="table-section bill-tbl w-100 mt-10">
    <table class="table w-100 mt-10">
        <tr>
            <th class="w-50">Payment Method</th>
            <th class="w-50">Shipping Method</th>
        </tr>
        <tr>
            <td><a href="{{ $adyenUrl }}" target="_blank">Online Secure Payment</a></td>
            <td>Free Shipping - Free Shipping</td>
        </tr>
    </table>
</div>
<div class="table-section bill-tbl w-100 mt-10">
    <table class="table w-100 mt-10">
        <tr>
            <th class="w-50">SKU</th>
            <th class="w-50">Product Name</th>
            <th class="w-50">Price</th>
            <th class="w-50">Qty</th>
            <th class="w-50">Subtotal</th>
            <th class="w-50">Tax Amount</th>
            <th class="w-50">Grand Total</th>
        </tr>
        <tr align="center">
            <td>$656</td>
            <td>Mobile</td>
            <td>$204.2</td>
            <td>3</td>
            <td>$500</td>
            <td>$50</td>
            <td>$100.60</td>
        </tr>
        <tr align="center">
            <td>$656</td>
            <td>Mobile</td>
            <td>$254.2</td>
            <td>2</td>
            <td>$500</td>
            <td>$50</td>
            <td>$120.00</td>
        </tr>
        <tr align="center">
            <td>$656</td>
            <td>Mobile</td>
            <td>$554.2</td>
            <td>5</td>
            <td>$500</td>
            <td>$50</td>
            <td>$100.00</td>
        </tr>
        <tr>
            <td colspan="7">
                <div class="total-part">
                    <div class="total-left w-85 float-left" align="right">
                        <p>Sub Total</p>
                        <p>Tax (18%)</p>
                        <p>Total Payable</p>
                    </div>
                    <div class="total-right w-15 float-left text-bold" align="right">
                        <p>$20</p>
                        <p>$20</p>
                        <p>$330.00</p>
                    </div>
                    <div style="clear: both;"></div>
                </div> 
            </td>
        </tr>
    </table>
    <div style="margin-top: 10px;"><a href="{{ $adyenUrl }}" target="_blank" style="display: inline-block; color: #ffffff; background-color: #3498db; border: solid 1px #3498db; border-radius: 5px; box-sizing: border-box; cursor: pointer; text-decoration: none; font-size: 14px; font-weight: bold; margin: 0; padding: 12px 25px; text-transform: capitalize; border-color: #3498db;">Pay Invoice</a></div>
</div>
</html>