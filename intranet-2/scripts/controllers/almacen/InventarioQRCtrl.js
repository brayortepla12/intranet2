app.controller('InventarioQRCtrl', ["$scope", "$rootScope", "HojaVidaService", "ServicioService", "SedeService", "EmpresaService", "EncabezadoService", "FrecuenciaService",
    "$filter",
    function ($scope, $rootScope, HojaVidaService, ServicioService, SedeService, EmpresaService, EncabezadoService, FrecuenciaService, $filter) {
        //<editor-fold defaultstate="collapsed" desc="Variables">
        let vm = $scope;
        vm.Servicios = [];
        vm.Sedes = [];
        vm.ServicioId = "--";
        vm.TipoAplicacion = "Polivalente";
        vm.SedeId = "--";
        vm.HojasVida = [];
        vm.NombreSede = "";
        vm.QRS = false;
        vm.Contador = 1;
        vm.ToPrint = false;
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Botones">
        vm.BuscarHojaVidas = function () {
            if (vm.SedeId != "--") {
                vm.cargado = false;
                vm.QRS = false;
                let cont = angular.copy(vm.Contador);
                HojaVidaService.GetHojaVidaServicioIdWithTA(vm.ServicioId, vm.TipoAplicacion).then(function (d) {
                    vm.HojasVida = d.data;
                    String.prototype.lpad = function (padString, length) {
                        var str = this;
                        while (str.length < length)
                            str = padString + str;
                        return str;
                    };
                    vm.HojasVida.map(h => {
                        h.Contador = (cont + "").lpad("0", 8);
                        cont++;
                    });
                    if (d.data.length > 0) {
                        vm.QRS = true;
                    }
                });
            }
        };
        vm.Imprimir = function () {
            vm.ToPrint = true;
            setTimeout(function () {
                printDiv();
            }, 600);

        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="consultas">
        function GetServicio() {
//            vm.Sedes.find(s => {
//                if(s.SedeId == vm.SedeId){
//                    vm.NombreSede = s.Nombre;
//                }
//            });
            vm.QRS = false;
            ServicioService.getServicioBySedeWithTA(vm.SedeId, $rootScope.username.UserId, vm.TipoAplicacion).then(function (c) {
                vm.Servicios = c.data;
                vm.ServicioId = "--";
                vm.cargado = false;
            });
        }
        function GetSede() {
            vm.simpleTableOptions = {};
            vm.cargado = false;
            vm.QRS = false;
            SedeService.getAllSedeByUserId($rootScope.username.UserId).then(function (c) {
                vm.Sedes = c.data;
                if (vm.Sedes.length == 1) {
                    vm.SedeId = vm.Sedes[0].SedeId;
                    GetServicio();
                }
            });
        }
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Helpers">

        vm.ChangeServicios = function () {
            GetServicio();
        };
        function printDiv() {
            $("#QrCode").print({
                globalStyles: true,
                stylesheet: "/intranet-2/public_html/styles/Inventario.css",
                noPrintSelector: ".no-print",
                iframe: true,
                append: null,
                prepend: null,
                manuallyCopyFormValues: true,
                timeout: 1250,
                title: null,
                doctype: '<!doctype html>',
                deferred: $.Deferred().done(function () {
                    swal({
                        title: `¿Has realizado la impresión?`,
                        text: "Nota: Al notificar que SI se realizó, el contador aumentara automaticamente.",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonClass: "btn-success",
                        confirmButtonText: "SI",
                        cancelButtonText: "NO",
                        closeOnConfirm: true,
                        closeOnCancel: true
                    }, function (isConfirm) {
                        if (isConfirm) {
                            vm.Contador += vm.HojasVida.length;
                        }
                    });
                })
            });
            setTimeout(function () {
                vm.ToPrint = false;
                vm.$apply();
            }, 1000);
        }
        //</editor-fold>
        function _init() {
            GetSede();
        }
        _init();
    }]);