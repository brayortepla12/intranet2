app.controller('CargoCtrl', ["$scope", "$rootScope", "$filter", "CargoService", "SedeService", "ServicioService", "ControlService",
    function ($scope, $rootScope, $filter, CargoService, SedeService, ServicioService, ControlService) {
        //<editor-fold defaultstate="collapsed" desc="Variables">
        $scope.simpleTableOptions = {};
        $scope.cargado = false;
        $scope.ActualizarBool = false;
        $scope.Cargo = {
            Cargo: "",
            CreatedBy: $rootScope.username.NombreCompleto
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Eventos">
        $scope.OpenCrearModal = () => {
            $scope.ActualizarBool = false;
            $scope.Cargo = {
                Cargo: "",
                CreatedBy: $rootScope.username.NombreCompleto
            };
            $('#CargoModal').modal('show');
        };
        
        $scope.ModalEditarCargo = (CargoId) => {
            CargoService.getCargoById(CargoId).then((d) => {
                $scope.Cargo = angular.copy(d.data[0]);
                $scope.ActualizarBool = true;
                $('#CargoModal').modal('show');
            });
        };

        $scope.CrearCargo = () => {
            
            if (!$scope.Datos.$valid) {
                angular.element("[name='" + $scope.Datos.$name + "']").find('.ng-invalid:visible:first').focus();
            } else {
                let obj = {Cargo: JSON.stringify($scope.Cargo)};
                CargoService.postCargo(obj).then((d) => {
                    if (typeof d.data !== "string") {
                        swal("Enhorabuena", "Se ha guardado los datos con exito", "success");
                        $('#CargoModal').modal('hide');
                        $scope.Cargo = {
                            Cargo: "",
                            CreatedBy: $rootScope.username.NombreCompleto
                        };
                        GetCargos();
                    } else {
                        swal("Error", d.data, "error");
                    }
                });
            }
        };
        $scope.ActualizarCargo = () => {
            if (!$scope.Datos.$valid) {
                angular.element("[name='" + $scope.Datos.$name + "']").find('.ng-invalid:visible:first').focus();
            } else {
                $scope.Cargo.ModifiedBy = $rootScope.username.NombreCompleto;
                let obj = {Cargo: JSON.stringify($scope.Cargo)};
                CargoService.putCargo(obj).then((d) => {
                    if (typeof d.data !== "string") {
                        swal("Enhorabuena", "Se han actualizado los datos con exito", "success");
                        $('#CargoModal').modal('hide');
                        $scope.Cargo = {
                            Cargo: "",
                            CreatedBy: $rootScope.username.NombreCompleto
                        };
                        GetCargos();
                    } else {
                        swal("Error", d.data, "error");
                    }
                });
            }

        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Consultas">
        function GetCargos() {
            $scope.cargado = false;
            CargoService.getCargos().then((d) => {
                $scope.simpleTableOptions = {
                    data: [],
                    aoColumns: [
                        {mData: 'CargoId'},
                        {mData: 'Cargo'},
                        {mData: 'Opciones'},
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
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Helpers">
        function SetFormat(lst) {
            for (var i in lst) {
                lst[i].Opciones = '';
                lst[i].Opciones += '<a class="btn  btn-info btn-xs icon-only white" onclick=\"angular.element(this).scope().ModalEditarCargo(' + lst[i].CargoId + ')\"><i class="fa fa-pencil"></i></a>';

            }
            return lst;
        }
        //</editor-fold>
        function __init__() {
            GetCargos();
        }
        __init__();
    }]);


