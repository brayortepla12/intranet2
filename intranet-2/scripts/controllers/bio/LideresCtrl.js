app.controller('LideresCtrl', ["$scope", "$rootScope", "LideresService", "PermisoCTService", "EmpresaService",
    function ($scope, $rootScope, LideresService, PermisoCTService, EmpresaService) {
        $scope.BanderaHorario = false;
        $scope.Mes = moment().format('M');
        $scope.Year = moment().format('YYYY');
        $scope.Tipo = "Entrada";
        $scope.TipoTurno = "";
        $scope.Listados = [];
        $scope.ListadosES = [];
        $scope.Nombres = "";
        $scope.Horarios = [];
        $scope.BuscarPermiso = false;
        $scope.Permiso = {};
        $scope.Permisos = [];
        //<editor-fold defaultstate="collapsed" desc="Variables">
        $scope.Lideres = [];
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
        $scope.ImprimirHorario = () => {
            printDiv("#TablaHorario", "/intranet-2/public_html/styles/ExcelHorarioCM.css");
        };
        $scope.ImprimirEstadisticas = () => {
            printDiv("#TablaEstadisticas", null);
        };
        $scope.getLideres = () => {
            $scope.Lideres = [];
            GetLideres();
        };

        $scope.GetVerHorario = (PersonaId, Nombres) => {
            $scope.UltimoDiaMes = getDaysInMonth($scope.Mes, $scope.Year);
            $scope.Nombres = Nombres;
            LideresService.getHorarioColaboradores(PersonaId, $scope.Mes, $scope.Year).then((d) => {
                console.log(d.data);
                $scope.Horarios = d.data;
                $scope.BanderaHorario = true;
            });
        };

        $scope.VerListado = (PersonaId, TipoTurno) => {
            $scope.TipoTurno = TipoTurno == 'Tarde' ? 'Incumplidas' : 'Cumplidas';
            LideresService.getListadoE_S($scope.Year, $scope.Mes, $scope.Tipo, PersonaId, TipoTurno).then((d) => {
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
            LideresService.getListadoES($scope.Year, $scope.Mes, PersonaId).then((d) => {
                console.log(d.data);
                $scope.ListadosES = d.data;
                $('#ListadoESModal').modal('show');
            });
        };

        $scope.VerListadoSalidas = (PersonaId, TipoTurno) => {
            $scope.TipoTurno = TipoTurno;
            LideresService.getListadoE_S($scope.Year, $scope.Mes, $scope.Tipo, PersonaId, TipoTurno).then((d) => {
                console.log(d.data);
                $scope.Listados = d.data;
                $('#EventosSalidasModal').modal('show');
            });
        };

        //<editor-fold defaultstate="collapsed" desc="Helpers">
        function getDaysInMonth(m, y) {
            return moment(new Date(y, m - 1, 1)).endOf('month').format('DD');
        }
        function printDiv(id, estilos) {
            $(id).print({
                globalStyles: true,
                mediaPrint: false,
                stylesheet: estilos,
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
        function GetLideres() {
            LideresService.getLideres($scope.Year, $scope.Mes, $scope.Tipo).then((d) => {
                console.log(d.data);
                $scope.Lideres = d.data;
            });
        }
        //</editor-fold>
        function __init__() {
            GetEmpresa();
            GetLideres();
        }
        __init__();
    }]);


