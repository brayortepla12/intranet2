app.controller('CalibracionCtrl', ["$scope", "ServicioService", "CalibracionService", "$rootScope","$filter","EmpresaService","EncabezadoService",
    function ($scope, ServicioService, CalibracionService, $rootScope,$filter,EmpresaService,EncabezadoService) {
        //<editor-fold defaultstate="collapsed" desc="Variables">
        var fecha_hoy = new Date();
        $scope.FechaActual = fecha_hoy.setMonth(fecha_hoy.getMonth() + 1);
        var fecha = null;
        $scope.Listados = null;
        $scope.Servicios = [];
        $scope.ServicioId = "--";
        $scope.ServiciosSeleccionados = [];
        $scope.LabelServicios = "";
        $scope.Year = new Date().getFullYear();
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Botones">
        $scope.BuscarCalibracion = function () {
            $scope.Listados = null;
            GetListados();
        };
        $scope.Imprimir = function () {
            setTimeout(function () {
                printDiv();
            }, 600);

        };
        $scope.AddServicios = function () {
//            var cont = 0;
//            for (var i in $scope.ServiciosSeleccionados) {
//                if ($scope.ServiciosSeleccionados[i].Nombre === s.Nombre) {
//                    cont++;
//                    $scope.ServiciosSeleccionados.splice(i, 1);
//                }
//            }
//            if (cont == 0 && s) {
//                $scope.ServiciosSeleccionados.push(s);
//            }
            $scope.ServiciosSeleccionados = [$scope.Servicios[$scope.ServicioId]];
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="consultas">
        function GetListados() {
            if ($scope.ServiciosSeleccionados.length !== 0) {
                var obj = {
                    Servicios: JSON.stringify([$scope.ServiciosSeleccionados]),
                    Year: $scope.Year
                };
                CalibracionService.GetAllByServicios(obj).then(function (c) {
                    console.log(c.data);
                    $scope.Listados = Crearpaquetes(SetCronograma(c.data));
                    LabelServicios($scope.ServiciosSeleccionados);
                });
            }
        }
        function GetServicios() {
            ServicioService.getServicioByUserId($rootScope.username.UserId).then(function (s) {
//                console.log(s.data)
                $scope.Servicios = $filter("orderBy")(s.data,"Nombre");
            });
        }
        function GetEmpresa() {
            EmpresaService.getEmpresa().then(function (e) {
                $scope.Empresa =  e.data;
            });
        }
        function GetEncabezado() {
            EncabezadoService.getEncabezado().then(function (e) {
                $scope.Encabezado = e.data;
            });
        }
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Helpers">
         function SetCronograma(lst) {
            for (var k in lst) {
                var fecha = null;
                for (var i in lst[k]) {
                    if (lst[k][i].FechaCalibracion2) {
                        var url = "";
                        fecha = new Date(lst[k][i].FechaCalibracion1);
                        url = new Date(lst[k][i].FechaCalibracion2).getFullYear() === $scope.Year ? "/Polivalente/#/mantenimiento/ver_reporte/" + lst[k][i].ReporteId : null;
                        lst[k][i].Mes = new Date(lst[k][i].FechaCalibracion2).getFullYear() === $scope.Year ? fecha.getMonth() : -1;
                        lst[k][i].Cronograma = Cronograma(lst[k][i].Inicio - 1, lst[k][i].Frecuencia, url, fecha, new Date(lst[k][i].FechaCalibracion2));
                    } else {
                        if (lst[k][i].FechaCalibracion1) {
                            fecha = new Date(lst[k][i].FechaCalibracion1);
                            lst[k][i].Mes = new Date(lst[k][i].FechaCalibracion1).getFullYear() === $scope.Year ? fecha.getMonth() : -1;
                            lst[k][i].Cronograma = Cronograma(lst[k][i].Inicio - 1, lst[k][i].Frecuencia, null, fecha, null, lst[k][i].Inicio);
                        } else {
                            lst[k].splice(i, 1);
                        }
                    }
                }
            }
            return lst;
        }
        function Cronograma(n, f, url, fecha, fecha2, inicio) {
            var Cronograma = [];
//            fecha.setFullYear($scope.Year);
//            console.log(($scope.Year - fecha_hoy.getFullYear() + 1) * 12);
            var iteracion_meses = ($scope.Year - fecha_hoy.getFullYear() + 1) * 12;
            var contador = 0;
            for (var i = 0; i < iteracion_meses; i++) {
                if (n === i) {
                    var obj = {
                        Estado: fecha < $scope.FechaActual ? true : false,
                        data: "",
                        n: n
                    };
//                    if (inicio) {
//                        obj.Estado = fecha < $scope.FechaActual && (fecha_hoy.getMonth() + 1) > inicio ? true : false;
//                    }
                    if (!url) {
                        obj.data = 'no tiene';
                    }
                    if (parseInt(fecha2 ? fecha2.getFullYear() === $scope.Year ? fecha2.getMonth() : 0 : null) >= parseInt(n)) {
                        obj.data = url;
                    } else {
                        url = null;
                        obj.data = 'no tiene';
                    }
                    if (f === "SEMESTRAL") {
                        n = n + 6;
                        fecha.setMonth(fecha.getMonth() + 6);
                    } else if (f === "ANUAL") {
                        n = n + 12;
                        fecha.setMonth(fecha.getMonth() + 12);
                    } else if (f === "TRIMESTRAL") {
                        n = n + 3;
                        fecha.setMonth(fecha.getMonth() + 3);
                    } else if (f === "MENSUAL") {
                        n = n + 1;
                        fecha.setMonth(fecha.getMonth() + 1);
                    } else if (f === "DIARIO") {
                        n = n + 1;
                        fecha.setMonth(fecha.getMonth() + 1);
                    } else if (f === "SEMANAL") {
                        n = n + 1;
                        fecha.setMonth(fecha.getMonth() + 1);
                    } else if (f === "BIMESTRAL") {
                        n = n + 2;
                        fecha.setMonth(fecha.getMonth() + 2);
                    } else if (f === "CUATRIMESTRAL") {
                        n = n + 4;
                        fecha.setMonth(fecha.getMonth() + 4);
                    } else if (f === "NO APLICA") {
                        n = n + 12;
                        obj = null;
                    }
                    if (iteracion_meses - i <= 12) {
                        Cronograma[contador] = obj;
                        contador++;
                    }
                } else {
                    if (iteracion_meses - i <= 12) {
                        Cronograma[contador] = null;
                        contador++;
                    }
                }
            }
            return Cronograma;
        }
        function Crearpaquetes(lst) {
            var paquete = {
                pack: []
            };
            var size = 15;
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
        function _init() {
            GetServicios();
            GetEmpresa();
            GetEncabezado();
        }
        _init();
    }]);

