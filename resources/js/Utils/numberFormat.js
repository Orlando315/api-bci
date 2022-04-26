export const numberFormat = function (number, decimals = 0) {
    number = parseFloat(number);

    if(! Number.isFinite(number)){
        return number;
    }

    return number.toLocaleString('de-DE', {minimumFractionDigits: decimals, maximumFractionDigits : decimals});
}
