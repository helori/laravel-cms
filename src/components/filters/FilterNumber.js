Vue.filter('number', function (value, decimals) {
    if(value){
        var ent = Math.floor(value);
        var dec = 0;
        if(ent != value){
            dec = (value - ent) + '';
            dec = dec.substring(2);
            dec = dec.substring(0, decimals ? parseInt(decimals) : 0);
        }
        ent = ent + '';
        
        var result = '';
        var counter = 0;
        for(var i=ent.length - 1; i>=0; --i){
            if(counter == 3){
                result += ' ';
                counter = 0;
            }
            result = ent.charAt(i) + result;
            ++counter;
        }
        if(dec){
            result += ',' + dec;
        }
        return result;
    }else{
        return '';
    }
});
