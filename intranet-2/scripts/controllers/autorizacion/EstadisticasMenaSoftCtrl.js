app.controller('EstadisticasMenaSoftCtrl', ["$scope", "$rootScope", "$state", "AutorizacionService",
    function ($scope, $rootScope, $state, AutorizacionService) {
        //<editor-fold defaultstate="collapsed" desc="Variables">
        let vm = $scope;
        let Meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        const DATE = moment();
        vm.Dia = DATE.format('D');
        vm.Mes = Meses[DATE.format('M') - 1];
        vm.Year = DATE.format('YYYY');
        vm.Dia = 'TODOS';//DATE.format('DD');
        vm.UltimoDiaMes = Number(DATE.endOf("month").format("DD"));
        vm.AutorizacionData = {};
        vm.cargarEstadisticas = false;
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Botones">
        vm.ConsultarAutorizaciones = function () {
            GetAutorizaciones();
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Consulta">
        function GetAutorizaciones() {
            vm.cargarEstadisticas = false;
            $('#dia-select option:selected').removeAttr('selected');
            let f = moment(`${vm.Year}-${vm.Mes}-01`);
            vm.UltimoDiaMes = Number(f.endOf("month").format("DD"));
            AutorizacionService.getAutorizaciones(vm.Dia, vm.Mes, vm.Year).then(function (d) {
                vm.AutorizacionData = {
                    data: [],
                    aoColumns: [
                        {mData: 'idautorizacion'},
                        {mData: 'fecregistro'},
                        {mData: 'nombre'},
                        {mData: 'entidad'},
                        {mData: 'paciente'},
                        {mData: 'cedula'},
                        {mData: 'servicio'},
                        {mData: 'habitacion'},
                        {mData: 'diagnostico'}
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
                vm.AutorizacionData.data = d.data;
                vm.cargarEstadisticas = true;
            });
        }

        function _init() {
            GetAutorizaciones();
        }
        //</editor-fold>
        _init();
    }]);