var numeral = require('numeral');
numeral.register('locale', 'ru', {
    delimiters: {
        thousands: ' ',
        decimal: ','
    },
    abbreviations: {
        thousand: 'k',
        million: 'm',
        billion: 'b',
        trillion: 't'
    },
    ordinal: function(number) {
        return number === 1 ? 'er' : 'ème';
    },
    currency: {
        symbol: process.env.MIX_CURRENCY
    }
});
numeral.locale('ru')

export function capitalize(string) {
    return _.capitalize(string);
}

export function lowerCase(string) {
    return _.lowerCase(string);
}

export function lowerFirst(string) {
    return _.lowerFirst(string);
}

export function dateFormat(date) {
    return date;
}

export function truncate(str, ln = 40) {
    return _.truncate(str, { 'length': ln, 'separator': ' ' });
}

function getDecimals(c) {
    let arr = ['', '0', '00', '000', '0000', '00000', '000000', '0000000', '00000000', '000000000', '0000000000'];
    return arr[c] ? arr[c] : arr[9];
}

function createFormattedNumber(n, c, currency = '') {
    if (n) {
        let decimal = getDecimals(c);
        const str = n.toString();
        if (str.indexOf('e') !== -1) {
            const exponent = parseInt(str.split('-')[1], 10);
            n = parseFloat(n)
            const result = n.toFixed(exponent);
            return result;
        } else {
            let nm = parseFloat(n);
            return numeral(nm).format(`0,0.[${decimal}] ${currency}`);
        }
    }
    return n;
}

export function formatMoney(n, c) {
    return createFormattedNumber(n, c, '$');
};

export function formatNumber(n, c) {
    return createFormattedNumber(n, c, '')
};

export function sum(prop) {
    return _.sumBy(this, prop);
}

export function divide(a, b) {
    return _.divide(a, b);
}

export function date(dt) {
    let dt_s = Date.parse(dt);
    if (!Number.isNaN(dt_s)) {
        let date = new Date(dt_s)
        let day = date.getDate();
        let month = date.getMonth() + 1;
        let year = date.getFullYear();
        if (day < 10) {
            day = '0' + day;
        }
        if (month < 10) {
            month = '0' + month;
        }
        return (day + "." + month + "." + year);
    }
    return '';
}

export function addMeasurement(material, qty) {
    if (material && material.measurement_changeable === true) {
        return ('(' + parseFloat((+material.additional_measurement_rate) * (+qty)).toFixed(3) + (material.additional_measurement ? material.additional_measurement.name : '') + ')');
    } else {
        return '';
    }
}

export function inverseAddMeasurement(material, qty) {
    let str = '';
    if (material && material.measurement_changeable === true) {
        str = material.additional_measurement ? material.additional_measurement.name : '';
        str += (' (' + parseFloat((+qty) / (+material.additional_measurement_rate)).toFixed(3) + ' ' + (material.measurement ? material.measurement.name : '') + ')');
    } else {
        str = material.measurement ? material.measurement.name : '';;
    }
    return str;
}