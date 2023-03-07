app.controller('VerReporteSOLCtrl', ["$scope", "$rootScope", "$state", "HojaVidaService", "SedeService", "ServicioService", "$stateParams", 'UsuarioService', 'ReporteService',
    'EmpresaService', '$filter', 'EncabezadoService',
    function ($scope, $rootScope, $state, HojaVidaService, SedeService, ServicioService, $stateParams, UsuarioService, ReporteService, EmpresaService, $filter, EncabezadoService) {
        //<editor-fold defaultstate="collapsed" desc="Variables">
        let vm = $scope;
        vm.ProcesandoPeticion = false;
        vm.ReporteDiario = false;
        vm.sol = "--";
        vm.Empresa = null;
        vm.Editar = false;
        vm.Servicios = [];
        vm.FallaDetectada = '';
        vm.Sedes = [];
        vm.Equipos = [];
        vm.Usuarios = [];
        vm.NumeroReporte = "";
        vm.reporte = {
            NumeroReporte: "",
            SolicitudId: null,
            SedeId: $rootScope.HojaVidaRapida ? $rootScope.HojaVidaRapida.SedeId : $rootScope.solicitud ? $rootScope.solicitud.SedeId : "--",
            Fecha: "",
            ServicioId: $rootScope.HojaVidaRapida ? $rootScope.HojaVidaRapida.ServicioId : $rootScope.solicitud ? $rootScope.solicitud.ServicioId : "--",
            Solicitante: "",
            Ubicacion: "",
            Responsable: "",
            TipoServicio: "",
            EquipoId: $rootScope.HojaVidaRapida ? $rootScope.HojaVidaRapida.HojaVidaId : "",
            FallaReportada: "",
            FallaDetectada: [],
            ProcedimientoRealizado: "",
            MedidasAplicadas: [],
            Repuestos: "",
            TotalRepuesto: "",
            Observaciones: "",
            EstadoFinal: [],
            ResponsableNombre: $rootScope.username.NombreCompleto,
            ResponsableCargo: $rootScope.username.Cargo,
            //ResponsableFirma: $rootScope.username.Firma,
            ResponsableId: $rootScope.username.UserId,
            Ciudad: "N/A",
            HoraInicio: null,
            HoraFinal: null,
            NivelCombustible: null,
            NivelAguaRefrigerante: null,
            NivelAceite: null,
            NivelElectrolitoBateria: null,
            VoltajeBateria: null,
            FechaUltCambioAceite: null,
            FiltroAire: null,
            Fugas: null,
            RecibeFecha: new Date(),
            RecibeHora: new Date(),
            RecibeNombre: "",
            RecibeCargo: "",
            RecibeEmail: "",
            Fecha: new Date(),
            Repuestos: [],
            TotalRepuesto: 0,
            TipoReporte: "Manual",
            ReporteArchivo: null,
            CreatedBy: $rootScope.username.NombreUsuario
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Botones">
        vm.GetReporteById = function () {
            if ($rootScope.REPORTEID) {
                ReporteService.GetReporteById($rootScope.REPORTEID).then(function (d) {
                    if (typeof d.data !== "string" && d.data.length > 0) {
                        vm.reporte = d.data[0];
                        GetServicio();
                        GetUsuarios();
                        GetEquipos();
                        vm.reporte.EquipoId = d.data[0].EquipoId;
                        vm.reporte.FallaDetectada = typeof d.data[0].FallaDetectada === 'string' ? JSON.parse(d.data[0].FallaDetectada) : null;
                        vm.reporte.MedidasAplicadas = typeof d.data[0].MedidasAplicadas === 'string' ? JSON.parse(d.data[0].MedidasAplicadas) : null;
                        vm.reporte.Repuestos = typeof d.data[0].Repuestos === 'string' ? JSON.parse(d.data[0].Repuestos) : null;
                        vm.reporte.EstadoFinal = typeof d.data[0].EstadoFinal === 'string' ? JSON.parse(d.data[0].EstadoFinal) : null;
                        vm.NumeroReporte = 'N°         ' + lpad(d.data[0].ReporteId.toString(), '0', 4);
                        vm.reporte.ModifiedBy = $rootScope.username.NombreUsuario;
                        vm.reporte.TipoReporte = "Manual";
                        vm.reporte.RecibeFirma = d.data[0].RecibeFirma;
                    } else {
                        swal("Error", "No existe este Reporte", "error");
                    }
                });
            }
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Helpers">
        function GetTotal() {
            vm.reporte.TotalRepuesto = 0;
            for (var i in vm.reporte.Repuestos) {
                vm.reporte.TotalRepuesto += parseFloat(vm.reporte.Repuestos[i].Valor);
            }
        }
        function printDiv() {

            $("#myElementId").print({
                globalStyles: true,
                mediaPrint: false,
                stylesheet: null,
                noPrintSelector: ".no-print",
                iframe: true,
                append: null,
                prepend: null,
                manuallyCopyFormValues: true,
                deferred: $.Deferred(),
                timeout: 750,
                title: null,
                doctype: '<!doctype html>'
            });

        }
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Consultas">
        vm.ChangeServicios = function () {
            GetServicio();
            vm.ResetParcial();
        };
        vm.ChangeEquipos = function () {
            GetEquipos();
            GetUsuarios();
            vm.ResetParcial();
        };
        function GetServicio() {
            ServicioService.getServicioByUserId($rootScope.username.UserId).then(function (c) {
                var f = $filter('filter')(c.data, {SedeId: vm.reporte.SedeId});
                vm.Servicios = $filter("orderBy")(f, "Nombre");
            });
        }
        function GetSede() {
            SedeService.getAllSedeByUserId($rootScope.username.UserId).then(function (c) {
                vm.Sedes = $filter("orderBy")(c.data, "Nombre");
                if (vm.Sedes.length == 1) {
                    vm.reporte.SedeId = vm.Sedes[0].SedeId;
                    GetServicio();
                }
            });
        }
        function GetNReporte() {

            ReporteService.GetNReporte().then(function (d) {
                if (d.data.length > 0) {
                    vm.NumeroReporte = 'N°         ' + lpad((parseInt(d.data[0].ReporteId) + 1).toString(), '0', 4);
                } else {
                    vm.NumeroReporte = 'N°         ' + lpad('1', '0', 4);
                }
            });
        }
        function GetEquipos() {
            HojaVidaService.GetHojaVida(vm.reporte.ServicioId).then(function (e) {
                vm.Equipos = $filter("orderBy")(e.data, "Ubicacion");
            });
        }
        function GetUsuarios() {
            UsuarioService.GetALLusuariosByServicio(vm.reporte.ServicioId).then(function (u) {
                vm.Usuarios = $filter("orderBy")(u.data, "NombreCompleto");
            });
        }
        function GetEncabezado() {
            EncabezadoService.getEncabezado().then(function (e) {
                vm.Encabezado = e.data;
            });
        }
        function _init() {
            //            GetServicio();
            GetSede();
            GetNReporte();
            GetEmpresa();
            GetEncabezado();
            if ($rootScope.REPORTEID) {
                vm.ReporteId = $rootScope.REPORTEID;
                vm.GetReporteById();
            }
        }
        function lpad(str, padString, length) {
            while (str.length < length)
                str = padString + str;
            return str;
        }
        function GetEmpresa() {
            EmpresaService.getEmpresa().then(function (e) {
                vm.Empresa = e.data;
            });
        }
        vm.isInArray = function (list, item) {
            for (var i in list) {
                if (list[i]) {
                    if (list[i] === item) {
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



