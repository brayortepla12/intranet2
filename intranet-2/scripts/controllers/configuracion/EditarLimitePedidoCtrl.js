app.controller('EditarLimitePedidoCtrl', ["$scope", "$rootScope", "RelacionService", "UsuarioService",
    function ($scope, $rootScope, RelacionService, UsuarioService) {
        //<editor-fold defaultstate="collapsed" desc="Variables">
        $scope.ToPrint = false;
        $scope.cargado = false;
        $scope.Usuarios = [];
        $scope.UsuarioId = "";
        $scope.Estado = "Activo";
        $scope.Item = {};
        $scope.simpleTableOptions = {};
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Botones">
        $scope.BuscarPlantilla = function () {
            if ($scope.UsuarioId !== "--") {
                $scope.cargado = false;
                RelacionService.BuscarPlantilla($scope.UsuarioId, $scope.Estado).then(function (d) {
                    console.log(d.data);
                    $scope.simpleTableOptions = {
                        data: [],
                        aoColumns: [
                            {mData: 'RelacionCostoId'},
                            {mData: 'Articulo'},
                            {mData: 'Servicio'},
                            {mData: 'Solicitante'},
                            {mData: 'Limite'},
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
                    $scope.simpleTableOptions.data = SetFormat(d.data);
                    $scope.cargado = true;
                });
            }
        };
        $scope.VerItem = function (i) {
            $scope.Item = $scope.simpleTableOptions.data[i];
            $('#EditarLimiteModal').modal('show');
            $scope.$apply();
        };
        
        $scope.ActualizarLimite = () => {
            $scope.Item.ModifiedBy = $rootScope.username.NombreCompleto;
            let obj = {
                EditarLimite: JSON.stringify([$scope.Item])
            };
            RelacionService.PutRelacion(obj).then((d)=>{
                if (typeof d.data != "string") {
                    swal("Enhorabuena!", "Se han actualizado los datos con exito", "success");
                    $('#EditarLimiteModal').modal('hide');
                    $scope.BuscarPlantilla();
                }
            });
        };
        $scope.Imprimir = function () {
            $scope.ToPrint = true;
            setTimeout(function () {
                printDiv();
            }, 600);

        };

        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="consultas">
        function GetUsuariosWithPlantilla() {
            UsuarioService.GetUsuarioWithPlantilla().then((d) => {
                if (typeof d.data != "string" && d.data.length > 0) {
                    $scope.Usuarios = d.data;
                    $scope.UsuarioId = d.data[0].UsuarioId;
                    $scope.BuscarPlantilla();
                }
            }).catch(() => {
                console.log("Error vacio");
            });
        }
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Helpers">
        function SetFormat(lst) {
            for (var i in lst) {
                if (lst[i].Estado != 'Inactivo') {
                    lst[i].Opciones = '<a class="btn btn-success btn-xs icon-only white" onclick=\"angular.element(this).scope().VerItem(' + i + ')\" ' +
                            ' ><i class="fa fa-pencil"></i></a>';
                } else {
                    lst[i].Opciones = "";
                }

            }
            return lst;
        }
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
                $scope.ToPrint = false;
                $scope.$apply();
            }, 1000);
        }
        //</editor-fold>
        function _init() {
            GetUsuariosWithPlantilla();
        }
        _init();
    }]);