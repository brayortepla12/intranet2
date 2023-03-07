app.controller('VerPermisosCTCtrl', ["$scope", "$rootScope", "$state", "$filter", "PermisoCTService", "SedeService",
    function ($scope, $rootScope, $state, $filter, PermisoCTService, SedeService) {
        //<editor-fold defaultstate="collapsed" desc="Variables">
        $scope.Permisos = [];
        $scope.Sedes = [];
        $scope.Mes = moment().format('M');
        $scope.Year = moment().format('YYYY');
        $scope.Estado = 'Activo';
        $scope.SedeId = 1;
        $scope.Sede = "";
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Eventos">
        $scope.GetPermisos = () => {
            GetPermisoByMes();
        };
        $scope.ImprimirListado = () => {
            printDiv("#ListadoPermisos");
        };
        $scope.ExportarPermiso = () => {
            $("#ListadoPermisos").tableExport({
                formats: ["xlsx"],
                position: "top", // (top, bottom), position of the caption element relative to table, (default: 'bottom')
            });
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Consultas">
        function GetPermisoByMes() {
            PermisoCTService.getPermisosBySedeIdAndMes($scope.SedeId, $scope.Mes, $scope.Year).then((d) => {
                console.log(d.data);
                $scope.Permisos = d.data;
                for (let i in $scope.Sedes) {
                    if ($scope.SedeId == $scope.Sedes[i].SedeId) {
                        $scope.Sede = $scope.Sedes[i].Nombre;
                        break;
                    }
                }
            });
        }
        function GetSede() {
            SedeService.getAllSede().then(function (c) {
                $scope.Sedes = $filter("orderBy")(c.data, "Nombre");
                $scope.SedeId = $scope.SedeId === "" ? $scope.Sedes[0].SedeId : $scope.SedeId;
            }).then(() => {
                GetPermisoByMes();
            });
        }
        //</editor-fold>
        function printDiv(id) {
            $(id).print({
                globalStyles: true,
                mediaPrint: false,
                stylesheet: null,
                noPrintSelector: ".no-print",
                iframe: true,
                append: null,
                prepend: null,
                manuallyCopyFormValues: true,
                deferred: $.Deferred(),
                timeout: 1500,
                title: null,
                doctype: '<!doctype html>'
            });

            setTimeout(function () {
                $scope.ToPrint = false;
                $scope.$apply();
            }, 1000);
        }
        function __init__() {
            GetSede();
        }
        __init__();
    }]);



