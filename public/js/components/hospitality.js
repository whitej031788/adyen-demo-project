export class HospitalityHelper {
    constructor(data) {
        this.data = data;
    }

    setData(key, value) {
        this.data[key] = value;
    }

    addRegistrant(data) {
        if (data) {
            this.data = data;
        }

        return $.ajax({
            url: '/api/hospitality/addRegistrant',
            dataType: 'json',
            type: 'post',
            data: this.data
        });
    }

    getRegistrants() {
        return $.ajax({
            url: '/api/hospitality/getRegistrants',
            dataType: 'json',
            type: 'get'
        });
    }

    removeRegistrant() {
        return $.ajax({
            url: '/api/hospitality/removeRegistrant',
            dataType: 'json',
            type: 'post',
            data: this.data
        });
    }

    updateRegistrant(data) {        
        return $.ajax({
            url: '/api/hospitality/updateRegistrant/' + this.data.id,
            dataType: 'json',
            type: 'patch',
            data: data
        });
    }

    addLineItem(terminal) {
        return $.ajax({
            url: '/api/hospitality/addLineItem',
            dataType: 'json',
            type: 'post',
            data: {data: this.data, terminal: terminal}
        });
    }

    removeLineItem(id) {
        return $.ajax({
            url: '/api/hospitality/removeLineItem',
            dataType: 'json',
            type: 'post',
            data: {registrantId: this.data.registrantId, lineItemId: id}
        });
    }

    payFinalBill() {
        return $.ajax({
            url: '/api/hospitality/payFinalBill',
            dataType: 'json',
            type: 'post',
            data: {registrantId: this.data.registrantId, data: this.data}
        });
    }

    showVirtualReceipt() {
        return $.ajax({
            url: '/api/hospitality/showVirtualReceipt',
            dataType: 'json',
            type: 'post',
            data: {registrantId: this.data.registrantId, data: this.data}
        });
    }
}