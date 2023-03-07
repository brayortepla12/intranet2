app.controller('ReporteServicioSSTCtrl', ["$scope", "$rootScope", "$state", "HojaVidaSSTService", "SedeService", "ServicioService", "$stateParams", 'UsuarioService', 'ReporteSSTService',
    'EmpresaService', '$filter', 'EncabezadoService',
    function ($scope, $rootScope, $state, HojaVidaSSTService, SedeService, ServicioService, $stateParams, UsuarioService, ReporteSSTService, EmpresaService, $filter, EncabezadoService) {
        //<editor-fold defaultstate="collapsed" desc="Variables">
        $scope.ImprimirReporte = false;
        $scope.ProcesandoPeticion = false;
        $scope.ReporteDiario = false;
        $scope.sol = "--";
        $scope.Empresa = null;
        $scope.Editar = false;
        $scope.Servicios = [];
        $scope.Sedes = [];
        $scope.Equipos = [];
        $scope.Usuarios = [];
        $scope.NumeroReporte = "";
        $scope.reporte = {
            NumeroReporte: "",
            SolicitudId: null,
            SedeId: $rootScope.HojaVidaRapida ? $rootScope.HojaVidaRapida.SedeId : $rootScope.solicitud ? $rootScope.solicitud.SedeId : "--",
            ServicioId: $rootScope.HojaVidaRapida ? $rootScope.HojaVidaRapida.ServicioId : $rootScope.solicitud ? $rootScope.solicitud.ServicioId : "--",
            Ubicacion: "",
            EquipoId: $rootScope.HojaVidaRapida ? $rootScope.HojaVidaRapida.HojaVidaId : "",
            FechaInspeccion: new Date(),
            FechaRecarga: "",
            FechaVencimiento: "",
            Acceso: "",
            Demarcacion: "",
            Senalizacion: "",
            InstalacionSitioAsignado: "",
            InstruccionesUso: "",
            AlturaAdecuada: "",
            Corneta: "",
            Manguera: "",
            CargaExtintor: "",
            ManijaTransporte: "",
            ManijaDescarga: "",
            Pasador: "",
            SelloSeguridad: "",
            Responsable: "",
            Observacion: "",
            CreatedBy: $rootScope.username.NombreUsuario
        };
        $scope.repuesto = {
            Codigo: "",
            Descripcion: "",
            Cantidad: 1,
            Valor: 0,
            EditMode: false
        };
        $scope.EditRepuesto = {
            Codigo: "",
            Descripcion: "",
            Cantidad: 1,
            Valor: 0
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Botones">

        $scope.GetReporteById = function () {
            if ($scope.ReporteId) {
                ReporteSSTService.GetReporteById($scope.ReporteId).then(function (d) {
                    console.log(d.data)
                    if (typeof d.data !== "string" && d.data.length > 0) {
                        $scope.reporte = d.data[0];
                        GetServicio();
                        GetUsuarios();
                        GetEquipos();
                        if (d.data[0].Servicio === "PLANTAS ELÉCTRICAS") {

                            $scope.ReporteDiario = true;
                        }
                        $scope.reporte.EquipoId = d.data[0].EquipoId;
                        $scope.NumeroReporte = 'N°         ' + lpad(d.data[0].ReporteId.toString(), '0', 4);
                        $scope.reporte.ModifiedBy = $rootScope.username.NombreUsuario;
                    } else {
                        swal("Error", d.data, "error");
                    }
                });
            }
        };
        $scope.Guardar = function () {
            //            console.log($scope.ficha.ProveedorId.originalObject )
            if (!$scope.Datos.$valid) {
                angular.element("[name='" + $scope.Datos.$name + "']").find('.ng-invalid:visible:first').focus();
            } else if ($scope.reporte.TipoServicio === "") {
                swal("Error", 'Debe seleccionar un tipo de servicio.', "error");
            } else if ($scope.reporte.EquipoId === "" && $scope.reporte.TipoServicio != "REDES") {
                swal("Error", 'Debe seleccionar un equipo.', "error");
            } else {
                var obj = {
                    Reporte: JSON.stringify([$scope.reporte]),
                    UserId: $rootScope.username.NombreUsuario
                };
                $scope.ProcesandoPeticion = true;
                ReporteSSTService.postReporte(obj).then(function (d) {
                    console.log(d.data);
                    $scope.ProcesandoPeticion = false;
                    if (typeof d.data != "string") {
                        swal("Enhorabuena!", 'Se hanguardado los datos satisfactoriamente', "success");
                    } else {
                        swal("Error", d.data, "error");
                    }
                }, function (e) {
                    $scope.ProcesandoPeticion = false;
                    swal("Error", e, "error");
                });
            }
        };
        $scope.Actualizar = function () {
            if (!$scope.Datos.$valid) {
                angular.element("[name='" + $scope.Datos.$name + "']").find('.ng-invalid:visible:first').focus();
            } else if ($scope.reporte.TipoServicio === "") {
                swal("Error", 'Debe seleccionar un tipo de servicio.', "error");
            } else if ($scope.reporte.EquipoId === "") {
                swal("Error", 'Debe seleccionar un equipo.', "error");
            } else {
                var obj = {
                    Reporte: JSON.stringify([$scope.reporte]),
                    UserId: $rootScope.username.NombreUsuario
                };
                $scope.ProcesandoPeticion = true;
                ReporteSSTService.putReporte(obj).then(function (d) {
                    console.log(d.data);
                    $scope.ProcesandoPeticion = false;
                    if (typeof d.data !== "string") {
                        swal("Enhorabuena!", 'Se hanguardado los datos satisfactoriamente', "success");
                        $scope.Reset();
                        $rootScope.ReporteId = d.data;
                    } else {
                        swal("Error", d.data, "error");
                    }
                }, function (e) {
                    $scope.ProcesandoPeticion = false;
                    swal("Error", e, "error");
                });
            }
        };

        $scope.Imprimir = function () {
            $scope.ImprimirReporte = true;
            printDiv();
        };
        $scope.DeleteRepuesto = function (i) {
            $scope.reporte.Repuestos.splice(i, 1);
        };
        $scope.Edit = function (i) {
            $scope.EditRepuesto = angular.copy($scope.reporte.Repuestos[i]);
            $scope.reporte.Repuestos[i].EditMode = true;
        };
        $scope.EditarRepuesto = function (i) {
            $scope.reporte.Repuestos[i] = $scope.EditRepuesto;
            $scope.reporte.Repuestos[i].EditMode = false;
            GetTotal();
        };
        $scope.Cancelar = function (i) {
            $scope.reporte.Repuestos[i].EditMode = false;
            $scope.EditRepuesto = {
                Codigo: "",
                Descripcion: "",
                Cantidad: 1,
                Valor: 0
            };
        };
        $scope.ResetParcial = function () {
            $scope.sol = "--"
            $scope.reporte.Ubicacion = "";
            $scope.reporte.ClaseExtintor = "";
            $scope.reporte.Modelo = "";
            $scope.reporte.FechaRecarga = "";
            $scope.reporte.FechaVencimiento = "";
            $scope.reporte.EquipoId = "";
        };
        $scope.Reset = function () {
            $scope.ReporteDiario = false;
            $scope.sol = "--";
            $('#configform')[0].reset();
            $scope.Usuarios = [];
            $scope.Equipos = [];
            $scope.reporte = {
                NumeroReporte: "",
                SolicitudId: null,
                SedeId: $rootScope.HojaVidaRapida ? $rootScope.HojaVidaRapida.SedeId : $rootScope.solicitud ? $rootScope.solicitud.SedeId : "--",
                ServicioId: $rootScope.HojaVidaRapida ? $rootScope.HojaVidaRapida.ServicioId : $rootScope.solicitud ? $rootScope.solicitud.ServicioId : "--",
                Ubicacion: "",
                EquipoId: $rootScope.HojaVidaRapida ? $rootScope.HojaVidaRapida.HojaVidaId : "",
                FechaInspeccion: new Date(),
                FechaRecarga: "",
                FechaVencimiento: "",
                Acceso: "",
                Demarcacion: "",
                Senalizacion: "",
                InstalacionSitioAsignado: "",
                InstruccionesUso: "",
                AlturaAdecuada: "",
                Corneta: "",
                Manguera: "",
                CargaExtintor: "",
                ManijaTransporte: "",
                ManijaDescarga: "",
                Pasador: "",
                SelloSeguridad: "",
                Responsable: "",
                Observacion: "",
                CreatedBy: $rootScope.username.NombreUsuario
            };
            $("#ex2_value").val('');
//            _init();
        };
        
        $scope.ChangeEquipo = function (i) {
            $scope.reporte.Ubicacion = $scope.Equipos[i].Ubicacion;
            $scope.reporte.ClaseExtintor = $scope.Equipos[i].ClaseExtintor;
            $scope.reporte.Modelo = $scope.Equipos[i].Modelo;
            $scope.reporte.FechaRecarga = $scope.Equipos[i].FechaRecarga;
            $scope.reporte.FechaVencimiento = $scope.Equipos[i].FechaVencimiento;
            $scope.reporte.EquipoId = $scope.Equipos[i].HojaVidaId;
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Helpers">
        
        function printDiv() {
            $("#ReporteSST").print({
                globalStyles: true,
                mediaPrint: false,
                stylesheet: null,
                noPrintSelector: ".no-print",
                iframe: true,
                append: null,
                prepend: null,
                manuallyCopyFormValues: true,
                deferred: $.Deferred(),
                timeout: 1000,
                title: null,
                doctype: '<!doctype html>'
            });

            setTimeout(function () {
                $scope.ImprimirReporte = false;
                $scope.$apply();
            }, 1000);

        }
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Consultas">
        $scope.ChangeServicios = function () {
            GetServicio();
            $scope.ResetParcial();
        };
        $scope.ChangeEquipos = function () {
            GetEquipos();
            GetUsuarios();
            $scope.ResetParcial();
        };
        function GetServicio() {
            ServicioService.getServicioByUserId($rootScope.username.UserId).then(function (c) {
                var f = $filter('filter')(c.data, {SedeId: $scope.reporte.SedeId});
                $scope.Servicios = $filter("orderBy")(f, "Nombre");
            });
        }
        function GetSede() {
            SedeService.getAllSedeByUserId($rootScope.username.UserId).then(function (c) {
                $scope.Sedes = $filter("orderBy")(c.data, "Nombre");
                if ($scope.Sedes.length == 1) {
                    $scope.reporte.SedeId = $scope.Sedes[0].SedeId;
                    GetServicio();
                }
            });
        }
        function GetNReporte() {

            ReporteSSTService.GetNReporte().then(function (d) {
                if (d.data.length > 0) {
                    $scope.NumeroReporte = 'N°         ' + lpad((parseInt(d.data[0].ReporteId) + 1).toString(), '0', 4);
                } else {
                    $scope.NumeroReporte = 'N°         ' + lpad('1', '0', 4);
                }
            });
        }
        function GetEquipos() {
            HojaVidaSSTService.GetHojaVida($scope.reporte.ServicioId).then(function (e) {
                $scope.Equipos = $filter("orderBy")(e.data, "Ubicacion");
            });
        }
        function GetUsuarios() {
            UsuarioService.GetALLusuariosByServicio($scope.reporte.ServicioId).then(function (u) {
                $scope.Usuarios = $filter("orderBy")(u.data, "NombreCompleto");
            });
        }
        function GetEncabezado() {
            EncabezadoService.getEncabezado().then(function (e) {
                $scope.Encabezado = e.data;
            });
        }
        function _init() {
            //            GetServicio();
            GetSede();
            if ($rootScope.HojaVidaRapida) {
                GetServicio();
                GetEquipos();
                GetUsuarios();
                $scope.reporte.Modelo = $rootScope.HojaVidaRapida.Modelo;
                $scope.reporte.ClaseExtintor = $rootScope.HojaVidaRapida.ClaseExtintor;
                $scope.reporte.Ubicacion = $rootScope.HojaVidaRapida.Ubicacion;
            }
            GetNReporte();
            GetEmpresa();
            GetEncabezado();
            if ($stateParams.Reporte_Id) {
                $scope.ReporteId = $stateParams.Reporte_Id;
                $scope.GetReporteById();
            }
        }
        function lpad(str, padString, length) {
            while (str.length < length)
                str = padString + str;
            return str;
        }
        function GetEmpresa() {
            EmpresaService.getEmpresa().then(function (e) {
                $scope.Empresa = e.data;
            });
        }
        $scope.isInArray = function (list, item) {
            for (var i in list) {
                if (list[i]) {
                    if (list[i] == item) {
                        return true;
                    }
                }
            }
            return false;
        };
        //</editor-fold>
//        printDiv();
        _init();
    }]);



