app.service("SesionService",["store", "$crypto", function (store,$crypto) {
    this.set= function (obj, key) {
        var encrypted = $crypto.encrypt(JSON.stringify(obj), 'Franklin Ospino'); // OJO no cambiar, esta es la firma de la app, sino existe habra CAOS
                        store.set(key,encrypted);
    };
    
    this.setNormal= function (obj, key) {
        var encrypted = obj; // OJO no cambiar, esta es la firma de la app, sino existe habra CAOS
                        store.set(key,encrypted);
    };
    this.getNormal= function (key) {
        var decrypted = null;
        try {
            decrypted = store.get(key) // OJO no cambiar, esta es la firma de la app, sino existe habra CAOS
        } catch (e) {
            console.log(e);
        }
        return decrypted;
    };
    this.get= function (key) {
        var decrypted = null;
        try {
            decrypted = JSON.parse($crypto.decrypt(store.get(key), 'Franklin Ospino')) // OJO no cambiar, esta es la firma de la app, sino existe habra CAOS
        } catch (e) {
            console.log(e);
        }
        return decrypted;
    };
    this.remove= function(key){
        store.remove(key);
    };
}]);

