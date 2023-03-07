app.controller('ReporteEscaneadoInternoCtrl', ["$scope", "$rootScope", "ServicioService", "SedeService", "ReporteService", "SesionService", "$state", "UsuarioService",
    function ($scope, $rootScope, ServicioService, SedeService, ReporteService, SesionService, $state, UsuarioService) {
        //<editor-fold defaultstate="collapsed" desc="Variables">
        if (!$rootScope.solicitud) {
            window.history.back();
        }
        $scope.Servicios = [];
        $scope.Sedes = [];
        $scope.Equipos = [];
        $scope.sol = "--";
        $scope.Archivo = "";
        $scope.Usuarios = [];
        $scope.reporte_escaneado = {
            SedeId: $rootScope.solicitud.SedeId,
            ServicioId: $rootScope.solicitud.ServicioId,
            Solicitante: $rootScope.solicitud.Solicitante,
            EquipoId: $rootScope.solicitud.EquipoId,
            Equipo: $rootScope.solicitud.Equipo,
            Ubicacion: $rootScope.solicitud.Ubicacion,
            Marca: $rootScope.solicitud.Marca,
            Modelo: $rootScope.solicitud.Modelo,
            Serie: $rootScope.solicitud.Serie,
            Inventario: $rootScope.solicitud.Inventario,
            Observacion: $rootScope.solicitud.Descripcion,
            ResponsableNombre: $rootScope.username.NombreCompleto,
            ResponsableCargo: $rootScope.username.Cargo,
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
            TotalRepuesto: "",
            Fecha: new Date(),
            TipoServicio: "CORRECTIVO",
            Responsable: "",
            EstadoFinal: [],
            TipoReporte: "Externo",
            SolicitudId: $rootScope.solicitud.SolicitudId,
            CreatedBy: $rootScope.username.NombreUsuario
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
            } else if ($scope.reporte_escaneado.TipoServicio == "") {
                swal("Error", "Debe seleccionar un tipo de servicio", "error");
            } else if ($scope.Archivo == "") {
                swal("Error", "Debe añadir un archivo", "error");
            } else if ($scope.reporte_escaneado.EstadoFinal.length == 0) {
                swal("Error", "Debe seleccionar minimo un estado final", "error");
            } else if ($scope.reporte_escaneado.RecibeNombre == "") {
                swal("Error", "Debe seleccionar un usuario que recibe", "error");
            } else {
                var fd = new FormData();
                fd.append('file', $scope.Archivo);
                fd.append('name', $scope.reporte_escaneado.Responsable);
                $scope.reporte_escaneado.EstadoFinal = JSON.stringify($scope.reporte_escaneado.EstadoFinal);
                var obj = {
                    reporte_escaneado: JSON.stringify([$scope.reporte_escaneado]),
                    UserId: $rootScope.username.NombreUsuario
                };
                ReporteService.PostReporteEscaneado($scope.Archivo, $scope.reporte_escaneado).then(function (d) {
                    if (typeof d.data != "string") {
                        swal("Enhorabuena!", 'Se hanguardado los datos satisfactoriamente', "success");
                        $scope.Reset();
                        $state.go('mantenimiento.solicitudAdmin');
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
        function GetUsuarios() {
            UsuarioService.GetALLusuariosByServicio($scope.reporte_escaneado.ServicioId).then(function (u) {
                $scope.Usuarios = u.data;
                $scope.Recibe();
            });
        }
        function GetServicio() {
            ServicioService.getAllServicio().then(function (c) {
                $scope.Servicios = c.data;
            });
        }
        function GetSede() {
            SedeService.getAllSede().then(function (c) {
                $scope.Sedes = c.data;
            });
        }

        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Helpers">
        $scope.Recibe = function () {
            for (var i in $scope.Usuarios) {
                if ($scope.reporte_escaneado.Solicitante === $scope.Usuarios[i].NombreCompleto) {
                    $scope.reporte_escaneado.RecibeCargo = $scope.Usuarios[i].Cargo;
                    $scope.reporte_escaneado.RecibeNombre = $scope.Usuarios[i].NombreCompleto;
                    $scope.reporte_escaneado.RecibeEmail = $scope.Usuarios[i].Email;
                }
            }
        };
        $scope.ResetParcial = function () {
            $scope.Usuarios = [];
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
            $scope.Usuarios = [];
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
                SedeId: $rootScope.solicitud.SedeId,
                ServicioId: $rootScope.solicitud.ServicioId,
                Solicitante: $rootScope.solicitud.Solicitante,
                EquipoId: $rootScope.solicitud.EquipoId,
                Equipo: $rootScope.solicitud.Equipo,
                Ubicacion: $rootScope.solicitud.Ubicacion,
                Marca: $rootScope.solicitud.Marca,
                Modelo: $rootScope.solicitud.Modelo,
                Serie: $rootScope.solicitud.Serie,
                Inventario: $rootScope.solicitud.Inventario,
                Observacion: $rootScope.solicitud.Descripcion,
                Fecha: new Date(),
                TipoServicio: "CORRECTIVO",
                Responsable: "",
                ResponsableNombre: $rootScope.username.NombreCompleto,
                ResponsableCargo: $rootScope.username.Cargo,
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
                EstadoFinal: [],
                TotalRepuesto: "",
                TipoReporte: "Externo",
                SolicitudId: $rootScope.solicitud.SolicitudId,
                CreatedBy: $rootScope.username.NombreUsuario
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
            GetUsuarios();
        }
        _init();

    }]);