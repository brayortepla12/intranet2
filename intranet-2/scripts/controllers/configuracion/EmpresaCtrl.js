app.controller('EmpresaCtrl', ["$scope", "$rootScope","EmpresaService",
    function ($scope, $rootScope,EmpresaService) {
        //<editor-fold defaultstate="collapsed" desc="Variables">
        $scope.Empresa = null;
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Botones">
        $scope.Actualizar = function(){
            if ($scope.DataImg) {
                $scope.Empresa.Logo = $scope.DataImg.compressed ? $scope.DataImg.compressed.dataURL : $scope.Empresa.Logo;
            }
            if (!$scope.Datos.$valid) {
                angular.element("[name='" + $scope.Datos.$name + "']").find('.ng-invalid:visible:first').focus();
            } else {
                $scope.UpdateEmpresa();
            }
        };
        $scope.UpdateEmpresa = function () {
            $scope.Empresa.ModifiedBy = $rootScope.username.NombreUsuario;
            var obj = {
                Empresa: JSON.stringify([$scope.Empresa]),
                ID: $scope.Empresa.EmpresaId
            };
            EmpresaService.PutEmpresa(obj).then(function (d) {
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
        function GetEmpresa() {
            EmpresaService.getEmpresa().then(function(e){
                console.log(e.data)
                $scope.Empresa = e.data;
                $scope.image = e.data.Logo;
            });
        }
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Helpers">
        
        //</editor-fold>
        
        function _init() {
            GetEmpresa();
        }
        _init();
    }]);
