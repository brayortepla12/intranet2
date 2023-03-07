app.controller('RondasCtrl', ["$scope", "$rootScope", "RondaService", "$state",
    function ($scope, $rootScope, RondaService,$state) {
        //<editor-fold defaultstate="collapsed" desc="Variables">
        $scope.cargado = false;
        $rootScope.Rondas = [];
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Botones">
        $scope.VerPlanilla = function(i){
            $rootScope.Planilla = $scope.simpleTableOptions.data[i];
            var url = $state.href("polivalente.planilla", {planilla_id: $rootScope.Planilla.RondaId});
            window.open(url,'_blank'); /// consultar por ID
        };
        $scope.AsignarTareas = function(i){
            $rootScope.Planilla = $scope.simpleTableOptions.data[i];
            var url = $state.href("polivalente.tarea", {planilla_id: $rootScope.Planilla.RondaId});
            window.open(url,'_blank'); /// consultar por ID
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="consultas">
         
        function GetRondas() {
            RondaService.GetAll($rootScope.username.UserId).then(function (c) {
                $scope.simpleTableOptions = {
                    data: [],
                    aoColumns: [
                        {mData: 'RondaId'},
                        {mData: 'Sede'},
                        {mData: 'Servicio'},
                        {mData: 'NombreJefeArea'},
                        {mData: 'Observaciones'},
                        {mData: 'CreatedBy'},
                        {mData: 'Fecha'},
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
                $scope.simpleTableOptions.data = SetFormat(c.data);
                $scope.cargado = true;
                
            });
        }
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Helpers">
       function SetFormat(lst) {
            for (var i in lst) {
                lst[i].Opciones = '<a class="btn  btn-success btn-xs icon-only white" onclick=\"angular.element(this).scope().VerPlanilla(' + i + ')\" target="_blank"><i class="fa fa-file-text-o"></i></a>';
            }
            return lst;
        }
        //</editor-fold>
        function _init() {
            $scope.Usuario = $rootScope.username;
            GetRondas();
        }
        _init();
    }]);




