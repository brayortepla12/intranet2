app.controller('AlertaMPCtrl', ["$scope", "$rootScope",
    function ($scope, $rootScope) {
        //<editor-fold defaultstate="collapsed" desc="Variables">
        $rootScope.AlertasMantenimientoPreventivos = $rootScope.AlertasMantenimientoPreventivos || [];
        $rootScope.Calibraciones = $rootScope.Calibraciones || [];
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Botones">
        
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="consultas">
        
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Helpers">
        function printDiv() {
            $("#calibracion").print({
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
    }]);




