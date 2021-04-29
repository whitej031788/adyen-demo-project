import * as faker from 'https://rawgit.com/Marak/faker.js/master/examples/browser/js/faker.js';

function AccountNumber() {
  return window.faker.random.number({min: 1000000, max: 99999999});
};

function BranchCode(){
  return window.faker.random.number({min: 100000, max: 999999});
};

function AccountHolderCode(i){
  return window.faker.fake(`${i}-{{name.lastName}}`);
};

function StoreName(){
  return window.faker.commerce.productName().replace(/\s/g, '-').substring(0, 22);
};

function CompanyNumber(){
  return window.faker.random.number({min: 10000000, max: 999999999});
};

function ProductValue(){
  return window.faker.random.number({min: 5000, max: 30000})
}

export { AccountNumber, BranchCode, AccountHolderCode, StoreName, CompanyNumber, ProductValue}
