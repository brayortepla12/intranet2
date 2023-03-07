app.controller('EquiposCtrl', ["$scope", "$rootScope", "HojaVidaService", "ServicioService", "SedeService", "EmpresaService","EncabezadoService","FrecuenciaService",
    "$filter",
    function ($scope, $rootScope, HojaVidaService, ServicioService, SedeService, EmpresaService,EncabezadoService,FrecuenciaService,$filter) {
        //<editor-fold defaultstate="collapsed" desc="Variables">
        $scope.Frecuencias = [];
        $scope.ToPrint = false;
        $scope.cargado = false;
        $scope.Servicios = [];
        $scope.Sedes = [];
        $scope.ServicioId = "--";
        $scope.SedeId = "--";
        $scope.Estado = "Activo";
        $scope.simpleTableOptions = {};
        $rootScope.ficha = null;
        $rootScope.ver_reportes = false;
        $rootScope.solicitud = false;
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Botones">
        $scope.DeleteItem = function (i) {
            swal({
                title: "¿Deseas ELIMINAR este Equipo?",
                text: "Equipo a eliminar " + $scope.simpleTableOptions.data[i].Equipo + " " + $scope.simpleTableOptions.data[i].Serie,
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Eliminar!",
                cancelButtonText: "Cancelar!",
                closeOnConfirm: false,
                closeOnCancel: false
            }, function (isConfirm) {
                if (isConfirm) {
                    var obj = {
                        HojaVidaId: $scope.simpleTableOptions.data[i].HojaVidaId,
                        ModifiedBy: $rootScope.username.NombreCompleto
                    };
                    var data = {
                        HojaVida: JSON.stringify([obj])
                    };
                    HojaVidaService.DeleteHojaVida(data).then(function (d) {
                        if (typeof d.data === 'string') {
                            swal("Error!", d.data, "error");
                        } else {
                            swal("Enhorabuena!", "Se ha eliminado el Equipo " + $scope.simpleTableOptions.data[i].Equipo, "success");
                            _init();
                        }
                    });
                } else {
                    swal("Cancelado", "No se ha ELIMINADO el Equipo... :)", "error");
                }
            });

        };
        
        $scope.DarBaja = function (i) {
            swal({
                title: "¿Deseas dar de BAJA este Equipo?",
                text: "Equipo a dar de BAJA " + $scope.simpleTableOptions.data[i].Equipo + " " + $scope.simpleTableOptions.data[i].Serie,
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-warning",
                confirmButtonText: "Dar de BAJA!",
                cancelButtonText: "Cancelar!",
                closeOnConfirm: false,
                closeOnCancel: false
            }, function (isConfirm) {
                if (isConfirm) {
                    var obj = {
                        HojaVidaId: $scope.simpleTableOptions.data[i].HojaVidaId,
                        ModifiedBy: $rootScope.username.NombreCompleto
                    };
                    var data = {
                        HojaVida_baja: JSON.stringify([obj])
                    };
                    HojaVidaService.DeleteHojaVida(data).then(function (d) {
                        if (typeof d.data === 'string') {
                            swal("Error!", d.data, "error");
                        } else {
                            swal("Enhorabuena!", "Se ha dado de baja el Equipo " + $scope.simpleTableOptions.data[i].Equipo, "success");
                            _init();
                        }
                    });
                } else {
                    swal("Cancelado", "No se ha Dado de BAJA el Equipo... :)", "error");
                }
            });

        };
        
        
        $scope.BuscarHojaVidas = function () {
            if ($scope.SedeId !== "--") {
                $scope.cargado = false;
                HojaVidaService.GetHojaVidaSedeId($scope.SedeId, $scope.ServicioId, $scope.Estado).then(function (d) {
                    console.log(d.data);
                    $scope.simpleTableOptions = {
                        data: [],
                        aoColumns: [
                            {mData: 'HojaVidaId'},
                            {mData: 'Equipo'},
                            {mData: 'Serie'},
                            {mData: 'Servicio'},
                            {mData: 'Ubicacion'},
                            {mData: 'Opciones'}
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
                    $scope.simpleTableOptions.data = SetFormat(d.data);
                    $scope.cargado = true;
                });
            }
        };
        $scope.ViewItem = function (i) {
            HojaVidaService.GetHojaVidaHojaVidaId($scope.simpleTableOptions.data[i].HojaVidaId).then(function (d) {
                console.log($scope.simpleTableOptions.data[i]);
                $rootScope.ficha = angular.copy((d.data[0]));
                $rootScope.ficha.Accesorios = JSON.parse($rootScope.ficha.Accesorios);
                $rootScope.solicitud = false;
            });
        };
        $scope.Imprimir = function () {
            $scope.ToPrint = true;
            setTimeout(function () {
                printDiv();
            }, 600);

        };
        $scope.ImprimirBySERVICIO = function () {
            GetSede();
            $('#ServicioModal').modal('show');
            GetServicio();
        };
        $scope.ChangeSede = function(){
            $scope.ServicioId = null;
            GetServicio();
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="consultas">
        function GetServicio() {
            ServicioService.getServicioByUserId($rootScope.username.UserId).then(function (c) {
                $scope.Servicios = $filter("orderBy")($filter('filter')(c.data, {SedeId: $scope.SedeId}), "Nombre");
                $scope.ServicioId = "TODO";
                $scope.BuscarHojaVidas();
            });
        }
        function GetSede() {
            $scope.simpleTableOptions = {};
            $scope.cargado = false;
            SedeService.getAllSedeByUserId($rootScope.username.UserId).then(function (c) {
                $scope.Sedes = c.data;
                $scope.SedeId = c.data[0].SedeId;
                $scope.ChangeSede();
            });
        }
        function GetEmpresa() {
            EmpresaService.getEmpresa().then(function (e) {
                $scope.Empresa =  e.data;
            });
        }
        function GetEncabezado() {
            EncabezadoService.getEncabezado().then(function (e) {
                $scope.Encabezado = e.data;
            });
        }
        function GetFrecuencia() {
            FrecuenciaService.getAllFrecuencia().then(function (c) {
                $scope.Frecuencias = $filter("orderBy")(c.data,"Nombre");
            });
        }
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Helpers">
        function SetFormat(lst) {
            for (var i in lst) {
                if (lst[i].Estado != 'Inactivo') {
                    lst[i].Opciones = '<a class="btn btn-success btn-xs icon-only white" href="/Polivalente#/polivalente/ficha_tecnica/' + lst[i].HojaVidaId + '" target="_blank"' +
                        ' ><i class="fa fa-info-circle"></i></a><a class="btn btn-danger btn-xs icon-only white" onclick=\"angular.element(this).scope().DeleteItem(' + i + ')\" ' +
                        ' ><i class="fa fa-trash-o"></i></a><a class="btn btn-warning btn-xs icon-only white" onclick=\"angular.element(this).scope().DarBaja(' + i + ')\" ' +
                        ' ><i class="fa fa-arrow-down"></i></a>';
                }else{
                    lst[i].Opciones = "";
                }
            }
            return lst;
        }
        $scope.ChangeServicios = function () {
            GetServicio();
        };
        function printDiv() {
            $("#dospaginas").print({
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

            setTimeout(function () {
                $scope.ToPrint = false;
                $scope.$apply();
            }, 1000);
        }
        //</editor-fold>
        function _init() {
            GetSede();
            GetEmpresa();
            GetEncabezado();
            GetFrecuencia();
        }
        _init();
    }]);