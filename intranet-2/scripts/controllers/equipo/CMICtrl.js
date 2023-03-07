app.controller('CMICtrl', ["$scope", "ServicioService", "CronoInfraService", "$rootScope", "$filter", "EmpresaService", "EncabezadoService",
    function ($scope, ServicioService, CronoInfraService, $rootScope, $filter, EmpresaService, EncabezadoService) {
        //<editor-fold defaultstate="collapsed" desc="Variables">
        var fecha_hoy = new Date();
        $scope.FechaActual = fecha_hoy.setMonth(fecha_hoy.getMonth()); // esta linea me permite configurar que tan rapido vence el mantenimiento
        $scope.Sede = "";
        $scope.CMI = [];
        $scope.Servicios = [];
        $scope.ServicioId = "--";
        $scope.ServiciosSeleccionados = [];
        $scope.LabelServicios = "";
        $scope.Year = new Date().getFullYear();
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Botones">
        $scope.Goto = function (d) {
            window.open('http://192.168.8.125:8080' + d, '_blank');
        };
        $scope.BuscarMantenimientoPreventivo = function () {
            $scope.Listados = null;
            GetListados();
        };
        $scope.Imprimir = function () {
            setTimeout(function () {
                printDiv();
            }, 600);
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="consultas">
        function GetListados() {
            CronoInfraService.GetAllByServicio($scope.Year, $scope.ServicioId).then(function (c) {
                console.log(c.data)
                if (typeof c.data === "string") {
                    swal("Error", c.data, "error");
                }
                $scope.CMI = c.data;
//                LabelServicios($scope.ServiciosSeleccionados);
            });
        }
        function GetServicios() {
            ServicioService.getServicioByUserId($rootScope.username.UserId).then(function (s) {
                if (typeof s.data === "string") {
                    swal("Error", s.data, "error");
                }
                $scope.Servicios = $filter("orderBy")(s.data, "Nombre");
            });
        }
        function GetEmpresa() {
            EmpresaService.getEmpresa().then(function (e) {
                if (typeof e.data === "string") {
                    swal("Error", e.data, "error");
                }
                $scope.Empresa = e.data;
            });
        }
        function GetEncabezado() {
            EncabezadoService.getEncabezado().then(function (e) {
                if (typeof e.data === "string") {
                    swal("Error", e.data, "error");
                }
                $scope.Encabezado = e.data;
            });
        }
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Helpers">
        function SetCronograma(lst) {
            for (var k in lst) {
                var fecha = null;
                for (var i in lst[k]) {
                    if (lst[k][i].FechaMantenimientoPreventivo2) {
                        var url = "";
                        fecha = new Date(lst[k][i].FechaMantenimientoPreventivo1);
                        url = new Date(lst[k][i].FechaMantenimientoPreventivo2).getFullYear() === $scope.Year ? "/Polivalente/#/mantenimiento/ver_reporte/" + lst[k][i].ReporteId : null;
                        lst[k][i].Mes = new Date(lst[k][i].FechaMantenimientoPreventivo2).getFullYear() === $scope.Year ? fecha.getMonth() : -1;
                        lst[k][i].Cronograma = Cronograma(lst[k][i].Inicio - 1, lst[k][i].Frecuencia, url, fecha, new Date(lst[k][i].FechaMantenimientoPreventivo2));
                    } else {
                        if (lst[k][i].FechaMantenimientoPreventivo1) {
                            fecha = new Date(lst[k][i].FechaMantenimientoPreventivo1);
                            lst[k][i].Mes = new Date(lst[k][i].FechaMantenimientoPreventivo1).getFullYear() === $scope.Year ? fecha.getMonth() : -1;
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
                        Estado: fecha <= $scope.FechaActual ? true : false,
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
                    } else if(fecha ? fecha.getFullYear() === $scope.Year ? fecha.getMonth() : 0 : null < parseInt(n) && fecha === fecha2){
                        obj.Estado = true;
                        url = null;
                        obj.data = 'no tiene';
                    }else{
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
            var size = 10;
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
                } else if (lst.length > 1 && i != lst.length - 1) {
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
            $("#MantenimientoPreventivo").print({
                globalStyles: true,
                mediaPrint: false,
                stylesheet: null,
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
        //</editor-fold>
        function _init() {
            GetServicios();
            GetEmpresa();
            GetEncabezado();
        }
        _init();
    }]);

