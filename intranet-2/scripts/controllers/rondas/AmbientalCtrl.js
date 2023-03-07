Date.prototype.addDays = function (days) {
    var dat = new Date(this.valueOf());
    dat.setDate(dat.getDate() + days);
    return dat;
};

app.controller('AmbientalCtrl', ["$scope", "$rootScope", "$filter", "RondaAmbientalService", "ServicioService", "SedeService", "EmpresaService", "EncabezadoService",
    function ($scope, $rootScope, $filter, RondaAmbientalService, ServicioService, SedeService,EmpresaService, EncabezadoService) {
        //<editor-fold defaultstate="collapsed" desc="Variables">
        $scope.Servicios = [];
        $scope.Servicios2 = [];
        $scope.Sedes = [];
        $scope.SedeId = "--";
        $scope.ServicioId = "--";
        $scope.Formatos = [];
        $scope.events = [];
        $scope.UpdateFormatos = [];
        $scope.ImprimirNow = false;
        $scope.Empresa = {};
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Botones">
        $scope.ChangeSede = function () {
            $scope.Servicios = $filter("filter")($scope.Servicios2, {SedeId: $scope.SedeId});
        };
        $scope.eventClicked = function (e) {
            $scope.UpdateFormatos = [];
            for (var i in $scope.events) {
                if ($scope.events[i].startsAt.getDate() === e.startsAt.getDate() && $scope.events[i].startsAt.getMonth() === e.startsAt.getMonth() && $scope.events[i].startsAt.getFullYear() === e.startsAt.getFullYear()) {
                    console.log($scope.events[i]);
                    $scope.UpdateFormatos.push($scope.events[i]);
                }
            }
            $("#UpdateRondaModal").modal("show");
        };
        $scope.ChangeServicio = function () {
            RondaAmbientalService.getRondasAmbientalesByServicioId($scope.ServicioId).then(function (d) {
                console.log(d.data);
                for (var i in d.data) {
                    d.data[i].start = new Date(d.data[i].startsAt + " 00:00:00");
                    d.data[i].end = new Date(d.data[i].endsAt + " 23:59:59");
                    d.data[i].startsAt = new Date(d.data[i].startsAt + " 00:00:00");
                    d.data[i].endsAt = new Date(d.data[i].endsAt + " 23:59:59");
                    d.data[i].title = d.data[i].Nombre;
                    var color = getRandomColor();
                    d.data[i].color = {// can also be calendarConfig.colorTypes.warning for shortcuts to the deprecated event types
                        primary: color, // the primary event color (should be darker than secondary)
                        secondary: color // the secondary event color (should be lighter than primary)
                    };
                    d.data[i].allDay = true;
                }
                $scope.events = d.data;
            });
        };
        $scope.Guardar = function () {
            var obj = {
                ListadoItems: JSON.stringify([$scope.Formatos])
            };

            RondaAmbientalService.PostRondaAmbiental(obj).then(function (d) {
                console.log(d.data);
                if (typeof d.data != "string") {
                    swal("Enhorabuena!", 'Se han guardado los datos satisfactoriamente', "success");
                    $("#RondaAmbientalModal").modal("hide");
                    $scope.Formatos = [];
                    $scope.ChangeServicio();
                } else {
                    swal("Error", d.data, "error");
                }
            });
        };

        $scope.Actualizar = function () {
            var obj = {
                ListadoItems: JSON.stringify($scope.UpdateFormatos)
            };
            RondaAmbientalService.PutRondaAmbiental(obj).then(function (d) {
                console.log(d.data);
                if (typeof d.data != "string") {
                    swal("Enhorabuena!", 'Se han modificado los datos satisfactoriamente', "success");
                    $("#UpdateRondaModal").modal("hide");
                    $scope.UpdateFormatos = [];
                    $scope.ChangeServicio();
                } else {
                    swal("Error", d.data, "error");
                }
            });
        };

        $scope.eventCreate = function (e) {
            RondaAmbientalService.getFormularioByServicioId($scope.ServicioId).then(function (d) {
                console.log(d.data);
                $scope.Formatos = d.data;
                for (var i in $scope.Formatos) {
                    $scope.Formatos[i].startsAt = e;
                    $scope.Formatos[i].endsAt = e.addDays(2);
                }
                $("#RondaAmbientalModal").modal("show");
            });
        };
        $scope.Imprimir = function(){
            $scope.ImprimirNow = true;
            setTimeout(function () {
                printDiv();
            }, 1000);
            
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Consultas">
        function GetServicio() {
            ServicioService.getServicioByUserId($rootScope.username.UserId).then(function (c) {
                $scope.Servicios2 = $filter("orderBy")(angular.copy(c.data), "Nombre");
                $scope.ChangeSede();
            });
        }
        function GetSede() {
            SedeService.getAllSedeByUserId($rootScope.username.UserId).then(function (c) {
                $scope.Sedes = $filter("orderBy")(c.data, "Nombre");
                $scope.SedeId = $scope.Sedes[0].SedeId;
                GetServicio();
            });
        }
        function GetEmpresa() {
            EmpresaService.getEmpresa().then(function (e) {
                $scope.Empresa = e.data;
            });
        }
        function GetEncabezado() {
            EncabezadoService.getEncabezado().then(function (e) {
                $scope.Encabezado = e.data;
            });
        }
        //</editor-fold>

        function printDiv() {
            $("#TablasRonda").print({
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
                $scope.ImprimirNow = false;
                $scope.$apply();
            }, 1000);
        }
        function getRandomColor() {
            var letters = 'BCDEF'.split('');
            var color = '#';
            for (var i = 0; i < 6; i++) {
                color += letters[Math.floor(Math.random() * letters.length)];
            }
            return color;
        }


        function _init() {
            GetSede();
            GetEmpresa();
            GetEncabezado();
        }
        _init();
    }]);





