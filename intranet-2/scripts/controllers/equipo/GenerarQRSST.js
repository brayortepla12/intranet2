app.controller('GenerarQRSST', ["$scope", "$rootScope", "HojaVidaSSTService", "ServicioService", "SedeService", "EmpresaService", "EncabezadoService", "FrecuenciaService",
    "$filter",
    function ($scope, $rootScope, HojaVidaSSTService, ServicioService, SedeService, EmpresaService, EncabezadoService, FrecuenciaService, $filter) {
        //<editor-fold defaultstate="collapsed" desc="Variables">
        $scope.Servicios = [];
        $scope.Sedes = [];
        $scope.ServicioId = "--";
        $scope.SedeId = "--";
        $scope.HojasVida = [];
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Botones">
        $scope.BuscarHojaVidas = function () {
            if ($scope.SedeId != "--") {
                $scope.cargado = false;
                HojaVidaSSTService.GetHojaVidaServicioId($scope.ServicioId).then(function (d) {
                    console.log(d.data);
                    $scope.HojasVida = d.data;
                });
            }
        };
        $scope.Imprimir = function () {
            $scope.ToPrint = true;
            setTimeout(function () {
                printDiv();
            }, 600);

        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="consultas">
        function GetServicio() {
            ServicioService.getServicioBySede($scope.SedeId).then(function (c) {
                $scope.Servicios = c.data;
                $scope.cargado = false;
            });
        }
        function GetSede() {
            $scope.simpleTableOptions = {};
            $scope.cargado = false;
            SedeService.getAllSedeByUserId($rootScope.username.UserId).then(function (c) {
                $scope.Sedes = c.data;
                if ($scope.Sedes.length == 1) {
                    $scope.SedeId = $scope.Sedes[0].SedeId;
                    GetServicio();
                }
            });
        }
        function GetEmpresa() {
            EmpresaService.getEmpresa().then(function (e) {
                $scope.Empresa = e.data;
            });
        }
        function GetEncabezado() {
            EncabezadoService.getEncabezado().then(function (e) {
                $scope.Encabezado = e.data;
            });
        }
        function GetFrecuencia() {
            FrecuenciaService.getAllFrecuencia().then(function (c) {
                $scope.Frecuencias = $filter("orderBy")(c.data, "Nombre");
            });
        }
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Helpers">
        
        $scope.ChangeServicios = function () {
            GetServicio();
        };
        function printDiv() {
            $("#QrCode").print({
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
        }
        _init();
    }]);


