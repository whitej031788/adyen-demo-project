// demoSession global variable that is always available containing demo settings
$(function() {
    let initialOptionsString = '<div id="step1options">';

    $('[data-toggle="tooltip"]').tooltip();

    $.each(stepOneValues, function( index, object ) {
        initialOptionsString += `
            <div class="row mt-1">
                <div class="col-6 off-3 col-12-mobile">
                    <a href="#step2" id="${object.value}" class="button button-secondary scrolly full-width stepaction-1">${object.label}</a>
                </div>
            </div>
        `;
    });

    initialOptionsString += "</div>";

    $('#step1 > header').after(initialOptionsString);

    $('.scrolly').scrolly();

    $(".stepaction-1").click(function() {
        $("#step2 > header > h2").text($(this).text());
        $("#merchantVertical").val(this.id);
        let valuesForStepTwo = [];
        switch (this.id) {
            case 'retail':
                valuesForStepTwo = retailValues;
                break;
            case 'hotel':
                valuesForStepTwo = hotelValues;
                break;
            case 'digital':
                valuesForStepTwo = digitalValues;
                break;
            case 'food':
                valuesForStepTwo = foodValues;
                break;
        }

        $('#step2options').remove();

        let htmlString = '<div id="step2options">';

        $.each(valuesForStepTwo, function( index, object ) {
            htmlString += `
                <div class="row mt-1">
					<div class="col-6 off-3 col-12-mobile">
					    <a href="#step3" id="${object.value}" class="button button-secondary scrolly full-width stepaction-2">${object.label}</a>
					</div>
			    </div>
            `;
        });

        htmlString += "</div>";
        $('#step2 > header').after(htmlString);

        $('#step2').removeClass('hide-with-sizes');
        $('.scrolly').scrolly();
    });

    $(document).on("click", ".stepaction-2", function() {
        $("#merchantSubtype").val(this.id);
        let currentSelection = $("#merchantVertical").val() + '-' + this.id;
        // Here we will redirect to any existing demos if they are useful
        // No need to reinvent the wheel
        switch (currentSelection) {
            case 'retail-classic':
                // My Store Demo 2, classic retail demo
                window.location.href = "https://adyen.strakzat.com/fabienne-chapot";
                break;
            case 'retail-ipp':
                window.location.href = "https://uc.seamless-checkout.net";
                break;
            default:
                // We are staying here if it's not in the above
                $('#step3').removeClass('hide-with-sizes');
                break;
        }
    });

    $(document).on("click", ".stepaction-3", function() {
        let instructionHash = $("#merchantVertical").val() + '-' + $("#merchantSubtype").val();
        $('#demoInstructions').html(demoInstructions[instructionHash]);
        $('#step4').removeClass('hide-with-sizes');
    });
});

function showInstructions() {
    return false;
}

let stepOneValues = [
    {value: "retail", label: "Retail"},
    {value: "hotel", label: "Hotels"},
    {value: "digital", label: "Digital / Subscriptions"},
    {value: "food", label: "F & B"},
    {value: "finance", label: "Financial Services"}
]

let retailValues = [
    {value: "ipp", label: "In Person Payments"},
    {value: "classic", label: "Classic Ecommerce"},
    {value: "unified", label: "Unified Commerce"}
];

let hotelValues = [
    {value: "booking", label: "Online booking"},
    {value: "checkin", label: "Check in"},
    {value: "payasyougo", label: "Pay as you go"}
];

let digitalValues = [
    {value: "saas", label: "SaaS Subscriptions"},
    {value: "marketplace", label: "Online Marketplace"}
];

let foodValues = [
    {value: "paytable", label: "Pay @ Table"},
    {value: "clickcollect", label: "Click and Collect"}
];

let subscriptionInstructions = `
    <span>
        When discussing subscriptions with merchants, it is important to talk about Revenue Accelerate
        and our other revenue optimization products. These features are why some exceptionally large
        subscription merchants (Spotify, Netflix) choose Adyen as their payment partner. Real Time
        Account Updater, Network Tokens, and Auto Rescue help merchant decrease involuntary churn,
        increase MRR and ARR (monthly and annual recurring revenue), and increase customer retention.
        Please refer to 
        <a href='https://hub.is.adyen.com/our-solution/enhancements/revenue-optimization/revenue-accelerate' target='_blank'>HUB</a> 
        for additional information.
    </span>
`;

let unifiedCommerceInstructions = `
    <span>
        Few merchants today are successfully executing on all of their cross-channel initiatives and strategies. This is in part due to being hampered by the complexity and rigidity of their payment system providers and disparate systems. Adyen empowers merchants to provide a consistent experience for shoppers irrespective of the sales channel. From this, merchants can benefit from value added services like shopper data and also more enhanced personalisation features through our partnerships. Please refer to <a href='https://hub.is.adyen.com/our-solution/payments/unified-commerce' target='_blank'>HUB</a> 
        for additional information.
    </span>
`;

let hospitalityInstructions = `
    <span>
        Customer demands in the hospitality space are becoming more and more complex, and Adyen is uniquely position to help hospitality venues deliver unmatched
        personalized experiences for guests. Our <a href='https://www.adyen.com/hotel-payments' target='_blank'>public facing page</a> paints a good picture, but this demo will bring you just a slice of what Adyen's terminal technology along with their
        expertise can allow hospitality venues to acheive.
    </span>
`;


let demoInstructions = {
    "digital-saas" : subscriptionInstructions,
    "retail-unified": unifiedCommerceInstructions,
    "hotel-payasyougo": hospitalityInstructions
};