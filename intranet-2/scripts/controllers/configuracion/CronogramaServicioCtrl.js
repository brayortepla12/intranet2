'use strict';
app.controller('CronogramaServicioCtrl', ["$scope", "$rootScope", "CronogramaServicioService", "$filter", "$state", "SedeService",
    "ServicioService", "FrecuenciaService",
    function ($scope, $rootScope, CronogramaServicioService, $filter, $state, SedeService, ServicioService, FrecuenciaService) {
        //<editor-fold defaultstate="collapsed" desc="inicializar variables">
        let vm = $scope;
        vm.PREFIJO = "";
        vm.SUBIRCRONOGRAMA = false;
        vm.CronogramaServicios = [];
        vm.Servicios = [];
        vm.Sedes = [];
        vm.Frecuencias = [];
        vm.SedeId = "";
        vm.Vigencia = new Date().getFullYear();
        vm.VigenciaE = null;
        vm.Tabla = [];
        vm.HASDATA = false;
        vm.DataTableE = [];
        vm.DataTable = [];
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Consultas">
        function GetCronogramaServicio() {
            CronogramaServicioService.getAllCronogramaServicio().then(function (c) {
                console.log(c.data)
                vm.CronogramaServicios = $filter("orderBy")(c.data, "-CronogramaServicioId");
            });
        }

        vm.ChangeSede = function () {
            GetServicio();
        };

        function GetServicio() {
            ServicioService.getServicioByUserId($rootScope.username.UserId).then(function (c) {
                vm.Servicios = $filter("orderBy")($filter('filter')(c.data, {SedeId: vm.CronogramaServicio.SedeId}), "Nombre");
            });
        }

        function GetSede() {
            SedeService.getAllSedeByUserId_TA($rootScope.username.UserId, vm.PREFIJO).then(function (c) {
                vm.Sedes = $filter("orderBy")(c.data, "Nombre");
                if (vm.Sedes.length == 1) {
                    vm.ficha.SedeId = vm.Sedes[0].SedeId;
                }
            });
        }
        function GetFrecuencia() {
            FrecuenciaService.getAllFrecuencia().then(function (c) {
                vm.Frecuencias = $filter("orderBy")(c.data, "Nombre");
            });
        }
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Excel">
        vm.SelectFile = function (file) {
            vm.SelectedFile = file;
        };
        vm.Preview = function () {
            vm.HASDATA = false;
            var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xls|.xlsx)$/;
            if (regex.test(vm.SelectedFile.name.toLowerCase())) {
                if (typeof (FileReader) != "undefined") {
                    var reader = new FileReader();
                    //For Browsers other than IE.
                    if (reader.readAsBinaryString) {
                        reader.onload = function (e) {
                            vm.ProcessExcel(e.target.result);
                        };
                        reader.readAsBinaryString(vm.SelectedFile);
                    } else {
                        //For IE Browser.
                        reader.onload = function (e) {
                            var data = "";
                            var bytes = new Uint8Array(e.target.result);
                            for (var i = 0; i < bytes.byteLength; i++) {
                                data += String.fromCharCode(bytes[i]);
                            }
                            vm.ProcessExcel(data);
                        };
                        reader.readAsArrayBuffer(vm.SelectedFile);
                    }
                } else {
                    swal("Error", "This browser does not support HTML5.", "error");
                }
            } else {
                swal("Error", "Por favor subir un archivo de excel valido.", "error");
            }
        };
        vm.ProcessExcel = function (data) {
            vm.HASDATAERROR = false;
            vm.Tabla = [];
            //Read the Excel File data.
            let workbook = XLSX.read(data, {
                type: 'binary'
            });
            for (let i in workbook.SheetNames) {
                let name = workbook.SheetNames[i];
                //Read all rows from Sheet into an JSON array.
                let excelRows = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[name]);
                excelRows = ValidateHasValue(excelRows);
                vm.Tabla = vm.Tabla.concat(excelRows);
            }
            vm.DataTable = {
                data: [],
                aoColumns: [
                    {mData: 'Item'},
                    {mData: 'Equipo'},
                    {mData: 'Marca'},
                    {mData: 'Modelo'},
                    {mData: 'Serie'},
                    {mData: 'Ubicacion'},
                    {mData: '1'},
                    {mData: '2'},
                    {mData: '3'},
                    {mData: '4'},
                    {mData: '5'},
                    {mData: '6'},
                    {mData: '7'},
                    {mData: '8'},
                    {mData: '9'},
                    {mData: '10'},
                    {mData: '11'},
                    {mData: '12'},
                    {mData: 'Vigencia'}
                ],
                "searching": true,
                "iDisplayLength": 25,
                "language": {
                    "lengthMenu": "Mostrar _MENU_ registros por página",
                    "zeroRecords": " No hay Items Registrados ",
                    "infoFiltered": "(filtro de _MAX_ registros totales)",
                    "search": " Filtrar : ",
                    "oPaginate": {
                        "sPrevious": "Anterior",
                        "sNext": "Siguiente"
                    }
                },
                "aaSorting": []
            };
            vm.DataTable.data = setFormat(angular.copy(vm.Tabla));
            //Display the data from Excel file in Table.
            vm.$apply(function () {
                vm.HASDATA = true;
            });
        };
        function ValidateHasValue(excelRows) {
            // validamos y removemos los items que no tengan ningun cronograma en los meses 1 - 12
            if (excelRows) {
                _.remove(excelRows, function (o) {
                    let sumbool = null;
                    for (let i = 1, max = 12; i <= max; i++) {
                        sumbool = sumbool || o.hasOwnProperty("" + i);
                    }
                    return !sumbool;
                });
            }
            return excelRows;
        }
        function setFormat(lst) {
            for (let i in lst) {
                for (let k = 1, max = 12; k <= max; k++) {
                    if (!lst[i].hasOwnProperty("" + k)) {
                        lst[i]["" + k] = "";
                    }
                }
            }
            return lst;
        }
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Botones guardar e imprimir">
        vm.OpenModalSubirExcel = function () {
            vm.SUBIRCRONOGRAMA = true;
        };
        vm.Atras = () => {
            vm.SUBIRCRONOGRAMA = false;
        };
        vm.SubirCronograma = function () {
            vm.HASDATAERROR = false;
            var data = {
                CronogramaServicio: JSON.stringify(vm.Tabla),
                CreatedBy: $rootScope.username.NombreUsuario,
                Prefijo: vm.PREFIJO
            };
            CronogramaServicioService.PostCronogramaServicio(data).then(function (d) {
                if (typeof d.data === "string") {
                    swal("Hubo un Error", d.data, "error");
                } else if (d.data.length > 0) {
                    vm.HASDATA = false;
                    swal("Hubo un Error", `No se pudieron registrar ${d.data.length} equipos.`, "error");
                    vm.VigenciaE = d.data[0].Vigencia;
                    vm.DataTableE = {
                        data: [],
                        aoColumns: [
                            {mData: 'Nombre'},
                            {mData: 'Marca'},
                            {mData: 'Modelo'},
                            {mData: 'Serie'},
                            {mData: 'Ubicacion'},
                            {mData: '_1'},
                            {mData: '_2'},
                            {mData: '_3'},
                            {mData: '_4'},
                            {mData: '_5'},
                            {mData: '_6'},
                            {mData: '_7'},
                            {mData: '_8'},
                            {mData: '_9'},
                            {mData: '_10'},
                            {mData: '_11'},
                            {mData: '_12'},
                            {mData: 'Vigencia'}
                        ],
                        "searching": true,
                        "iDisplayLength": 25,
                        "language": {
                            "lengthMenu": "Mostrar _MENU_ registros por página",
                            "zeroRecords": " No hay Items Registrados ",
                            "infoFiltered": "(filtro de _MAX_ registros totales)",
                            "search": " Filtrar : ",
                            "oPaginate": {
                                "sPrevious": "Anterior",
                                "sNext": "Siguiente"
                            }
                        },
                        "aaSorting": []
                    };
                    vm.DataTableE.data = d.data;
                    vm.HASDATAERROR = true;
                } else {
                    vm.HASDATA = false;
                    swal("Enhorabuena!!", "Se han guardado los datos satisfactoriamente", "success");
                }
            }, function (e) {
                swal("Hubo un Error", e, "error");
            });
        };

        vm.Imprimir = function () {
            $.print("#printable");
        };
        //</editor-fold>
        function _init() {
            let tipo = 'pol';
            switch ($state.current.name) {
                case "configuracion.Cronograma":
                    tipo = 'pol';
                    break;
                case "configuracion.CronogramaSistemas":
                    tipo = 'sistemas';
                    break;
                case "configuracion.CronogramaBiomedicos":
                    tipo = 'biomedicos';
                    break;
            }
            vm.PREFIJO = tipo;
            GetCronogramaServicio();
            GetSede();
            GetFrecuencia();
        }
        // inicializamos las variables en _init()
        _init();
    }]);






