app.controller('RondaSistemaDetalleCtrl', ["$scope", "$rootScope", "$stateParams", "EmpresaService", "$crypto"
    ,"RondaSistemaServicioService" ,"$state",
    function ($scope, $rootScope, $stateParams, EmpresaService, $crypto, RondaSistemaServicioService, $state) {
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
        $scope.FechaHoy = "";
        $scope.Usuarios = [];
        $scope.DetalleRondaSistemas = [];
        var decrypt = $crypto.decrypt($stateParams.token, 'Franklin Ospino');
        $scope.FechaHoy = decrypt;
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Botones">
        $scope.Imprimir = function () {
            $scope.ToPrint = true;
            setTimeout(function () {
                printDiv();
            }, 600);

        };
        $scope.GuardarRondaSistema_Detalle = function(){
            var obj = {
                RondaSistema_Detalle: JSON.stringify([$scope.DetalleRondaSistemas]),
                CreatedBy: $rootScope.username.NombreCompleto
            };
            RondaSistemaServicioService.PostRondaSistemaServicio(obj).then(function(d){
                console.log(d.data);
            });
        };
        //</editor-fold>    
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
        
        
        
        $scope.ShowServicios = function(i){
            $state.go('ronda.listado_rondas_sistemas', {RondaId:$scope.simpleTableOptions.data[i].RondaId});
        };
        
        $scope.GuardarRondaSistema_Detalle = function(){
            var obj = {
                RondaSistema_Detalle: JSON.stringify([$scope.DetalleRondaSistemas]),
                CreatedBy: $rootScope.username.NombreCompleto
            };
            RondaSistemaServicioService.PostRondaSistemaServicio(obj).then(function(d){
                console.log(d.data);
                GetRondasByUsuario();
            });
        };
        
        function GetRondasByUsuario() {
            RondaSistemaServicioService.getRondaSistemaServicioByUsuarioId_RondaFECHA($rootScope.username.UserId ,$stateParams.RondaId, $scope.FechaHoy).then(function(d){
                console.log(d.data);
                $scope.DetalleRondaSistemas = SetFormat(d.data);
            });
        }
        
        GetRondasByUsuario();
        
        //<editor-fold defaultstate="collapsed" desc="Helpers">
        function SetFormat(lst) {
            for (var i in lst) {
                lst[i].Fecha = $scope.FechaHoy;
               
            }
            return lst;
        }
        //</editor-fold>
    }]);




