app.controller('RondasSistemasCtrl', ["$scope", "$rootScope", "RondaSistemaServicioService", "$state",
    function ($scope, $rootScope, RondaSistemaServicioService,$state) {
        $scope.cargado = false;
        $scope.simpleTableOptions = {};
        $scope.RondasSistemas = [];
        
        
        $scope.ShowServicios = function(i){
//            console.log($scope.simpleTableOptions.data[i]);
//            $scope.Ronda = $scope.simpleTableOptions.data[i].Nombre;
//            RondaSistemaServicioService.getRondaSistemaServicioByUsuarioId_Ronda($rootScope.username.UserId, $scope.simpleTableOptions.data[i].RondaId ).then(function(d){
//                console.log(d.data);
//                $('#RondaSistemaModal').modal('show');
//                $scope.RondasSistemas = d.data;
//            });

            $state.go('ronda.listado_rondas_sistemas', {RondaId:$scope.simpleTableOptions.data[i].RondaId});
        };
        $scope.GuardarRondaSistema_Detalle = function(){
            var obj = {
                RondaSistema_Detalle: JSON.stringify([$scope.RondasSistemas]),
                CreatedBy: $rootScope.username.NombreCompleto
            };
            RondaSistemaServicioService.PostRondaSistemaServicio(obj).then(function(d){
                console.log(d.data);
            });
        };
        
        function GetRondasByUsuario() {
            RondaSistemaServicioService.getRondaSistemaServicioByUsuarioId($rootScope.username.UserId).then(function(d){
                console.log(d.data);
                $scope.simpleTableOptions = {
                    data: [],
                    aoColumns: [
                        {mData: 'RondaId'},
                        {mData: 'Nombre'},
                        {mData: 'Tipo'},
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
                $scope.simpleTableOptions.data = SetFormat(d.data);
                $scope.cargado = true;
            });
        }
        
        GetRondasByUsuario();
        
        //<editor-fold defaultstate="collapsed" desc="Helpers">
        function SetFormat(lst) {
            for (var i in lst) {
                lst[i].Opciones = '<a class="btn  btn-info btn-xs icon-only white" onclick=\"angular.element(this).scope().ShowServicios(' + i + ')\" target="_blank"><i class="fa fa-info"></i></a>';
               
            }
            return lst;
        }
        //</editor-fold>
    }]);





