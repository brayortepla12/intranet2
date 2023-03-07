app.controller('EquiposSistemasCtrl', ["$scope", "$rootScope", "HojaVidaSistemaService", "ServicioService", "SedeService", "EmpresaService", "EncabezadoService", "FrecuenciaService",
    "$filter",
    function ($scope, $rootScope, HojaVidaSistemaService, ServicioService, SedeService, EmpresaService, EncabezadoService, FrecuenciaService, $filter) {
        //<editor-fold defaultstate="collapsed" desc="Variables">
        let vm = $scope;
        
        vm.Frecuencias = [];
        vm.ToPrint = false;
        vm.cargado = false;
        vm.Servicios = [];
        vm.Sedes = [];
        vm.ServicioId = "--";
        vm.SedeId = "--";
        vm.Estado = "Activo";
        vm.simpleTableOptions = {};
        $rootScope.ficha = null;
        $rootScope.ver_reportes = false;
        $rootScope.solicitud = false;
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Botones">
        vm.PonerEnMantenimiento = (i) => {
            swal({
                title: "¿Deseas colocar este Equipo en mantenimiento?",
                text: "Equipo: " + vm.simpleTableOptions.data[i].Equipo,
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-info",
                confirmButtonText: "Cambiar Estado",
                cancelButtonText: "Cancelar",
                closeOnConfirm: false,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {
                    var obj = {
                        HojaVidaId: vm.simpleTableOptions.data[i].HojaVidaId,
                        ModifiedBy: $rootScope.username.NombreCompleto
                    };
                    var data = {
                        HojaVida_Mant: JSON.stringify([obj])
                    };
                    HojaVidaSistemaService.putHojaVida(data).then(function (d) {
                        if (typeof d.data === 'string') {
                            swal("Error!", d.data, "error");
                        } else {
                            swal("Enhorabuena!", "Se ha cambiado el estado del Equipo " + vm.simpleTableOptions.data[i].Equipo, "success");
                            vm.BuscarHojaVidas();
                        }
                    });
                } 
            });
        };
        vm.PonerActivo = (i) => {
            swal({
                title: "¿Deseas colocar este Equipo en activo?",
                text: "Equipo: " + vm.simpleTableOptions.data[i].Equipo,
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-info",
                confirmButtonText: "Cambiar Estado",
                cancelButtonText: "Cancelar",
                closeOnConfirm: false,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {
                    var obj = {
                        HojaVidaId: vm.simpleTableOptions.data[i].HojaVidaId,
                        ModifiedBy: $rootScope.username.NombreCompleto
                    };
                    var data = {
                        HojaVida_Act: JSON.stringify([obj])
                    };
                    HojaVidaSistemaService.putHojaVida(data).then(function (d) {
                        if (typeof d.data === 'string') {
                            swal("Error!", d.data, "error");
                        } else {
                            swal("Enhorabuena!", "Se ha cambiado el estado del Equipo " + vm.simpleTableOptions.data[i].Equipo, "success");
                            vm.BuscarHojaVidas();
                        }
                    });
                }
            });
        };
        vm.DeleteItem = function (i) {
            swal({
                title: "¿Deseas ELIMINAR este Equipo?",
                text: "Equipo a eliminar " + vm.simpleTableOptions.data[i].Equipo + " " + vm.simpleTableOptions.data[i].Serie,
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
                        HojaVidaId: vm.simpleTableOptions.data[i].HojaVidaId,
                        ModifiedBy: $rootScope.username.NombreCompleto
                    };
                    var data = {
                        HojaVida: JSON.stringify([obj])
                    };
                    HojaVidaSistemaService.DeleteHojaVida(data).then(function (d) {
                        if (typeof d.data === 'string') {
                            swal("Error!", d.data, "error");
                        } else {
                            swal("Enhorabuena!", "Se ha eliminado el Equipo " + vm.simpleTableOptions.data[i].Equipo, "success");
                            vm.BuscarHojaVidas();
                        }
                    });
                } else {
                    swal("Cancelado", "No se ha ELIMINADO el Equipo... :)", "error");
                }
            });

        };
        vm.BuscarHojaVidas = function () {
            if (vm.SedeId !== "--") {
                vm.cargado = false;
                HojaVidaSistemaService.GetHojaVidaSedeId(vm.SedeId, vm.ServicioId, vm.Estado).then(function (d) {
                    console.log(d.data);
                    vm.simpleTableOptions = {
                        data: [],
                        aoColumns: [
                            {mData: 'HojaVidaId'},
                            {mData: 'Equipo'},
                            {mData: 'TipoArticulo'},
                            {mData: 'Fabricante'},
                            {mData: 'NSerial'},
                            {mData: 'SO'},
                            {mData: 'Servicio'},
                            {mData: 'Ubicacion'},
                            {mData: 'Estado'},
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
                    vm.simpleTableOptions.data = SetFormat(d.data);
                    vm.cargado = true;
                });
            }
        };
        vm.ViewItem = function (i) {
            HojaVidaSistemaService.GetHojaVidaHojaVidaId(vm.simpleTableOptions.data[i].HojaVidaId).then(function (d) {
                console.log(vm.simpleTableOptions.data[i]);
                $rootScope.ficha = angular.copy((d.data[0]));
                $rootScope.ficha.Accesorios = JSON.parse($rootScope.ficha.Accesorios);
                $rootScope.solicitud = false;
            });
        };
        vm.Imprimir = function () {
            vm.ToPrint = true;
            setTimeout(function () {
                printDiv();
            }, 600);

        };
        vm.ImprimirBySERVICIO = function () {
            GetSede();
            $('#ServicioModal').modal('show');
            GetServicio();
        };
        vm.ChangeSede = function () {
            vm.ServicioId = null;
            GetServicio();
        };
        vm.DarBaja = function (i) {
            swal({
                title: "¿Deseas dar de BAJA este Equipo?",
                text: "Equipo a dar de BAJA " + vm.simpleTableOptions.data[i].Equipo + " " + vm.simpleTableOptions.data[i].Serie,
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
                        HojaVidaId: vm.simpleTableOptions.data[i].HojaVidaId,
                        ModifiedBy: $rootScope.username.NombreCompleto
                    };
                    var data = {
                        HojaVida_baja: JSON.stringify([obj])
                    };
                    HojaVidaSistemaService.DeleteHojaVida(data).then(function (d) {
                        if (typeof d.data === 'string') {
                            swal("Error!", d.data, "error");
                        } else {
                            swal("Enhorabuena!", "Se ha dado de baja el Equipo " + vm.simpleTableOptions.data[i].Equipo, "success");
                            vm.BuscarHojaVidas();
                        }
                    });
                } else {
                    swal("Cancelado", "No se ha Dado de BAJA el Equipo... :)", "error");
                }
            });

        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="consultas">
        function GetServicio() {
            ServicioService.getServicioBySede(vm.SedeId).then(function (c) {
                vm.Servicios = c.data;
                vm.cargado = false;
            });
        }
        vm.ChangeServicios = function () {
            GetServicio();
        };

        function GetServicio() {
            ServicioService.getServicioByUserId($rootScope.username.UserId).then(function (c) {
                vm.Servicios = $filter("orderBy")($filter('filter')(c.data, {SedeId: vm.SedeId}), "Nombre");
                vm.ServicioId = "TODO";
                vm.BuscarHojaVidas();
            });
        }
        function GetSede() {
            if(vm.Sedes.length == 0){
                vm.simpleTableOptions = {};
                vm.cargado = false;
                SedeService.getAllSedeByUserId($rootScope.username.UserId).then(function (c) {
                    vm.Sedes = c.data;
                    vm.SedeId = c.data[0].SedeId;
                    GetServicio();
                });
            }
        }
        function GetEmpresa() {
            EmpresaService.getEmpresa().then(function (e) {
                vm.Empresa = e.data;
            });
        }
        function GetEncabezado() {
            EncabezadoService.getEncabezado().then(function (e) {
                vm.Encabezado = e.data;
            });
        }
        function GetFrecuencia() {
            FrecuenciaService.getAllFrecuencia().then(function (c) {
                vm.Frecuencias = $filter("orderBy")(c.data, "Nombre");
            });
        }
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Helpers">
        function SetFormat(lst) {
            for (var i in lst) {
                if (lst[i].Estado !== 'Inactivo') {
                    lst[i].Opciones = '<a class="btn btn-success btn-xs icon-only white" href="/intranet-2/#/sistemas/ficha_tecnica_2/' + lst[i].HojaVidaId + '" target="_blank"' +
                            ' ><i class="fa fa-info-circle"></i></a><a class="btn btn-danger btn-xs icon-only white" onclick=\"angular.element(this).scope().DeleteItem(' + i + ')\" ' +
                            ' ><i class="fa fa-trash-o"></i></a><a class="btn btn-warning btn-xs icon-only" onclick=\"angular.element(this).scope().DarBaja(' + i + ')\" ' +
                            ' ><i class="fa fa-arrow-down"></i></a><a class="btn btn-default btn-xs icon-only" href="cld://' + lst[i].Equipo + '" target="_blank" ' +
                            ' ><i class="fa fa-eye"></i></a>';
                    if(lst[i].Estado !== 'Mantenimiento'){
                        lst[i].Opciones += '<a class="btn btn-info btn-xs icon-only" onclick=\"angular.element(this).scope().PonerEnMantenimiento(' + i + ')\"><i class="fas fa-screwdriver"></i></a>'
                    }else if(lst[i].Estado === 'Mantenimiento'){
                        lst[i].Opciones += '<a class="btn btn-info btn-xs icon-only" onclick=\"angular.element(this).scope().PonerActivo(' + i + ')\"><i class="fas fa-desktop"></i></a>'
                    }
                    if (lst[i].TipoArticulo === 'Impresora') {
                        lst[i].Opciones += '<a class="btn btn-default btn-morado btn-xs icon-only white" href="/intranet-2/#/sistemas/historial_recarga/' + lst[i].HojaVidaId + '" target="_blank"' +
                                ' ><i class="fa fa-print"></i></a>';
                    }
                } else {
                    lst[i].Opciones = "";
                }


            }
            return lst;
        }
        vm.ChangeServicios = function () {
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
                vm.ToPrint = false;
                vm.$apply();
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