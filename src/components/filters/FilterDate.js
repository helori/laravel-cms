Vue.filter('date', function (value, sep, format) {
    if(value){
        var date = new Date(value);
        var mm = date.getMonth() + 1;
        var dd = date.getDate();

        var d = (dd>9 ? '' : '0') + dd;
        var m = (mm>9 ? '' : '0') + mm;
        var y = date.getFullYear();

        var result = [];
        if(format == 'Y-m-d'){
            result.push(y);
            result.push(m);
            result.push(d);
        }else{
            result.push(d);
            result.push(m);
            result.push(y);
        }
        return result.join(sep ? sep : '/');
    }else{
        return '';
    }
});

Vue.filter('datetime', function (value, sep) {
    if(value){
        var date = new Date(value);
        var mm = date.getMonth() + 1;
        var dd = date.getDate();
        return [
            (dd>9 ? '' : '0') + dd,
            (mm>9 ? '' : '0') + mm,
            date.getFullYear()
        ].join(sep ? sep : '/');
    }else{
        return '';
    }
});