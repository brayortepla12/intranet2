app.controller('VerHojaVidaCtrl', ["$scope", "$stateParams", 'HojaVidaService', 'SesionService', 'EncabezadoService', 'EmpresaService',
    function ($scope, $stateParams, HojaVidaService, SesionService, EncabezadoService, EmpresaService) {
        //<editor-fold defaultstate="collapsed" desc="Variables">
        $scope.Hojas = [];
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Botones">
       
        $scope.Imprimir = function () {
            printDiv();
        };
        
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Helpers">
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
                timeout: 3050,
                title: null,
                doctype: '<!doctype html>'
            });

        }
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Consultas">
        function GetHojasByServicioId(ServicioId) {
            HojaVidaService.GetHojaVidaByServicio(ServicioId).then(function (d) {
                console.log(d.data)
                if (typeof d.data !== "string") {
                    $scope.Hojas = setFormat(d.data);
                } else {
                    swal("Error", d.data, "error");
                }
            }, function (e) {
                swal("Error", e, "error");
            })
        }
        function setFormat(lst) {
            for (var i in lst) {
                lst[i].Accesorios = JSON.parse(lst[i].Accesorios);
            }
            return lst;
        }
        function GetEncabezado() {
            EncabezadoService.getEncabezado().then(function (e) {
                $scope.Encabezado = e.data;
            });
        }
        function GetEmpresa() {
            EmpresaService.getEmpresa().then(function (e) {
                $scope.Empresa = e.data;
            });
        }
        function _init() {
            GetHojasByServicioId($stateParams.servicio_id);
            GetEncabezado();
            GetEmpresa();
        }
        $scope.isInArray = function (txt, lst) {
            for (var i in lst) {
                if (txt == lst[i]) {
                    return true;
                }
            }
            return false;
        };
        $scope.getDiffFalla = function (lst) {
            for (var i in lst) {
                if (lst[i] != "MAL USO" && lst[i] != "ACCESORIOS" && lst[i] != "DESGASTE" && lst[i] != "SIN FALLA") {
                    return lst[i];
                }
            }
        }
        //</editor-fold>
//        printDiv();
        _init();
    }]);



