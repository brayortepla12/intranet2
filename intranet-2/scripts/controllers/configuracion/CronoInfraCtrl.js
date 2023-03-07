'use strict';
app.controller('CronoInfraCtrl', ["$scope", "$rootScope", "CronoInfraService", "$filter", "SedeService",
    "ServicioService", "FrecuenciaService",
    function ($scope, $rootScope, CronoInfraService, $filter, SedeService, ServicioService, FrecuenciaService) {
        //<editor-fold defaultstate="collapsed" desc="inicializar variables">
        $scope.Mes = moment().format('M');
        $scope.Year = moment().format('YYYY');
        $scope.SubirExcelCronograma = false;
        $scope.UserId = $rootScope.username.UserId;
        $scope.CronogramaServicios = [];
        $scope.AnnoActual = new Date().getFullYear();
        $scope.Servicios = [];
        $scope.Sedes = [];
        $scope.SedeId = null;
        $scope.Frecuencias = [];
        $scope.CronogramaServicio = {
            SedeId: "",
            MesInicial: "",
            ServicioId: "",
            FrecuenciaMantenimientoId: "",
            Observaciones: "",
            Vigencia: "",
            CreatedBy: $rootScope.username.NombreUsuario
        };
        $scope.selected = {};
        $scope.SelectedFile = {
            name: ''
        };
        $scope.Tabla = [];
        // inicializamos las variables en _init()
        _init();

        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Excel">
        $scope.SelectFile = function (file) {
            $scope.SelectedFile = file;
        };
        $scope.Preview = function () {
            var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xls|.xlsx)$/;
            if (regex.test($scope.SelectedFile.name.toLowerCase())) {
                if (typeof (FileReader) != "undefined") {
                    var reader = new FileReader();
                    //For Browsers other than IE.
                    if (reader.readAsBinaryString) {
                        reader.onload = function (e) {
                            $scope.ProcessExcel(e.target.result);
                        };
                        reader.readAsBinaryString($scope.SelectedFile);
                    } else {
                        //For IE Browser.
                        reader.onload = function (e) {
                            var data = "";
                            var bytes = new Uint8Array(e.target.result);
                            for (var i = 0; i < bytes.byteLength; i++) {
                                data += String.fromCharCode(bytes[i]);
                            }
                            $scope.ProcessExcel(data);
                        };
                        reader.readAsArrayBuffer($scope.SelectedFile);
                    }
                } else {
                    swal("Error", "This browser does not support HTML5.", "error");
                }
            } else {
                swal("Error", "Por favor subir un archivo de excel valido.", "error");
            }
        };
        $scope.ProcessExcel = function (data) {
            //Read the Excel File data.
            var workbook = XLSX.read(data, {
                type: 'binary'
            });
            for (let i in workbook.SheetNames) {
                //Fetch the name of Sheet.
                var firstSheet = workbook.SheetNames[i];
                //Read all rows from Sheet into an JSON array.
                var excelRows = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[firstSheet]);
                console.log(excelRows);
                $scope.Tabla = $scope.Tabla.concat(excelRows);
            }
            console.log($scope.Tabla);
            
            //Display the data from Excel file in Table.
            $scope.$apply(function () {
                $scope.IsVisible = true;
            });
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Consultas">
        function GetCronogramaServicio() {
//            CronoInfraService.getAllCronogramaServicio($scope.Year).then(function (c) {
//                $scope.CronogramaServicios = c.data;
//            });
        }

//        $scope.ChangeSede = function () {
//            GetServicio();
//        };
//
//        function GetServicio() {
//            ServicioService.getServicioByUserId($rootScope.username.UserId).then(function (c) {
//                $scope.Servicios = $filter("orderBy")($filter('filter')(c.data, {SedeId: $scope.CronogramaServicio.SedeId}), "Nombre");
//            });
//        }

        function GetSede() {
            SedeService.getAllSedeByUserId($rootScope.username.UserId).then(function (c) {
                $scope.Sedes = $filter("orderBy")(c.data, "Nombre");
                $scope.SedeId = $scope.Sedes[0].SedeId;
            });
        }
        function GetFrecuencia() {
            FrecuenciaService.getAllFrecuencia().then(function (c) {
                $scope.Frecuencias = $filter("orderBy")(c.data, "Nombre");
            });
        }
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Botones guardar e imprimir">
        $scope.ChangeVigencia = () => {
            GetCronogramaServicio();
        };
        $scope.SubirArchivo = () => {
            let obj = {
                Data: JSON.stringify($scope.Tabla),
                Year: $scope.Year,
                CreatedBy: $rootScope.username.NombreCompleto
            };
            CronoInfraService.PostCronogramaServicio(obj).then((d) => {
                console.log(d.data);
                if (typeof d.data !== "string") {
                    swal("Enhorabuena", "Se ha guardado los datos con exito", "success");
                    $scope.SubirExcelTurnos = false;
                    $scope.Tabla = [];
                    $scope.SelectedFile = {
                        name: ''
                    };
                } else {
                    swal("Error", d.data, "error");
                }
            });
        };
        $scope.openExcelModal = () => {
            $scope.SubirExcelCronograma = $scope.SubirExcelCronograma ? false : true;
        };
        $scope.ListadoEquipos = function (CronogramaServicioId) {
            console.log(CronogramaServicioId)
        };
        $scope.GuardarCronogramaServicio = function () {
            var data = {
                Vigencia: $scope.Year,
                CronogramaServicio: JSON.stringify([$scope.CronogramaServicio])
            };
            CronoInfraService.PostCronogramaServicio(data).then(function (d) {
                if (typeof d.data !== "object") {
                    swal("Hubo un Error", d.data, "error");
                } else {
                    swal("Enhorabuena!!", "Se han guardado los datos satisfactoriamente", "success");
                    $scope.CronogramaServicio = {
                        SedeId: "",
                        MesInicial: "",
                        ServicioId: "",
                        FrecuenciaMantenimientoId: "",
                        Observaciones: "",
                        Vigencia: "",
                        CreatedBy: $rootScope.username.NombreUsuario
                    };
                    $('#CronogramaServicioModal').modal('hide');
                    GetCronogramaServicio();
                }
            }, function (e) {
                swal("Hubo un Error", e, "error");
            });
        };

        $scope.Imprimir = function () {
            $.print("#printable");
        };
        //</editor-fold>
        function _init() {
            GetCronogramaServicio();
            GetSede();
            GetFrecuencia();
        }
    }]);






