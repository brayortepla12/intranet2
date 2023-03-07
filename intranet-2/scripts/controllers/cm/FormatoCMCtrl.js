app.controller('FormatoCMCtrl', ["$scope", "$rootScope",
    function ($scope, $rootScope) {
        console.log($rootScope.RondaV);
        function printDiv() {
            $("#TablaPreviewExcel").print({
                globalStyles: true,
                mediaPrint: false,
                stylesheet: "/intranet-2/public_html/styles/ExcelCM.css",
                noPrintSelector: ".no-print",
                iframe: true,
                append: null,
                prepend: null,
                manuallyCopyFormValues: true,
                deferred: $.Deferred(),
                timeout: 1000,
                title: null,
                doctype: '<!doctype html>'
            });

            setTimeout(function () {
                $scope.ToPrint = false;
                $scope.$apply();
            }, 1000);
        }
    }]);




