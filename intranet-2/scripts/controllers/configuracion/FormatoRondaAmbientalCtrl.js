'use strict';
app.controller('FormatoRondaAmbientalCtrl', ["$scope", "$rootScope", "$filter", "RondaAmbientalService", "SedeService", "ServicioService",
    function ($scope, $rootScope, $filter, RondaAmbientalService, SedeService, ServicioService) {
        //<editor-fold defaultstate="collapsed" desc="inicializar variables">
        $scope.cargado = false;
        $scope.simpleTableOptions = null;
        $scope.Items = [];
        // inicializamos las variables en _init()
        _init();

        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Consultas">
        function GetFormularios() {
            RondaAmbientalService.getAllFormularios().then(function (d) {
                $scope.simpleTableOptions = {
                    data: [],
                    aoColumns: [
//                        {mData: 'FormatoId'},
                        {mData: 'Identificador'},
                        {mData: 'Titulo'},
//                        {mData: 'Estado'},
                        {mData: 'CreatedBy'},
                        {mData: 'CreatedAt'},
                        {mData: 'Opciones'},
                    ],
                    "searching": true,
                    //                "sDom": "Tflt<'row DTTTFooter'<'col-sm-6'i><'col-sm-6'p>>",
                    //                "oTableTools": {
                    //                    "aButtons": [
                    //                        "xls", "pdf"
                    //                    ],
                    //                    "sSwfPath": "assets/swf/copy_csv_xls_pdf.swf"
                    //                },
                    "iDisplayLength": 25,
                    "language": {
                        "lengthMenu": "Mostrar _MENU_ registros por página",
                        "zeroRecords": " No hay Items Registrados ",
                        "infoFiltered": "(filtro de _MAX_ registros totales)",
                        "search": " Filtrar : ",
                        "oPaginate": {
                            "sPrevious": "Anterior",
                            "sNext": "Siguiente"
                        }
                    },
                    "aaSorting": []
                };
                $scope.simpleTableOptions.data = SetFormat(d.data);
                $scope.cargado = true;
            });
        }
        function GetSedes() {
            SedeService.getAllSede().then(function (c) {
                $scope.Sedes = c.data;
            });
        }
        function GetServicios() {
            ServicioService.getAllServicio().then(function (c) {
                $scope.Servicios = c.data;
            });
        }
        function GetFormatoServicio(id) {
            ServicioService.getServicioByFormatoId(id).then(function (s) {
                LogicaServicios(s.data);
            });
        }
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Botones guardar e imprimir">
        $scope.VerItems = function (i) {
            $scope.Items = $scope.simpleTableOptions.data[i].Items;
            $scope.$apply();
            $("#ItemsFormatoModal").modal("show");
        };
        $scope.VerServicios = function (i) {
            $scope.SelectServicio = [];
            $scope.select = angular.copy($scope.simpleTableOptions.data[i]);
            GetFormatoServicio($scope.simpleTableOptions.data[i].FormatoId);
            $('#FormatoServicioModal').modal('show');
        };
        $scope.SeleccionarServicio = function (s, index) {
            for (var i = 0, max = $scope.Servicios.length; i < max; i++) {
                $scope["servicio" + i] = false;
            }
            $scope["servicio" + index] = true;

            $scope.SelectServicio = $filter('orderBy')(s.Servicios, 'Nombre');
            
        };
        $scope.SelectAServicio = function (o, i) {
            var data = {
                FormatoServicio: JSON.stringify([
                    {
                        ServicioId: o.ServicioId,
                        FormatoId: $scope.select.FormatoId,
                        CreatedBy: $rootScope.username.NombreCompleto,
                    }
                ])
            };
            ServicioService.PostServicio(data).then(function (d) {
                $scope.SelectServicio[i].isSelected = $scope.SelectServicio[i].isSelected ? false : true;
            }, function (e) {
                swal("Hubo un Error", e, "error");
            });
        };

        //</editor-fold>
        function SetFormat(lst) {
            for (var i in lst) {
                lst[i].Opciones = '<a class="btn  btn-primary btn-xs icon-only white"  onclick=\"angular.element(this).scope().VerItems(' + i + ')\"><i class="fa fa-list"></i></a>' +
                        '<a class="btn  btn-info btn-xs icon-only white"  onclick=\"angular.element(this).scope().VerServicios(' + i + ')\"><i class="fa fa-building-o"></i></a>';

            }
            return lst;
        }
        function LogicaServicios(lst) {
            $scope.RelacionServicio = [];
            for (var i = 0, max = $scope.Sedes.length; i < max; i++) {
                $scope["servicio" + i] = false;
            }
            var ss = angular.copy($scope.Sedes);
            for (var i in ss) {
                ss[i].Servicios = VerificarServicios(ss[i].SedeId, lst);
                $scope.RelacionServicio.push(ss[i]);
            }
            console.log($scope.RelacionServicio);
        }
        function VerificarServicios(SedeId, lst) {
            var Servicios = [];
            var ser = angular.copy($scope.Servicios);
            for (var j in ser) {
                for (var k in lst) {
                    if (ser[j].ServicioId === lst[k].ServicioId) {
                        ser[j].isSelected = true;
                    }
                }
                if (SedeId === ser[j].SedeId) {
                    Servicios.push(ser[j]);
                }
            }
            return Servicios;
        }
        function _init() {
            GetFormularios();
            GetSedes();
            GetServicios();
        }



    }]);





