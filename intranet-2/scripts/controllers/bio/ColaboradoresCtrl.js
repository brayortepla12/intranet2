app.controller('ColaboradoresCtrl', ["$scope", "$rootScope", "ColaboradoresService", "PermisoCTService", "EmpresaService",
    function ($scope, $rootScope, ColaboradoresService, PermisoCTService, EmpresaService) {
        $scope.Mes = moment().format('M');
        $scope.Year = moment().format('YYYY');
        $scope.Tipo = "Entrada";
        $scope.TipoTurno = "";
        $scope.Listados = [];
        $scope.ListadosES = [];
        $scope.Nombres = "";
        $scope.BuscarPermiso = false;
        $scope.Permiso = {};
        $scope.Permisos = [];
        //<editor-fold defaultstate="collapsed" desc="Variables">
        $scope.Colaboradores = [];
        //</editor-fold>

        $scope.VerPermiso = (PermisoId) => {
            $scope.Permisos = [];
            PermisoCTService.getPermisoByPermisoId(PermisoId).then((d) => {
                if (typeof d.data != 'string' && d.data.length > 0) {
                    $scope.BuscarPermiso = true;
                    $scope.Permiso = d.data[0];
                }
                console.log(d.data);
            });
        };

        $scope.ImprimirEstadisticas = () => {
            printDiv("#TablaEstadisticas");
        };
        $scope.getColaboradores = () => {
            $scope.Colaboradores = [];
            GetColaboradores();
        };

        $scope.VerListado = (PersonaId, TipoTurno) => {
            $scope.TipoTurno = TipoTurno == 'Tarde' ? 'Incumplidas' : 'Cumplidas';
            ColaboradoresService.getListadoE_S($scope.Year, $scope.Mes, $scope.Tipo, PersonaId, TipoTurno).then((d) => {
                console.log(d.data);
                $scope.Listados = d.data;
                $('#EventosModal').modal('show');
            });
        };

        $scope.GetEstadistica = (PersonaId, Nombres) => {
            $scope.BuscarPermiso = false;
            $scope.Permiso = {};
            $scope.Permisos = [];
            $scope.Nombres = Nombres;
            ColaboradoresService.getListadoES($scope.Year, $scope.Mes, PersonaId).then((d) => {
                console.log(d.data);
                $scope.ListadosES = d.data;
                $('#ListadoESModal').modal('show');
            });
        };

        $scope.VerListadoSalidas = (PersonaId, TipoTurno) => {
            $scope.TipoTurno = TipoTurno;
            ColaboradoresService.getListadoE_S($scope.Year, $scope.Mes, $scope.Tipo, PersonaId, TipoTurno).then((d) => {
                console.log(d.data);
                $scope.Listados = d.data;
                $('#EventosSalidasModal').modal('show');
            });
        };

        //<editor-fold defaultstate="collapsed" desc="Helpers">
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
        //</editor-fold>

        //<editor-fold defaultstate="collapsed" desc="Consultas">
        function GetEmpresa() {
            EmpresaService.getEmpresa().then(function (e) {
                $scope.Empresa = e.data;
            });
        }
        function GetColaboradores() {
            ColaboradoresService.getColaboradores($scope.Year, $scope.Mes, $scope.Tipo).then((d) => {
                console.log(d.data);
                $scope.Colaboradores = d.data;
            });
        }
        //</editor-fold>
        function __init__() {
            GetEmpresa();
            GetColaboradores();
        }
        __init__();
    }]);


