'use strict';
app.controller('CronogramaCtrl', ["$scope", "$rootScope", "CronogramaService", "SedeService", "ServicioService", "$filter", "HojaVidaService", "FrecuenciaService",
    function ($scope, $rootScope, CronogramaService, SedeService, ServicioService, $filter, HojaVidaService, FrecuenciaService) {
        //<editor-fold defaultstate="collapsed" desc="inicializar variables">
        $scope.Cronogramas = [];
        $scope.Servicios = [];
        $scope.Sedes = [];
        $scope.Equipos = [];
        $scope.Frecuencias = [];
        $scope.Cronograma = {
            Nombre: null,
            Inicio: 1,
            SedeId: null,
            ServicioId: null,
            HojaVidaId: null,
            FrecuenciaMantenimientoId: null,
            CreatedBy: $rootScope.username.NombreUsuario
        };
        $scope.selected = {};
        $scope.select = {};
        // inicializamos las variables en _init()
        _init();

        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Consultas">
        function GetCronograma() {
            CronogramaService.getAllCronograma().then(function (c) {
                console.log(c.data)
                $scope.Cronogramas = c.data;
            });
        }
        function GetServicio() {
            ServicioService.getServicioByUserId($rootScope.username.UserId).then(function (c) {
                $scope.Servicios = $filter("orderBy")($filter('filter')(c.data, {SedeId: $scope.Cronograma.SedeId}), "Nombre");
            });
        }
        function GetSede() {
            SedeService.getAllSedeByUserId($rootScope.username.UserId).then(function (c) {
                $scope.Sedes = $filter("orderBy")(c.data, "Nombre");
                if ($scope.Sedes.length === 1) {
                    $scope.Cronograma.SedeId = $scope.Sedes[0].SedeId;
                    GetServicio();
                }
            });
        }
        function GetFrecuencia() {
            FrecuenciaService.getAllFrecuencia().then(function (c) {
                $scope.Frecuencias = c.data;
            });
        }
        $scope.ChangeServicios = function () {
            GetServicio();
            $scope.Cronograma.ServicioId = null;
            $scope.Cronograma.HojaVidaId = null;
        };
        $scope.ChangeEquipos = function () {
            GetEquipos();
        };
        $scope.ChangeEquipos2 = function () {
            $scope.Equipos = [];
            HojaVidaService.GetHojaVida($scope.select.ServicioId).then(function (e) {
                $scope.Equipos = $filter("orderBy")(e.data, "Equipo");
            });
        };
        function GetEquipos() {
            HojaVidaService.GetHojaVida($scope.Cronograma.ServicioId).then(function (e) {
                $scope.Equipos = $filter("orderBy")(e.data, "Equipo");
            });
        }
        $scope.ChangeEquipo = function (i) {
            $scope.Cronograma.HojaVidaId = $scope.Equipos[i].HojaVidaId;
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Botones guardar e imprimir">
        $scope.Delete = function (o) {
            swal({
                title: "¿Deseas eliminar este cronograma?",
                text: "NOTA: este paso no se puede deshacer.",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Eliminar!",
                closeOnConfirm: false
            },
            function () {
                var data = {
                    Cronograma: JSON.stringify([o])
                };
                CronogramaService.DeleteCronograma(data).then(function (d) {
                    if (typeof d.data !== "string") {
                        swal("Hubo un Error", d.data, "error");
                    } else {
                        swal("Enhorabuena!!", "Se ha eliminado este cronograma correctamente", "success");
                        _init();
                    }
                }, function (e) {
                    swal("Hubo un Error", e, "error");
                });
            });

        };
        $scope.GuardarCronograma = function () {
            var data = {
                Cronograma: JSON.stringify([$scope.Cronograma])
            };
            CronogramaService.PostCronograma(data).then(function (d) {
                if (typeof d.data !== "object") {
                    swal("Hubo un Error", d.data, "error");
                } else {
                    swal("Enhorabuena!!", "Se han guardado los datos satisfactoriamente", "success");
                    $scope.Cronograma = {
                        Nombre: null,
                        SedeId: null,
                        Inicio: 1,
                        ServicioId: null,
                        HojaVidaId: null,
                        FrecuenciaMantenimientoId: null,
                        CreatedBy: $rootScope.username.NombreUsuario
                    };
                    $scope.Cronogramas = [];
                    $scope.Servicios = [];
                    $scope.Sedes = [];
                    $scope.Equipos = [];
                    $scope.Frecuencias = [];
                    $scope.Cronograma = {
                        Nombre: null,
                        SedeId: null,
                        ServicioId: null,
                        HojaVidaId: null,
                        FrecuenciaMantenimientoId: null,
                        CreatedBy: $rootScope.username.NombreUsuario
                    };
                    $scope.selected = {};
                    $scope.select = {};
                    $('#CronogramaModal').modal('hide');
                    _init();
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

            GetCronograma();
            GetSede();
            GetFrecuencia();
        }
        //<editor-fold defaultstate="collapsed" desc="Codigo para el editar en linea">
        $scope.getTemplate = function (item) {
            if (item.CronogramaId === $scope.selected.CronogramaId) {
                return 'edit';
            } else
                return 'display';
        };
        $scope.editObj = function (item) {
            $scope.ItemA = "";
            $scope.select = item;
            $scope.selected = angular.copy(item);
            $scope.ChangeEquipos2();
        };
        $scope.reset = function () {
            $scope.selected = {};
        };
        $scope.Actualizar = function () {
            $scope.select.ModifiedBy = $rootScope.username.UserId;
            var obj = {
                Cronograma: JSON.stringify([$scope.select]),
                ID: $scope.select.CronogramaId
            };
            CronogramaService.PutCronograma(obj).then(function (d) {
                if (typeof d.data !== "object") {
                    swal("Hubo un Error", d.data, "error");
                } else {
                    swal("Enhorabuena!!", "Se han guardado los datos satisfactoriamente", "success");
                    $scope.select = {};
                    $scope.selected = {};
                    GetCronograma();
                }
            }, function (e) {
                swal("Hubo un Error", e, "error");
            });
        };
        //</editor-fold>


    }]);





