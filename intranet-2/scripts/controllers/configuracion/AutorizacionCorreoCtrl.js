
'use strict';
app.controller('AutorizacionCorreoCtrl', ["$scope", "$rootScope", "$builder", "$filter", "AutorizacionService",
    function ($scope, $rootScope, $builder, $filter, AutorizacionService) {
        $scope.simpleTableOptions = null;
        _init();
        $scope.Protocolo = {
            Nombre: "",
            Items: [],
            CreatedBy: $rootScope.username.NombreCompleto
        };
        $scope.CrearProtocoloView = false;
        $scope.Item = {
            Destinatario: "",
            Email: "",
            Estado: "Activo",
            CreatedBy: $rootScope.username.NombreCompleto
        };
        
        //<editor-fold defaultstate="collapsed" desc="Botones">
        $scope.AddItem = function () {
            if (!$scope.ItemProtocolo.$valid) {
                angular.element("[name='" + $scope.ItemProtocolo.$name + "']").find('.ng-invalid:visible:first').focus();
            } else {
                $scope.Protocolo.Items.push($scope.Item);
                $scope.Item = {
                    Destinatario: "",
                    Email: "",
                    Estado: "Activo",
                    CreatedBy: $rootScope.username.NombreCompleto
                };
            }
        };
        
        $scope.Delete = function (i) {
            console.log(i);
            $scope.Protocolo.Items[i].Estado = 'Inactivo';
        };

        $scope.GuardarProtocolo = function () {
            if ($scope.Protocolo.Nombre === "") {
                swal("Error!", "Debe añadir un titulo al Protocolo.", "error");
            } else if ($scope.Protocolo.Items.length === 0) {
                swal("Error!", "Debe añadir minimo un item.", "error");
            } else {
                console.log($scope.Protocolo);
                
                var obj = {
                    Protocolo: JSON.stringify([$scope.Protocolo])
                };
                AutorizacionService.postProtocolo(obj).then(function (d) {
                    if (typeof d.data === "string") {
                        swal("Error!", d.data, "error");
                    } else {
                        swal("Enhorabuena!", "Se han guardado los datos satisfactoriamente", "success");
                        $scope.Reset();
                    }
                });
            }
        };

        $scope.ActualizarProtocolo = function () {
            if ($scope.protocolo.Nombre === "") {
                swal("Error!", "Debe añadir un titulo al Protocolo.", "error");
            } else if ($builder.forms['default'].length === 0) {
                swal("Error!", "Debe añadir un item al formulario.", "error");
            } else {
                $scope.protocolo.Formulario = JSON.stringify($builder.forms['default']);
                var obj = {
                    Protocolo: JSON.stringify([$scope.protocolo]),
                    ID: $scope.protocolo.ProtocoloId,
                    ModifiedBy: $rootScope.username.NombreCompleto
                };
                ProtocoloService.putProtocolo(obj).then(function (d) {
                    if (typeof d.data === "string") {
                        swal("Error!", d.data, "error");
                    } else {
                        swal("Enhorabuena!", "Se han guardado los datos satisfactoriamente", "success");
                        $scope.Reset();
                    }
                });
            }
        };

        $scope.DetalleProtocolo = function (i) {
            $scope.ProtocoloId = $scope.simpleTableOptions.data[i].ProtocoloId;
            GetItems($scope.ProtocoloId);
            $scope.Nombre_Protocolo = $scope.simpleTableOptions.data[i].Nombre;
            $scope.AddFlujoTrabajo = true;
            GetSede();
            $scope.$apply();
        };

        $scope.Reset = function () {
            $scope.cargado = false;
            _init();
            $scope.protocolo = {
                Nombre: "",
                Formulario: [],
                CreatedBy: $rootScope.username.NombreCompleto
            };
            $scope.CrearProtocoloView = false;
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Consulta">
        function GetProcesos() {
            AutorizacionService.getAllProtocoloAutorizacion().then(function (d) {
                $scope.simpleTableOptions = {
                    data: [],
                    aoColumns: [
                        {mData: 'ProtocoloId'},
                        {mData: 'Nombre'},
                        {mData: 'CreatedAt'},
                        {mData: 'Estado'},
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

        function _init() {
            GetProcesos();
        }
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Helpers">
        function SetFormat(lst) {
            for (var i in lst) {
                lst[i].Opciones = '<a class="btn  btn-primary btn-xs icon-only white" onclick=\"angular.element(this).scope().EditarProtocolo(' + i + ')\"><i class="fa fa-pencil"></i></a>\n\
                <a class="btn  btn-info btn-xs icon-only white" onclick=\"angular.element(this).scope().DetalleProtocolo(' + i + ')\"><i class="fa fa-briefcase"></i></a>';
            }
            return lst;
        }
        //</editor-fold>
    }]);



