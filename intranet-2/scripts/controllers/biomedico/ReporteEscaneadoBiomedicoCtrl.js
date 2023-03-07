app.controller('ReporteEscaneadoBiomedicoCtrl', ["$scope", "$rootScope", "ServicioBiomedicoService", "SedeBiomedicoService", 
    "ReporteBiomedicoService", "SesionService", "$filter", "HojaVidaServiceBiomedico","UsuarioBiomedicoService",
    function ($scope, $rootScope, ServicioService, SedeService, ReporteService, SesionService, $filter, HojaVidaService,UsuarioService) {
        //<editor-fold defaultstate="collapsed" desc="Variables">
        $scope.Servicios = [];
        $scope.Servicios2 = [];
        $scope.Sedes = [];
        $scope.Equipos = [];
        $scope.ServicioId = "--";
        $scope.SedeId = "--";
        $scope.sol = "--";
        $scope.Archivo = "";
        $scope.Usuarios = [];
        $scope.reporte_escaneado = {
            SedeId: $rootScope.HojaVidaRapida ? $rootScope.HojaVidaRapida.SedeId : "--",
            ServicioId: $rootScope.HojaVidaRapida ? $rootScope.HojaVidaRapida.ServicioId : "--",
            EquipoId: $rootScope.HojaVidaRapida ? $rootScope.HojaVidaRapida.EquipoId : "--",
            Ubicacion: $rootScope.HojaVidaRapida ? $rootScope.HojaVidaRapida.Ubicacion : "",
            Solicitante: "",
            Foto: "",
            Descripcion: "",
            ResponsableNombre: $rootScope.username.NombreCompleto,
            ResponsableCargo: $rootScope.username.Cargo,
//            ResponsableFirma: $rootScope.username.FirmaJefe,
            ResponsableId: $rootScope.username.UserId,
            RecibeFecha: new Date(),
            RecibeHora: new Date(),
            RecibeNombre: "",
            RecibeCargo: "",
            RecibeEmail: "",
            EstadoFinal: [],
            TotalRepuesto: "",
            Fecha: new Date(),
            TipoServicio: "",
            Responsable: "",
            TipoReporte: "Externo",
            SolicitudId: null,
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
            } else if ($scope.sol == "--") {
                swal("Error", "Debe seleccionar un Equipo", "error");
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
                        $rootScope.$broadcast('ReporteEscaneadoId_new', { ReporteId: d.data[0] });
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
            ServicioService.getServicioByUserId($rootScope.username.UserId).then(function (c) {
                var f = $filter('filter')(c.data, { SedeId: $scope.reporte_escaneado.SedeId});
                $scope.Servicios = $filter("orderBy")(f, "Nombre");
            });
        }
        function GetSede() {
            SedeService.getAllSedeByUserId($rootScope.username.UserId).then(function (c) {
                $scope.Sedes = $filter("orderBy")(c.data,"Nombre");
                if (c.data.length == 1) {
                    $scope.reporte_escaneado.SedeId = c.data[0].SedeId;
                    $scope.ChangeSede();
                }
            });
        }
        function GetUsuarios() {
            UsuarioService.GetALLusuariosByServicio($scope.reporte_escaneado.ServicioId).then(function (u) {
                $scope.Usuarios = $filter("orderBy")(u.data,"NombreCompleto");
            });
        }
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Helpers">
        $scope.Recibe = function (i) {
            $scope.reporte_escaneado.RecibeCargo = $scope.Usuarios[i].Cargo;
            $scope.reporte_escaneado.RecibeNombre = $scope.Usuarios[i].NombreCompleto;
            $scope.reporte_escaneado.RecibeEmail = $scope.Usuarios[i].Email;
            $scope.reporte_escaneado.RecibeId = $scope.Usuarios[i].UsuarioId;
        };
        $scope.ChangeSede = function () {
//            $scope.Servicios = $filter('filter')($scope.Servicios2, {SedeId: parseInt($scope.reporte_escaneado.SedeId)}, true)
//            $scope.reporte_escaneado.ServicioId = "--";
            $scope.Equipos = [];
            GetServicio();
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
            GetUsuarios();
        };
        $scope.ResetParcial = function () {
            $scope.sol = "--"
            $scope.reporte_escaneado.Marca = "";
            $scope.reporte_escaneado.Ubicacion = "";
            $scope.reporte_escaneado.Serie = "";
            $scope.reporte_escaneado.Modelo = "";
            $scope.reporte_escaneado.Inventario = "";
//            $scope.reporte_escaneado.EquipoId = "";
            $scope.FotoEquipo = "";
            $scope.Usuarios = [];
            
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
                SedeId: "--",
                ServicioId: "--",
                EquipoId: "--",
                Ubicacion: "",
                Foto: "",
                Descripcion: "",
                ResponsableNombre: $rootScope.username.NombreCompleto,
                ResponsableCargo: $rootScope.username.Cargo,
    //            ResponsableFirma: $rootScope.username.FirmaJefe,
                ResponsableId: $rootScope.username.UserId,
                RecibeFecha: new Date(),
                RecibeHora: new Date(),
                RecibeNombre: "",
                RecibeCargo: "",
                RecibeEmail: "",
                EstadoFinal: [],
                TotalRepuesto: "",
                Fecha: new Date(),
                TipoServicio: "",
                Responsable: "",
                SolicitudId: null,
                TipoReporte: "Externo",
                CreatedBy: $rootScope.username.NombreUsuario
            };
            $scope.FotoEquipo = "";
            $scope.simpleTableOptions = {};
            _init();
            $('#ModalCrearreporte_escaneado').modal('hide');
            $scope.$apply();
        };
        //</editor-fold>
        function _init() {
            GetServicio();
            GetSede();
            if ($rootScope.HojaVidaRapida) {
                $scope.ChangeSede();
                $scope.ChangeServicio($rootScope.HojaVidaRapida.ServicioId);
                GetUsuarios();
                $scope.reporte_escaneado.Marca = $rootScope.HojaVidaRapida.Marca;
                $scope.reporte_escaneado.Modelo = $rootScope.HojaVidaRapida.Modelo;
                $scope.reporte_escaneado.Serie = $rootScope.HojaVidaRapida.Serie;
                $scope.reporte_escaneado.Inventario = $rootScope.HojaVidaRapida.Inventario;
                $scope.reporte_escaneado.Ubicacion = $rootScope.HojaVidaRapida.Ubicacion;
                $scope.reporte_escaneado.EquipoId = $rootScope.HojaVidaRapida.HojaVidaId;
            }
        }
        _init();

    }]);