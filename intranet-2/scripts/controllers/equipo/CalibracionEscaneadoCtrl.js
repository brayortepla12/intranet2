app.controller('CalibracionEscaneadoCtrl', ["$scope", "$rootScope", "ServicioService", "SedeService", "ReporteService", "SesionService", "$filter", "HojaVidaService",
    function ($scope, $rootScope, ServicioService, SedeService, ReporteService, SesionService, $filter, HojaVidaService) {
        //<editor-fold defaultstate="collapsed" desc="Variables">
        $scope.Servicios = [];
        $scope.Servicios2 = [];
        $scope.Sedes = [];
        $scope.Equipos = [];
        $scope.ServicioId = "--";
        $scope.SedeId = "--";
        $scope.sol = "--";
        $scope.Archivo = "";
        $scope.reporte_escaneado = {
            SedeId: "--",
            ServicioId: "--",
            EquipoId: "--",
            Ubicacion: "",
            Foto: "",
            Descripcion: "",
            Ubicacion: "",
            ResponsableNombre: SesionService.get("UserData_Polivalente").NombreCompleto,
            ResponsableCargo: SesionService.get("UserData_Polivalente").Cargo,
            ResponsableFirma: SesionService.get("UserData_Polivalente").Firma,
            RecibeFecha: new Date(),
            RecibeHora: new Date(),
            RecibeNombre: "",
            RecibeCargo: "",
            RecibeEmail: "",
            EstadoFinal: [],
            TotalRepuesto: "",
            Fecha: new Date(),
            TipoServicio: "CALIBRACIÓN",
            Responsable: "",
            TipoReporte: "Externo",
            SolicitudId: null,
            CreatedBy: SesionService.get("UserData_Polivalente").NombreUsuario
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Botones">
        $scope.Guardar = function () {
            if (!$scope.Datos.$valid) {
                angular.element("[name='" + $scope.Datos.$name + "']").find('.ng-invalid:visible:first').focus();
            } else if ($scope.reporte_escaneado.SedeId == "--") {
                swal("Error", "Debe seleccionar una sede", "error");
            } else if ($scope.reporte_escaneado.ServicioId == "--") {
                swal("Error", "Debe seleccionar un servicio", "error");
            } else if ($scope.sol == "--") {
                swal("Error", "Debe seleccionar un Equipo", "error");
            } else if ($scope.reporte_escaneado.TipoServicio == "") {
                swal("Error", "Debe seleccionar un tipo de servicio", "error");
            } else if ($scope.Archivo == "") {
                swal("Error", "Debe añadir un archivo", "error");
            } else if ($scope.reporte_escaneado.EstadoFinal.length == 0) {
                swal("Error", "Debe seleccionar minimo un estado final", "error");
            } else {
                var fd = new FormData();
                fd.append('file', $scope.Archivo);
                fd.append('name', $scope.reporte_escaneado.Responsable);
                $scope.reporte_escaneado.EstadoFinal = JSON.stringify($scope.reporte_escaneado.EstadoFinal);
                var obj = {
                    reporte_escaneado: JSON.stringify([$scope.reporte_escaneado]),
                    UserId: SesionService.get("UserData_Polivalente").NombreUsuario
                };
                ReporteService.PostReporteEscaneado($scope.Archivo, $scope.reporte_escaneado).then(function (d) {
                    if (typeof d.data != "string") {
                        swal("Enhorabuena!", 'Se hanguardado los datos satisfactoriamente', "success");
                        $scope.Reset();
                    } else {
                        swal("Error", d.data, "error");
                    }
                });
            }
        };
        $scope.AddEstadoFinal = function (txt) {
            var cont = 0;
            for (var i in $scope.reporte_escaneado.EstadoFinal) {
                if ($scope.reporte_escaneado.EstadoFinal[i] === txt) {
                    cont++;
                    $scope.reporte_escaneado.EstadoFinal.splice(i, 1);
                }
            }
            if (cont == 0 && txt != "") {
                $scope.reporte_escaneado.EstadoFinal.push(txt);
            }
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="consultas">
        function GetServicio() {
            ServicioService.getAllServicio().then(function (c) {
                $scope.Servicios2 = c.data;
            });
        }
        function GetSede() {
            SedeService.getAllSede().then(function (c) {
                $scope.Sedes = c.data;
                if (c.data.length == 1) {
                    $scope.reporte_escaneado.SedeId = c.data[0].SedeId;
                    $scope.ChangeSede();
                }
            });
        }
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Helpers">

        $scope.ChangeSede = function () {
            $scope.Servicios = $filter('filter')($scope.Servicios2, {SedeId: parseInt($scope.reporte_escaneado.SedeId)}, true)
            $scope.reporte_escaneado.ServicioId = "--";
            $scope.Equipos = [];
            $scope.ResetParcial();
        };
        $scope.ChangeServicio = function () {
            HojaVidaService.GetHojaVida($scope.reporte_escaneado.ServicioId).then(function (d) {
                $scope.Equipos = d.data;
            }, function (e) {
                swal("Error", e, "error");
            });
            $scope.ResetParcial();
        };
        $scope.ChangeEquipo = function (i) {
            $scope.reporte_escaneado.Marca = $scope.Equipos[i].Marca;
            $scope.reporte_escaneado.Ubicacion = $scope.Equipos[i].Ubicacion;
            $scope.reporte_escaneado.Serie = $scope.Equipos[i].Serie;
            $scope.reporte_escaneado.Modelo = $scope.Equipos[i].Modelo;
            $scope.reporte_escaneado.Inventario = $scope.Equipos[i].Inventario;
            $scope.reporte_escaneado.EquipoId = $scope.Equipos[i].HojaVidaId;
            $scope.FotoEquipo = $scope.Equipos[i].Foto;
        };
        $scope.ResetParcial = function () {
            $scope.sol = "--"
            $scope.reporte_escaneado.Marca = "";
            $scope.reporte_escaneado.Ubicacion = "";
            $scope.reporte_escaneado.Serie = "";
            $scope.reporte_escaneado.Modelo = "";
            $scope.reporte_escaneado.Inventario = "";
            $scope.reporte_escaneado.EquipoId = "";
            $scope.FotoEquipo = "";
        };
        $scope.Reset = function () {
            $scope.cargado = false;
            $scope.Servicios = [];
            $scope.Servicios2 = [];
            $scope.Sedes = [];
            $scope.Equipos = [];
            $scope.ServicioId = "--";
            $scope.SedeId = "--";
            $scope.sol = "--";
            $scope.Archivo = "";
            $scope.reporte_escaneado = {
                SedeId: "--",
                ServicioId: "--",
                EquipoId: "--",
                Ubicacion: "",
                Foto: "",
                Descripcion: "",
                Ubicacion: "",
                ResponsableNombre: SesionService.get("UserData_Polivalente").NombreCompleto,
                ResponsableCargo: SesionService.get("UserData_Polivalente").Cargo,
                ResponsableFirma: SesionService.get("UserData_Polivalente").Firma,
                RecibeFecha: new Date(),
                RecibeHora: new Date(),
                RecibeNombre: "",
                RecibeCargo: "",
                RecibeEmail: "",
                EstadoFinal: [],
                TotalRepuesto: "",
                Fecha: new Date(),
                TipoServicio: "CALIBRACIÓN",
                Responsable: "",
                SolicitudId: null,
                TipoReporte: "Externo",
                CreatedBy: SesionService.get("UserData_Polivalente").NombreUsuario
            };
            $scope.FotoEquipo = "";
            $scope.simpleTableOptions = {};
            _init();
            $('#ModalCrearreporte_escaneado').modal('hide');
        };
        //</editor-fold>
        function _init() {
            GetServicio();
            GetSede();
        }
        _init();
    }]);