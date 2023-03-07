app.controller('EncabezadoCtrl', ["$scope", "$rootScope","EncabezadoService",
    function ($scope, $rootScope,EncabezadoService) {
        //<editor-fold defaultstate="collapsed" desc="Variables">
        $scope.Encabezado = null;
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Botones">
        $scope.Actualizar = function(){
            if ($scope.DataImg) {
                $scope.Encabezado.Logo = $scope.DataImg.compressed ? $scope.DataImg.compressed.dataURL : $scope.Encabezado.Logo;
            }
            if ($scope.DataImg1) {
                $scope.Encabezado.FirmaElaboro = $scope.DataImg1.compressed ? $scope.DataImg1.compressed.dataURL : $scope.Encabezado.FirmaElaboro;
            }
            if ($scope.DataImg2) {
                $scope.Encabezado.FirmaReviso = $scope.DataImg2.compressed ? $scope.DataImg2.compressed.dataURL : $scope.Encabezado.FirmaReviso;
            }
            if ($scope.DataImg3) {
                $scope.Encabezado.FirmaAprobo = $scope.DataImg3.compressed ? $scope.DataImg3.compressed.dataURL : $scope.Encabezado.FirmaAprobo;
            }
            if (!$scope.Datos.$valid) {
                angular.element("[name='" + $scope.Datos.$name + "']").find('.ng-invalid:visible:first').focus();
            } else {
                $scope.UpdateEncabezado();
            }
        };
        $scope.UpdateEncabezado = function () {
            $scope.Encabezado.ModifiedBy = $rootScope.username.NombreUsuario;
            var obj = {
                Encabezado: JSON.stringify([$scope.Encabezado]),
                ID: $scope.Encabezado.EncabezadoPiePaginaId
            };
            EncabezadoService.PutEncabezado(obj).then(function (d) {
                if (typeof d.data != "string") {
                    swal("Enhorabuena", "Se han actualizado los datos de la empresa con exito", "success");
                }else{
                    swal("Error", d.data, "error");
                }
            }, function (e) {
                swal("Error", e, "error");
            });
        }
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="consultas">
        function GetEncabezado() {
            EncabezadoService.getEncabezado().then(function(e){
                console.log(e.data)
                $scope.Encabezado = e.data;
                $scope.image = e.data.Logo;
            });
        }
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Helpers">
        
        //</editor-fold>
        
        function _init() {
            GetEncabezado();
        }
        _init();
    }]);
