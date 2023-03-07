app.controller('MantenimientoPreventivoSistemasCtrl', ["$scope", "$rootScope", "$filter", "SedeService", "ServicioService", "MantenimientoPreventivoSistemaService", "EmpresaService", "EncabezadoService",
    function ($scope, $rootScope, $filter, SedeService, ServicioService, MantenimientoPreventivoSistemaService, EmpresaService, EncabezadoService) {
        //<editor-fold defaultstate="collapsed" desc="Variables">
        var fecha_hoy = new Date();
        $scope.MesActual = moment().format('M');
        $scope.FechaActual = fecha_hoy.setMonth(fecha_hoy.getMonth()); // esta linea me permite configurar que tan rapido vence el mantenimiento
        $scope.Listados = null;
        $scope.Sedes = [];
        $scope.SedeId = "--";
        $scope.SedesSeleccionados = [];
        $scope.LabelSedes = "";
        $scope.Year = new Date().getFullYear();
        $scope.FiltrarMes = "Todos";
        $scope.ServicioId = "Todos";

        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Botones">
        $scope.Goto = function (d) {
            if (d) {
                window.open(`http://${window.location.host}/Polivalente/#/sistemas/reporte_servicio_sistemas/${d}`, '_blank');
            }
        };
        $scope.ChangeSedes = () => {
            GetServicio();
        };
        $scope.BuscarCronograma = function () {
            var obj = {
                SedeId: $scope.SedeId,
                ServicioId: $scope.ServicioId,
                Vigencia: $scope.Year,
                Mes: $scope.FiltrarMes
            };
            MantenimientoPreventivoSistemaService.GetAllBySede(obj).then(function (c) {
                
                if (typeof c.data === "string") {
                    swal("Error", c.data, "error");
                }
                let lista = RemoveElemento(c.data);
                $scope.Listados = Crearpaquetes(lista);
            });
        };
        $scope.Imprimir = function () {
            setTimeout(function () {
                printDiv();
            }, 600);

        };
        $scope.AddSedes = function (s) {
            $scope.SedesSeleccionados = [$scope.Sedes[$scope.SedeId]];
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="consultas">

        function GetSedes() {
            SedeService.getAllSedeByUserId($rootScope.username.UserId).then(function (s) {
                if (typeof s.data === "string") {
                    swal("Error", s.data, "error");
                }
                $scope.Sedes = $filter("orderBy")(s.data, "Nombre");
                $scope.SedeId = $scope.Sedes[0].SedeId;
                GetServicio();
            });
        }
        function GetServicio() {
            ServicioService.getServicioBySede($scope.SedeId).then(function (c) {
                $scope.Servicios = c.data;
                $scope.ServicioId = c.data[0].ServicioId;
                $scope.BuscarCronograma();
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

        function Crearpaquetes(lst) {
            var paquete = {
                pack: []
            };
            var size = 20;
            var cont = 0;
//            if ($scope.ServicioId != "Todos") {
                for (var i = 0; i < lst.length; i++) {
                    for (var j = 0; j < lst[i].length; j += size) {
                        var lst2 = angular.copy(lst);
                        var smallarray = lst2[i].slice(j, j + size);
                        cont += size;
                        if (smallarray) {
                            paquete.pack.push(smallarray);
                        }
                    }
                }
//            } else {
//                for (let i = 0; i < lst.length; i++) {
//                    let smallarray = [];
//                    let ServicioId = lst[i].ServicioId;
//                    for (let j = 0; j < lst.length; j++) {
//                        if (lst[j].ServicioId == ServicioId) {
//                            smallarray.push(lst[j]);
//                        }
//                    }
//                    if (smallarray) {
//                        paquete.pack.push(smallarray);
//                    }
//                }
//            }
            console.log(paquete.pack)
            return paquete;
        }
        function RemoveElemento(lst) {
            var nuevo = [[]];
            if ($scope.FiltrarMes != "Todos") {
                for (var i in lst) {
                    for (var j in lst[i]) {
                        if (lst[i][j].Cronograma) {
                            if (lst[i][j].Cronograma.length > 0) {
                                nuevo[0].push(lst[i][j]);
                            }
                        }
                    }
                }
            } else {
                return lst;
            }
            return nuevo;
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
                mediaPrint: true,
                stylesheet: null,
                noPrintSelector: ".no-print",
                iframe: true,
                append: null,
                prepend: null,
                manuallyCopyFormValues: true,
                deferred: $.Deferred(),
                timeout: 1500,
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
            GetSedes();
            GetEmpresa();
            GetEncabezado();
        }
        _init();
    }]);

