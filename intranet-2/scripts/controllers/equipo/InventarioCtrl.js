app.controller('InventarioCtrl', ["$scope", "ServicioService", "EquipoService", "$state",
    function ($scope, ServicioService, EquipoService, $state) {
        //<editor-fold defaultstate="collapsed" desc="Variables">
        $scope.Equipoes = null;
        $scope.Servicios = [];
        $scope.ServicioId = "--";
        $scope.ServiciosSeleccionados = [];
        $scope.LabelServicios = "";
        $scope.Year = new Date().getFullYear();
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Botones">
        $scope.BuscarEquipo = function () {
            $scope.Equipoes = null;
            GetEquipoes();
        };
        $scope.Imprimir = function () {
            setTimeout(function () {
                printDiv();
            }, 600);

        };
        $scope.AddServicios = function (s) {
            var cont = 0;
            for (var i in $scope.ServiciosSeleccionados) {
                if ($scope.ServiciosSeleccionados[i].Nombre === s.Nombre) {
                    cont++;
                    $scope.ServiciosSeleccionados.splice(i, 1);
                }
            }
            if (cont == 0 && s) {
                $scope.ServiciosSeleccionados.push(s);
            }
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="consultas">
        function GetEquipoes() {
            if ($scope.ServiciosSeleccionados.length !== 0) {
                var obj = {
                    Servicios: JSON.stringify([$scope.ServiciosSeleccionados]),
                    Year: $scope.Year
                };
                EquipoService.GetAllByServicios(obj).then(function (c) {
                    console.log(c.data)
                    $scope.Equipoes = Crearpaquetes(c.data);
                    LabelServicios($scope.ServiciosSeleccionados);
                });
            }
        }
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Helpers">
        function Crearpaquetes(lst) {
            var paquete = {
                pack: []
            };
            var size = 30;
            var cont = 0;
            for (var i = 0; i < lst.length; i++) {
                for (var j = 0; j < lst[i].length; j += size) {
                    var lst2 = angular.copy(lst);
                    var smallarray = lst2[i].slice(j, j + size);
                    cont += size;
                    // do something with smallarray
                    
                    if (smallarray) {
                        paquete.pack.push(smallarray);
                    }
                }
            }
            console.log(paquete.pack)
            return paquete;
        }
        function LabelServicios(lst) {
            $scope.LabelServicios = "";
            for (var i = 0; i <= lst.length - 1; i++) {

                $scope.LabelServicios += lst[i].Nombre;
                if (i == lst.length - 2) {
                    $scope.LabelServicios += " Y ";
                } else if(lst.length > 1 && i != lst.length - 1){
                    $scope.LabelServicios += ", ";
                }
            }
        }
        function SetFormat(lst) {
            for (var i in lst) {
                lst[i].Opciones = '<a class="btn btn-success btn-xs icon-only white" onclick=\"angular.element(this).scope().ViewItem(' + i + ')\" ' +
                        ' ><i class="fa fa-info-circle"></i></a>';
            }
            return lst;
        }
        function GetServicios() {
            ServicioService.getAllServicio().then(function (s) {
                $scope.Servicios = s.data;
            });
        }
        function printDiv() {
            $("#Equipo").print({
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
            GetServicios();
        }
        _init();
    }]);

