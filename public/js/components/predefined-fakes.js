import * as faker from 'https://rawgit.com/Marak/faker.js/master/examples/browser/js/faker.js';

function AccountNumber() {
    return Faker().datatype.number({min: 1000000, max: 99999999});
};

function BranchCode(){
    return Faker().datatype.number({min: 100000, max: 999999});
};

function AccountHolderCode(i){
    return Faker().fake(`${i}-{{name.lastName}}`);
};

function StoreName(){
    return Faker().commerce.productName().replace(/\s/g, '-').substring(0, 22);
};

function CompanyNumber(){
    return Faker().datatype.number({min: 10000000, max: 999999999});
};

function ProductValue(){
    return Faker().datatype.number({min: 5000, max: 30000})
}

function NumberBetween(min,max) {
    return Faker().datatype.number({min, max}).toString();
}


function Faker(){
    window.faker.locale = 'en_GB';
    return window.faker;
}

export { AccountNumber, BranchCode, AccountHolderCode, StoreName, CompanyNumber, ProductValue, NumberBetween, Faker}
