// app.config(["$indexedDBProvider", function ($indexedDBProvider) {
//     $indexedDBProvider
//       .connection('Biometrico')
//       .upgradeDatabase(1, function(event, db, tx){
//         let objStore = db.createObjectStore('ct_persona', {keyPath: 'PersonaId'});
//         objStore.createIndex('PrimerNombre', 'PrimerNombre', {unique: false});
//         objStore.createIndex('SegundoNombre', 'SegundoNombre', {unique: false});
//         objStore.createIndex('PrimerApellido', 'PrimerApellido', {unique: false});
//         objStore.createIndex('SegundoApellido', 'SegundoApellido', {unique: false});
//         objStore.createIndex('Cedula', 'Cedula', {unique: false});
//         objStore.createIndex('CodigoTarjeta', 'CodigoTarjeta', {unique: false});
//         objStore.createIndex('Foto', 'Foto', {unique: false});
//         objStore.createIndex('EstadoBiometrico', 'EstadoBiometrico', {unique: false});
        
//         objStore = db.createObjectStore('ct_control', {keyPath: 'ControlId'});
//         objStore.createIndex('Fecha', 'Fecha', {unique: false});
//         objStore.createIndex('PersonaId', 'PersonaId', {unique: false});
//         objStore.createIndex('Dispositivo', 'Dispositivo', {unique: false});
//         objStore.createIndex('Tipo', 'Tipo', {unique: false});
//       });
//   }]);
