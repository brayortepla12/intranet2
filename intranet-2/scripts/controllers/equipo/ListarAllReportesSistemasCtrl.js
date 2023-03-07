app.controller('ListarAllReportesSistemasCtrl', ["$scope", "$rootScope", "ReporteSistemaService", "$filter", "ServicioService", "SedeService",
    function ($scope, $rootScope, ReporteSistemaService, $filter, ServicioService, SedeService) {
        $scope.simpleTableOptions = null;
        $scope.SedeId = null;
        $scope.ServicioId = null;
        $scope.Servicios = [];
        $scope.Servicios2 = [];
        $scope.Year = new Date().getFullYear();
        $scope.Mes = 'TODOS';
        $scope.Servicios = [];
        $scope.Sedes = [];
        $scope.ServicioId = "TODOS";
        $scope.SedeId = "--";
        $scope.TipoServicio = "TODOS";
        $scope.TipoArticulo = "TODOS";
        _init();
        //<editor-fold defaultstate="collapsed" desc="Botones">
        $scope.ExportarExcel = () => {
             window.open(`http://${window.location.host}/Polivalente/api/ReporteSistema.php?UsuarioId_Exc=${$rootScope.username.UserId}&SedeId_Exc=${$scope.SedeId}&ServicioId_Exc=${$scope.ServicioId}&TipoServicio_Exc=${$scope.TipoServicio}&TipoArticulo_Exc=${$scope.TipoArticulo}`, '_blank');
        };
        $scope.DeleteReporte = function (i) {
            swal({
                title: "¿Deseas ELIMINAR este REPORTE?",
                text: "Nota: Este paso no se puede retroceder!",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "ELIMINAR!",
                cancelButtonText: "Cancelar!",
                closeOnConfirm: false,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {
                    $scope.simpleTableOptions.data[i].ModifiedBy = $rootScope.username.NombreUsuario;
                    var obj = {
                        Reporte: JSON.stringify([$scope.simpleTableOptions.data[i]]),
                        UserId: $rootScope.username.NombreUsuario
                    };
                    ReporteSistemaService.DeleteReporte(obj).then(function (d) {
                        console.log(d.data)
                        if (typeof d.data !== 'string') {
                            swal("Enhorabuena!", "Se ha ELIMINADO este reporte.", "success");
                            $scope.cargado = false;
                            $scope.simpleTableOptions = null;
                            $scope.Servicios = [];
                            _init();
                        } else {
                            swal("Error", d.data, "error");
                        }
                    });
                }
            });
        };
        $scope.ImprimirBySERVICIO = function () {
            GetSede();
            $('#ServicioModal').modal('show');
        };
        $scope.ReenviarEmail = function (i) {
            swal({
                title: "¿Deseas Reenviar el correo de notificación?",
                text: "Nota: Cuidado de hacer SPAM!",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Reenviar!",
                cancelButtonText: "Cancelar!",
                closeOnConfirm: false,
                closeOnCancel: false
            }, function (isConfirm) {
                if (isConfirm) {
                    ReporteSistemaService.SendEmail($scope.simpleTableOptions.data[i].ReporteId).then(function (d) {
                        if (typeof d.data !== 'string') {
                            swal("Enhorabuena!", "Se ha enviado el reporte para que el usuario pueda firmarlo.", "success");
                        } else {
                            swal("Error", d.data, "error");
                        }
                    });
                } else {
                    swal("Cancelado", "No se ha enviado el correo... :)", "error");
                }
            });
        };
        $scope.AutoFirmarTODO = function () {
            swal({
                title: "¿Deseas FIRMAR TODO?",
                text: "Nota: Cuidado de hacer SPAM!",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "FIRMAR!",
                cancelButtonText: "Cancelar!",
                closeOnConfirm: false,
                closeOnCancel: false
            }, function (isConfirm) {
                if (isConfirm) {

                    ReporteSistemaService.AutoFirmar($rootScope.username.UserId).then(function (d) {
                        console.log(d.data);
                        if (typeof d.data !== 'string') {
                            _init();
                            swal("Enhorabuena!", "Se han firmado todos los reportes.", "success");
                        } else {
                            swal("Error", d.data, "error");
                        }
                    });
                } else {
                    swal("Cancelado", "Tranquil@ no hubo ningun cambio... :)", "error");
                }
            });
        };
        $scope.BuscarReportes = () => {
            GetReportes();
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Consulta">
        $scope.ChangeSede = function () {
            $scope.ServicioId = "TODOS";
            GetServicio();
        };

        function GetServicio() {
            ServicioService.getServicioBySede($scope.SedeId).then(function (c) {
                $scope.Servicios = $filter("orderBy")($filter('filter')(c.data, {SedeId: $scope.SedeId}), "Nombre");
                $scope.ServicioId = "TODOS";
                $scope.BuscarReportes();
            });
        }
        function GetSede() {
            $scope.simpleTableOptions = {};
            $scope.cargado = false;
            SedeService.getAllSedeByUserId($rootScope.username.UserId).then(function (c) {
                $scope.Sedes = c.data;
                $scope.SedeId = c.data[0].SedeId;
                GetServicio();
            });
        }
        function GetReportes() {
            $scope.cargado = false;
            ReporteSistemaService.GetAllReportes($rootScope.username.UserId, $scope.SedeId, $scope.ServicioId, $scope.TipoServicio, $scope.TipoArticulo).then(function (d) {
                if ($scope.TipoArticulo == 'Impresora') {
                    $scope.simpleTableOptions = {
                        data: [],
                        aoColumns: [
                            {mData: 'ReporteId'},
                            {mData: 'Sede'},
                            {mData: 'Servicio'},
                            {mData: 'Ubicacion'},
                            {mData: 'TipoServicio'},
                            {mData: 'Solicitante'},
                            {mData: 'Equipo'},
                            {mData: 'NSerial'},
                            {mData: 'Contador'},
                            {mData: 'Fecha'},
                            {mData: 'CreatedBy'},
                            {mData: 'CreatedAt'},
                            {mData: 'Estado'},
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
                } else {
                    $scope.simpleTableOptions = {
                        data: [],
                        aoColumns: [
                            {mData: 'ReporteId'},
                            {mData: 'Sede'},
                            {mData: 'Servicio'},
                            {mData: 'Ubicacion'},
                            {mData: 'TipoServicio'},
                            {mData: 'Solicitante'},
                            {mData: 'Equipo'},
                            {mData: 'NSerial'},
                            {mData: 'Fecha'},
                            {mData: 'CreatedBy'},
                            {mData: 'CreatedAt'},
                            {mData: 'Estado'},
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
                }

                $scope.simpleTableOptions.data = SetFormat(d.data);
                $scope.cargado = true;
            });
        }
        function _init() {
            GetSede();
        }
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Helpers">
        function SetFormat(lst) {
            for (var i in lst) {
                lst[i].Opciones = '<a class="btn  btn-primary btn-xs icon-only white" href="/Polivalente/#/sistemas/ficha_tecnica_2/' + lst[i].HojaVidaId + '" target="_blank"><i class="fa fa-stethoscope"></i></a>' +
                        '<a class="btn  btn-info btn-xs icon-only white" href="/Polivalente/#/sistemas/reporte_servicio_sistemas/' + lst[i].ReporteId + '" target="_blank"><i class="fa fa-pencil-square-o"></i></a>' +
                        '<a class="btn  btn-danger btn-xs icon-only white" onclick=\"angular.element(this).scope().DeleteReporte(' + i + ')\" target="_blank"><i class="fa fa-trash"></i></a>';
                lst[i].TotalRepuesto = $filter('currency')(lst[i].TotalRepuesto);

                if (lst[i].Estado === 'Borrador' && $rootScope.Cargo === "COORDINADOR MANT. POLIVALENTE") {
                    lst[i].Opciones += '<a class="btn  btn-warning btn-xs icon-only white" onclick=\"angular.element(this).scope().ReenviarEmail(' + i + ')\"><i class="fa fa-paper-plane-o"></i></a>'
                }
            }
            return lst;
        }
        //</editor-fold>
    }]);



