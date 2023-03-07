app.controller('SeguimientoCtrl', ["$scope", "$rootScope", "$stateParams", "RondaService", "EmpresaService", "$crypto", "UsuarioService", "SesionService",
    function ($scope, $rootScope, $stateParams, RondaService, EmpresaService, $crypto, UsuarioService, SesionService) {
        //<editor-fold defaultstate="collapsed" desc="Prototipos fechas">
        Date.prototype.monthNames = [
            "ENERO", "FEBRERO", "MARZO",
            "ABRIL", "MAYO", "JUNIO",
            "JULIO", "AGOSTO", "SEPTIEMBRE",
            "OCTUBRE", "NOVIEMBRE", "DICIEMBRE"
        ];

        Date.prototype.getMonthName = function () {
            return this.monthNames[this.getMonth()];
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Variables">
        $scope.Rondas = [];
        $scope.Rondas2 = [];
        $scope.Empresa = null;
        $scope.FechaHoy = "";
        $scope.Usuarios = [];
        $scope.options = [];
        $scope.labels = {
            "itemsSelected": "Seleccionados",
            "selectAll": "Seleccionar Todo",
            "unselectAll": "Deseleccionar Todo",
            "search": "Buscar",
            "select": "Seleccionar"
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Botones">
        $scope.Imprimir = function () {
            $scope.ToPrint = true;
            setTimeout(function () {
                printDiv();
            }, 600);

        };
        $scope.Actualizar = function () {
            var obj = {
                ActividadUsuario: JSON.stringify([$scope.Rondas]),
                ModifiedBy: $rootScope.username.NombreCompleto
            };
            RondaService.putActividadUsuario(obj).then(function (d) {
                console.log(d.data)
                if (typeof d.data != "string") {
                    swal("Enhorabuena!", 'Se han actualizado los datos satisfactoriamente', "success");
                    GetRondaById();
                } else {
                    swal("Error", d.data, "error");
                }
            });
        };
        //</editor-fold>
        function GetUsuario() {
            UsuarioService.GetALLusuarios(SesionService.get("UserData_Polivalente").key, SesionService.get("UserData_Polivalente").Email).then(function (d) {
                $scope.Usuarios = d.data;
                GetEmpresa();
                GetRondaById();
                for (var i in d.data) {
                    $scope.options.push(d.data[i].NombreCompleto)
                }
            });
        }
        function GetRondaById() {
            $scope.Rondas = [];
            var decrypt = $crypto.decrypt($stateParams.token, 'Franklin Ospino');
            $scope.FechaHoy = toDate(decrypt);
            RondaService.GetAllByFecha(toConsultar(decrypt), $rootScope.username.UserId).then(function (d) {
                
                $scope.Rondas = d.data;
                console.log($scope.Rondas);
            });
        }
        function GetEmpresa() {
            EmpresaService.getEmpresa().then(function (e) {
                $scope.Empresa = e.data;
            });
        }
        function toDate(dateStr) {
            var parts = dateStr.split("-");
            return new Date(parts[0], parts[1] - 1, parts[2]);
        }
        
        function toConsultar(dateStr) {
            var parts = dateStr.split("-");
            return parts[0] + "-" + (parts[1] < 10 ? "0" + parts[1] : parts[1]) + "-" + (parts[2] < 10 ? "0" + parts[2] : parts[2]);
        }


        function printDiv() {
            $("#Planilla").print({
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

        function _init() {
            GetUsuario();
            
        }
        _init();
    }]);




