
'use strict';
app.controller('ProtocoloCtrl', ["$scope", "$rootScope", "$builder", "$filter", "ProtocoloService", "SedeService", "ServicioService", "UsuarioService",
    "FlujoTrabajoService", "AnexoService",
    function ($scope, $rootScope, $builder, $filter, ProtocoloService, SedeService, ServicioService, UsuarioService, FlujoTrabajoService, AnexoService) {
        $scope.simpleTableOptions = null;
        $scope.CrearProtocolo = false;
        $scope.cargado = false;
        $scope.UpdateProtocolo = false;
        $scope.AddItemFlujoTrabajo = false;
        $scope.items = [];
        $scope.Servicios = [];
        $scope.Usuarios = [];
        $scope.Verificadores = [];
        $scope.ProtocoloId = null;
        $scope.Nombre_Protocolo = "";
        $scope.AnexosData = false;
        $scope.UsuarioVerificador = {};
        $scope.Verificador = {
            SedeId: "",
            ServicioId: "",
            UsuarioId: "",
            CreatedBy: $rootScope.username.NombreCompleto
        };
        $scope.FlujoTrabajo = {
            ProtocoloId: "",
            Verificadores: [],
            CreatedBy: $rootScope.username.NombreCompleto
        };

        _init();
        $scope.protocolo = {
            Nombre: "",
            Formulario: [],
            CreatedBy: $rootScope.username.NombreCompleto
        };
        $scope.AddFlujoTrabajo = false;
        //<editor-fold defaultstate="collapsed" desc="Botones">
        $scope.AddItem = function () {
            $('#VerificadoresModal').modal('show');
        };

        $scope.CrearAnexo = function (u) {
            console.log(u)
            AnexoService.GetByVerificadorIdId(u.UsuarioId, u.FlujoTrabajoId).then(function (d) {
                console.log(d.data)
                $scope.UsuarioVerificador = u;
                if (typeof d.data === "string") {
                    swal("Error!", d.data, "error");
                } else if (d.data.length > 0) {
                    $scope.UsuarioVerificador.Anexo = d.data[0].Anexo;
                    if ($scope.UsuarioVerificador.Anexo) {
                        var Anexos = JSON.parse($scope.UsuarioVerificador.Anexo);
                        for (var i in Anexos) {
                            $builder.addFormObject('Anexos', Anexos[i]);
                        }
                    }
                    $scope.AnexosData = true;

                } else {
                    $scope.AnexosData = true;
                }

            });
        };

        $scope.GuardarAnexo = function () {
            $scope.UsuarioVerificador.Anexo = JSON.stringify($builder.forms['Anexos']);
            $scope.AnexosData = false;
            $scope.ResetAnexos();
        };

        $scope.ResetAnexos = function () {
            while ($builder.forms['Anexos'].length > 0) {
                $builder.removeFormObject('Anexos', 0);
            }
        };

        $scope.AnadirFlujo = function () {
            $scope.FlujoTrabajo.Estado = 'Activo';
            $scope.items.push($scope.FlujoTrabajo);
            $scope.FlujoTrabajo = {
                ProtocoloId: "",
                Verificadores: [],
                CreatedBy: $rootScope.username.NombreCompleto
            };
        };

        $scope.UpdateFlujo = function () {
            for (var i in $scope.items) {
//                for (var k in $scope.items[i].Verificadores) {
                console.log($scope.items[i].FlujoTrabajoId + " == " + $scope.FlujoTrabajo.FlujoTrabajoId)
                if ($scope.items[i].FlujoTrabajoId == $scope.FlujoTrabajo.FlujoTrabajoId) {
                    $scope.items[i] = $scope.FlujoTrabajo;
                    $('#VerificadoresUpdateModal').modal('hide');
                    $scope.FlujoTrabajo = {
                        ProtocoloId: "",
                        Verificadores: [],
                        CreatedBy: $rootScope.username.NombreCompleto
                    };
                }
//                }
            }
        };

        $scope.GuardarProtocolo = function () {
            if ($scope.protocolo.Nombre === "") {
                swal("Error!", "Debe añadir un titulo al Protocolo.", "error");
            } else if ($builder.forms['default'].length === 0) {
                swal("Error!", "Debe añadir un item al formulario.", "error");
            } else {
                $scope.protocolo.Formulario = JSON.stringify($builder.forms['default']);
                var obj = {
                    Protocolo: JSON.stringify([$scope.protocolo])
                };
                ProtocoloService.postProtocolo(obj).then(function (d) {
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
            $scope.CrearProtocolo = false;
            $scope.UpdateProtocolo = false;
            $scope.AddFlujoTrabajo = false;
        };

        $scope.ResetBuilder = function () {
            while ($builder.forms['default'].length > 0) {
                $builder.removeFormObject('default', 0);
            }
        };

        $scope.EditarProtocolo = function (i) {
            while ($builder.forms['default'].length > 0) {
                $builder.removeFormObject('default', 0);
            }
            console.log($scope.simpleTableOptions.data[i]);
            $scope.protocolo = angular.copy($scope.simpleTableOptions.data[i]);
            $scope.protocolo.Formulario = JSON.parse($scope.protocolo.Formulario);
            for (var i in $scope.protocolo.Formulario) {
                $builder.addFormObject('default', $scope.protocolo.Formulario[i]);
            }
            $scope.UpdateProtocolo = true;
            $scope.$apply();
        };

        $scope.ChangeSede = function () {
            $scope.Usuarios = [];
            $scope.Servicios = [];
            GetServicio();
            $scope.Verificador.ServicioId = "";
            $scope.Verificador.UsuarioId = "";
        };

        $scope.ChangeServicios = function () {
            $scope.Usuarios = [];
            GetUsuarios();
            $scope.Verificador.UsuarioId = "";
        };
        $scope.AddToList = function () {
            $scope.Verificador.Estado = 'Activo';
            for (var k in $scope.Sedes) {
                if ($scope.Sedes[k].SedeId == $scope.Verificador.SedeId) {
                    $scope.Verificador.Sede = $scope.Sedes[k].Nombre;
                }
            }

            for (var k in $scope.Servicios) {
                if ($scope.Servicios[k].ServicioId == $scope.Verificador.ServicioId) {
                    $scope.Verificador.Servicio = $scope.Servicios[k].Nombre;
                }
            }

            for (var k in $scope.Usuarios) {
                if ($scope.Usuarios[k].UsuarioId == $scope.Verificador.UsuarioId) {
                    $scope.Verificador.NombreCompleto = $scope.Usuarios[k].NombreCompleto;
                }
            }


            $scope.FlujoTrabajo.ProtocoloId = $scope.ProtocoloId;
            $scope.FlujoTrabajo.Verificadores.push($scope.Verificador);
            $scope.Verificador = {
                SedeId: "",
                ServicioId: "",
                UsuarioId: "",
                CreatedBy: $rootScope.username.NombreCompleto
            };
            $scope.Usuarios = [];
            $scope.Servicios = [];
        };
        $scope.GuardarFlujoTrabajo = function () {
            for (var k in $scope.items) {
                $scope.items[k].Orden = k;
                $scope.items[k].ModifiedBy = $rootScope.username.NombreCompleto;
            }
            console.log($scope.items)
            if ($scope.items.length > 0) {
                var obj = {
                    FlujoTrabajo: JSON.stringify([$scope.items])
                };

                FlujoTrabajoService.postFlujoTrabajo(obj).then(function (d) {
                    if (typeof d.data === "string") {
                        swal("Error!", d.data, "error");
                    } else {
                        swal("Enhorabuena!", "Se han guardado los datos satisfactoriamente", "success");
                        $scope.Reset();
                    }
                });
            } else {
                swal("Error!", "Debe ingresar minimo un item.", "error");
            }
        };
        $scope.Delete = function (i) {
            $scope.items[i].Estado = 'Inactivo';
        };
        $scope.DeleteVerificador = function (i) {
            $scope.FlujoTrabajo.Verificadores.splice(i, 1);
        };
        $scope.EditarVerificador = function (o) {
            $scope.FlujoTrabajo = angular.copy(o);
            $('#VerificadoresUpdateModal').modal('show');
        };
        $scope.InactivarVerificador = function (i) {
            $scope.FlujoTrabajo.Verificadores[i].Estado = 'Inactivo';
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Consulta">
        function GetProcesos() {
            ProtocoloService.GetAllProtocolo($rootScope.username.UserId).then(function (d) {
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

        function GetItems(ProtocoloId) {
            FlujoTrabajoService.GetFlujoTrabajoByProtocoloId(ProtocoloId).then(function (d) {
                console.log(d.data)
                $scope.items = d.data;
            });
        }

        function GetServicio() {
            ServicioService.getServicioByUserId($rootScope.username.UserId).then(function (c) {
                var f = $filter('filter')(c.data, {SedeId: $scope.Verificador.SedeId});
                $scope.Servicios = $filter("orderBy")(f, "Nombre");
            });
        }

        function GetUsuarios() {
            UsuarioService.GetALLusuariosByServicio($scope.Verificador.ServicioId).then(function (u) {
                $scope.Usuarios = $filter("orderBy")(u.data, "NombreCompleto");
            });
        }

        function GetSede() {
            SedeService.getAllSedeByUserId($rootScope.username.UserId).then(function (c) {
                $scope.Sedes = $filter("orderBy")(c.data, "Nombre");

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



