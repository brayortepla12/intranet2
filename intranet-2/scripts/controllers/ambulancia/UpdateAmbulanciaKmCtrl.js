'use strict';
app.controller('UpdateAmbulanciaKmCtrl', ["$scope", "$rootScope", "KMService", "HojaVidaAmbulanciaService",
    function ($scope, $rootScope, KMService, HojaVidaAmbulanciaService) {
        //<editor-fold defaultstate="collapsed" desc="inicializar variables">
        $scope.Moviles = [];
        $scope.Km = {
            HojaVidaId: '',
            LastKm: '',
            Km: '',
            CreatedBy: $rootScope.username.NombreCompleto
        };
        $scope.EditKm = {
            KMId: '',
            HojaVidaId: '',
            LastKm: '',
            Km: '',
            CreatedBy: $rootScope.username.NombreCompleto
        };
        // inicializamos las variables en _init()
        _init();
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Consultas">
        function GetListadoMoviles() {
            KMService.getAllKM().then(function (c) {
                $scope.Moviles = c.data;
            });
        }
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Botones guardar e imprimir">
        $scope.EditModalKm = (o) => {
            $scope.EditKm.HojaVidaId = o.HojaVidaId;
            $scope.EditKm.KMId = o.KMId;
            $scope.EditKm.Km = o.Km;
            $('#UKmModal').modal('show');
        };
        $scope.EliminarMovil = (HojaVidaId) => {
            swal({
                title: "¿Deseas eliminar esta movil?",
                text: "",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Eliminar",
                cancelButtonText: "Cancelar",
                closeOnConfirm: false,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {
                    var obj = {
                        HojaVidaId: HojaVidaId,
                        Estado: 'Inactivo',
                        ModifiedBy: $rootScope.username.NombreCompleto
                    };
                    var data = {
                        EliminarMovil: JSON.stringify(obj)
                    };
                    HojaVidaAmbulanciaService.putHojaVida(data).then(function (d) {
                        if (typeof d.data === 'string') {
                            swal("Error!", d.data, "error");
                        } else {
                            swal("Enhorabuena!", "Se ha eliminado con exito.", "success");
                            GetListadoMoviles();
                        }
                    });
                }
            });
        };
        $scope.SetEstadoMovil = (HojaVidaId, EstadoMovil) => {
            swal({
                title: `¿Deseas cambiar el estado de esta movil a ${EstadoMovil}?`,
                text: "",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-warning",
                confirmButtonText: "Cambiar Estado",
                cancelButtonText: "Cancelar",
                closeOnConfirm: false,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {
                    var obj = {
                        HojaVidaId: HojaVidaId,
                        EstadoMovil: EstadoMovil,
                        ModifiedBy: $rootScope.username.NombreCompleto
                    };
                    var data = {
                        EstadoMovil: JSON.stringify(obj)
                    };
                    HojaVidaAmbulanciaService.putHojaVida(data).then(function (d) {
                        if (typeof d.data === 'string') {
                            swal("Error!", d.data, "error");
                        } else {
                            swal("Enhorabuena!", "Se ha realizado el cambio con exito.", "success");
                            GetListadoMoviles();
                        }
                    });
                }
            });
        };
        $scope.OpenModalKm = (o) => {
            $scope.Km.HojaVidaId = o.HojaVidaId;
            $scope.Km.LastKm = o.Km;
            $('#KmModal').modal('show');
        };
        $scope.CrearKm = () => {
            var obj = {
                KM: JSON.stringify($scope.Km)
            };
            KMService.PostKM(obj).then(function (d) {
                if (typeof d.data === "string") {
                    swal("Hubo un Error", d.data, "error");
                } else {
                    swal("Enhorabuena!!", "Se han guardado los datos satisfactoriamente", "success");
                    $scope.Km = {
                        HojaVidaId: '',
                        LastKm: '',
                        Km: '',
                        CreatedBy: $rootScope.username.NombreCompleto
                    };
                    $scope.Moviles = [];
                    $('#KmModal').modal('hide');
                    GetListadoMoviles();
                }
            }, function (e) {
                swal("Hubo un Error", e, "error");
            });
        };

        $scope.UpdateKm = () => {
            $scope.EditKm.ModifiedBy = $rootScope.username.NombreCompleto;
            var obj = {
                EditKm: JSON.stringify($scope.EditKm)
            };
            KMService.PutKM(obj).then(function (d) {
                if (typeof d.data === "string") {
                    swal("Hubo un Error", d.data, "error");
                } else {
                    swal("Enhorabuena!!", "Se han actualizado los datos satisfactoriamente", "success");
                    $scope.EditKm = {
                        KMId: '',
                        HojaVidaId: '',
                        LastKm: '',
                        Km: '',
                        CreatedBy: $rootScope.username.NombreCompleto
                    };
                    $scope.Moviles = [];
                    $('#UKmModal').modal('hide');
                    GetListadoMoviles();
                }
            }, function (e) {
                swal("Hubo un Error", e, "error");
            });
        };
        //</editor-fold>
        function _init() {
            GetListadoMoviles();
        }


    }]);







