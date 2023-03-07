app.filter('character',function(){
    return function(input){
        return String.fromCharCode(64 + parseInt(input,10));
    };
});

