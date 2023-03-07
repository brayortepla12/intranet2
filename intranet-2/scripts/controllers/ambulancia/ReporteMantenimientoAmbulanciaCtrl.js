'use strict';
app.controller('ReporteMantenimientoAmbulanciaCtrl', ["$scope", "$rootScope", "$stateParams", "$crypto", "ReporteAmbulanciaService", "SedeService",
    "DetalleAmbulanciaService", "$filter", "HojaVidaAmbulanciaService", "KMService",
    function ($scope, $rootScope, $stateParams, $crypto, ReporteAmbulanciaService, SedeService, DetalleAmbulanciaService,
            $filter, HojaVidaAmbulanciaService, KMService) {
        //<editor-fold defaultstate="collapsed" desc="inicializar variables">
        $scope.BSaveReport = false;
        $scope.SolicitudMantenimiento = null;
        $scope.cargado = false;
        $scope.UpdateBandera = false;
        $scope.simpleTableOptions = {};
        $scope.labels = {
            "itemsSelected": "Seleccionados",
            "selectAll": "Seleccionar Todo",
            "unselectAll": "Deseleccionar Todo",
            "search": "Buscar",
            "select": "Seleccionar"
        };
        $scope.MinKM = 0;
        $scope.ItemsCronograma = [];
        $scope.Sistemas = [];
        $scope.Reportes = [];
        $scope.FechaActual = new Date();
        $scope.Sedes = [];
        $scope.Equipos = [];
        $scope.Detalles = [];
        $scope.DetallesSeleccionados = [];
        $scope.SedeId = '';
        $scope.Reporte = {
            SedeId: null,
            HojaVidaId: null,
            Fecha: "",
            SolicitudMantenimientoId: null,
            Descripcion: "",
            Notas: "",
            Km: "",
            LastKm: "",
            Detalles: [],
            CreatedBy: $rootScope.username.NombreUsuario
        };
        // inicializamos las variables en _init()
        _init();
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Botones">
        $scope.OpenModal = () => {
            $scope.Equipos = [];
            $scope.DetallesSeleccionados = [];
            $scope.Reporte = {
                SedeId: null,
                HojaVidaId: null,
                Fecha: "",
                SolicitudMantenimientoId: null,
                Descripcion: "",
                Notas: "",
                Km: "",
                LastKm: "",
                Detalles: [],
                CreatedBy: $rootScope.username.NombreUsuario
            };
            $scope.UpdateBandera = false;
            $("#ReporteModal").modal('show');
        };
        $scope.ChangeSede = () => {
            HojaVidaAmbulanciaService.GetHojaVidaSedeId($scope.Reporte.SedeId).then((d) => {
                console.log(d.data);
                $scope.Equipos = d.data;
            });
        };
        $scope.BuscarReportes = () => {
            GetReportes($scope.SedeId);
        };
        $scope.EditarReporte = (ReporteId) => {
            ReporteAmbulanciaService.GetReporteById(ReporteId).then((d) => {
                $scope.ChangeSede();
                console.log(d.data);
                $scope.Reporte = d.data[0];
                $scope.DetallesSeleccionados = $scope.Reporte.Detalles;
                $scope.UpdateBandera = true;
                $("#ReporteModal").modal('show');
            });
        };
        $scope.ChangeEquipo = function () {
            GetLasKM($scope.Reporte.HojaVidaId);
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Consultas">
        function GetReportes(SedeId) {
            $scope.cargado = false;
            ReporteAmbulanciaService.GetAllReportes(SedeId).then(function (c) {
                $scope.simpleTableOptions = {
                    data: [],
                    aoColumns: [
                        {mData: 'ReporteId'},
                        {mData: 'Fecha'},
                        {mData: 'Placa'},
                        {mData: 'TipoMantenimiento'},
                        {mData: 'Descripcion'},
                        {mData: 'LastKm'},
                        {mData: 'Km'},
                        {mData: 'Opciones'},
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
                $scope.simpleTableOptions.data = SetFormat(c.data);
                $scope.cargado = true;
            });
        }
        function GetSede() {
            SedeService.getAllSedeByUserId($rootScope.username.UserId).then(function (c) {
                $scope.Sedes = $filter("orderBy")(c.data, "Nombre");
                $scope.SedeId = $scope.Sedes[0].SedeId;
                if ($scope.Sedes.length === 1) {
                    $scope.Reporte.SedeId = $scope.Sedes[0].SedeId;
                }
            }).then(() => {
                GetReportes($scope.SedeId);
            });
        }
        function GetDetalles() {
            DetalleAmbulanciaService.getall().then(function (c) {
                $scope.Detalles = c.data;
            });
        }
        function GetLasKM(HojaVidaId) {
            KMService.getLastKMByHojaVidaId(HojaVidaId).then(function (c) {
                if (typeof c.data === "string") {
                    swal("Error", c.data, "error");
                }
                $scope.Reporte.Km = c.data.length > 0 ? parseInt(c.data[0].Km) : 0;
                $scope.MinKM = c.data.length > 0 ? parseInt(c.data[0].Km) : 0;
                $scope.Reporte.LastKm = c.data.length > 0 ? parseInt(c.data[0].Km) : 0;
            });
        }
        function GuardarReporte() {
            $scope.Reporte.Detalles = JSON.stringify($scope.DetallesSeleccionados);
            $scope.Reporte.CreatedBy = $rootScope.username.NombreCompleto;
            var data = {
                Reporte: JSON.stringify($scope.Reporte)
            };
            ReporteAmbulanciaService.postReporte(data).then(function (d) {
                if (typeof d.data === "string") {
                    swal("Hubo un Error", d.data, "error");
                } else {
                    swal("Enhorabuena!!", "Se han guardado los datos satisfactoriamente", "success");
                    $scope.Reporte = {
                        SedeId: null,
                        HojaVidaId: null,
                        Fecha: "",
                        SolicitudMantenimientoId: null,
                        Descripcion: "",
                        Notas: "",
                        Km: "",
                        LastKm: "",
                        Detalles: [],
                        CreatedBy: $rootScope.username.NombreUsuario
                    };
                    $scope.DetallesSeleccionados = [];
                    $scope.Reportes = [];
                    $scope.Equipos = [];
                    $('#ReporteModal').modal('hide');
                    GetReportes($scope.SedeId);
//                    _init();
                    $scope.BSaveReport = false;
                }
            }, function (e) {
                swal("Hubo un Error", e, "error");
                $scope.BSaveReport = false;
            });
        }
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Botones guardar e imprimir">
        $scope.Delete = function (o) {
            o.ModifiedBy = $rootScope.username.NombreCompleto;
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
                            Reporte: JSON.stringify([o])
                        };
                        ReporteAmbulanciaService.DeleteReporte(data).then(function (d) {
                            if (typeof d.data === "string") {
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
        $scope.GuardarReporte = function () {
            if (!$scope.BSaveReport) {
                $scope.BSaveReport = true;
                GuardarReporte();
            }
        };
        $scope.ActualizarReporte = () => {
            $scope.Reporte.ModifiedBy = $rootScope.username.NombreCompleto;
            $scope.Reporte.Detalles = JSON.stringify($scope.DetallesSeleccionados);
            var obj = {
                Reporte: JSON.stringify($scope.Reporte)
            };
            ReporteAmbulanciaService.PutReporte(obj).then(function (d) {
                if (typeof d.data === "string") {
                    swal("Hubo un Error", d.data, "error");
                } else {
                    swal("Enhorabuena!!", "Se han guardado los datos satisfactoriamente", "success");
                    $scope.Reporte = {
                        SedeId: null,
                        HojaVidaId: null,
                        Fecha: "",
                        SolicitudMantenimientoId: null,
                        Descripcion: "",
                        Notas: "",
                        Km: "",
                        LastKm: "",
                        Detalles: [],
                        CreatedBy: $rootScope.username.NombreUsuario
                    };
                    $scope.DetallesSeleccionados = [];
                    $scope.Reportes = [];
                    $scope.Equipos = [];
                    $('#ReporteModal').modal('hide');
                    GetReportes($scope.SedeId);
                }
            }, function (e) {
                swal("Hubo un Error", e, "error");
            });
        };
        $scope.Imprimir = function () {
            $.print("#printable");
        };
        //</editor-fold>

        function SetFormat(lst) {
            let hoy = moment().format().split('T')[0];
            for (var i in lst) {
                lst[i].Opciones = '';
                console.log(hoy)
                if (lst[i].Fecha == hoy) {
                    lst[i].Opciones = '<a class="btn btn-success btn-xs icon-only white" onclick=\"angular.element(this).scope().EditarReporte(' + lst[i].ReporteId + ')\" target="_blank"' +
                            ' ><i class="fa fa-pencil"></i></a>';
                    lst[i].Opciones += '<a class="btn btn-danger btn-xs icon-only white" onclick=\"angular.element(this).scope().DeleteItem(' + lst[i].ReporteId + ')\" ' +
                            ' ><i class="fa fa-trash-o"></i></a>';
                }

            }
            return lst;
        }
        function _init() {
            GetSede();
            GetDetalles();
            if ($stateParams.SolicitudMantenimiento) {
                $scope.SolicitudMantenimiento = JSON.parse($crypto.decrypt($stateParams.SolicitudMantenimiento, 'Franklin Ospino'));
            } else {
                $scope.SolicitudMantenimiento = null;
            }
        }


    }]);







