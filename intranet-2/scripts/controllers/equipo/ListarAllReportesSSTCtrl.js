app.controller('ListarAllReportesSSTCtrl', ["$scope", "$rootScope", "ReporteSSTService", "$filter", "ServicioService", "SedeService",
    function ($scope, $rootScope, ReporteSSTService, $filter, ServicioService, SedeService) {
        $scope.simpleTableOptions = null;
        $scope.SedeId = null;
        $scope.ServicioId = null;
        $scope.Servicios = [];
        $scope.Servicios2 = [];
        $scope.Year = new Date().getFullYear();
        $scope.Mes = '0';
        _init();
        //<editor-fold defaultstate="collapsed" desc="Botones">
        
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
                    ReporteSSTService.DeleteReporte(obj).then(function (d) {
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
                    ReporteSSTService.SendEmail($scope.simpleTableOptions.data[i].ReporteId).then(function (d) {
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

                    ReporteSSTService.AutoFirmar($rootScope.username.UserId).then(function (d) {
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
        $scope.ChangeSede = function(){
            $scope.Servicios2 = angular.copy($filter("filter")($scope.Servicios, {SedeId: parseInt($scope.SedeId)}, true));
            $scope.ServicioId = null;
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Consulta">
        function GetServicio() {
            ServicioService.getServicioByUserId($rootScope.username.UserId).then(function (c) {
                $scope.Servicios = $filter("orderBy")(angular.copy(c.data),"Nombre");
                $scope.ChangeSede();
            });
        }
        function GetSede() {
            SedeService.getAllSedeByUserId($rootScope.username.UserId).then(function (c) {
                $scope.Sedes = $filter("orderBy")(c.data, "Nombre");
                $scope.SedeId = $scope.Sedes[0].SedeId;
                GetServicio();
            });
        }
        function GetReportes() {
            ReporteSSTService.GetAllReportes($rootScope.username.UserId).then(function (d) {
                console.log(d.data);
                $scope.simpleTableOptions = {
                    data: [],
                    aoColumns: [
                        {mData: 'ReporteId'},
                        {mData: 'Sede'},
                        {mData: 'Servicio'},
                        {mData: 'Ubicacion'},
                        {mData: 'FechaRecarga'},
                        {mData: 'FechaVencimiento'},
                        {mData: 'Nombre'},
                        {mData: 'CreatedBy'},
                        {mData: 'CreatedAt'},
                        {mData: 'Estado'},
                        {mData: 'Opciones'},
                    ],
                    "searching": true,
                    //                "sDom": "Tflt<'row DTTTFooter'<'col-sm-6'i><'col-sm-6'p>>",
                    //                "oTableTools": {
                    //                    "aButtons": [
                    //                        "xls", "pdf"
                    //                    ],
                    //                    "sSwfPath": "assets/swf/copy_csv_xls_pdf.swf"
                    //                },
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
                $scope.simpleTableOptions.data = SetFormat(d.data);
                $scope.cargado = true;
            });
        }
        function _init() {
            GetReportes();
        }
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Helpers">
        function SetFormat(lst) {
            for (var i in lst) {
                lst[i].Opciones = '<a class="btn  btn-primary btn-xs icon-only white" href="/Polivalente/#/sst/ficha_tecnica_3/' + lst[i].HojaVidaId + '" target="_blank"><i class="fa fa-stethoscope"></i></a>' +
                        '<a class="btn  btn-info btn-xs icon-only white" href="/Polivalente/#/sst/reporte_servicio_sst/' + lst[i].ReporteId + '" target="_blank"><i class="fa fa-pencil-square-o"></i></a>' +
                        '<a class="btn  btn-danger btn-xs icon-only white" onclick=\"angular.element(this).scope().DeleteReporte(' + i + ')\" target="_blank"><i class="fa fa-trash"></i></a>';
                
            }
            return lst;
        }
        //</editor-fold>
    }]);



