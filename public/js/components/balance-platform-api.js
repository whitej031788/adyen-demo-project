export class BalancePlatformApi {
    constructor(data) {
        this.data = data;
    }

    setData(key, value) {
        this.data[key] = value;
    }

    getAccountHolders(data) {
        return $.ajax({
            url: '/api/adyen/balance/getAccountHolders',
            dataType: 'json',
            type: 'post',
            data: data
        });
    }
}