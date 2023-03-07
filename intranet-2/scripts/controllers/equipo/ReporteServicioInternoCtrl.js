app.controller('ReporteServicioInternoCtrl', ["$scope", "$rootScope", "$state", "HojaVidaService", "SedeService", "ServicioService", "SesionService",
    'UsuarioService', 'ReporteService', 'EmpresaService', 'EncabezadoService',
    function ($scope, $rootScope, $state, HojaVidaService, SedeService, ServicioService, SesionService, UsuarioService, ReporteService, EmpresaService, EncabezadoService) {
        //<editor-fold defaultstate="collapsed" desc="Variables">
        $scope.Empresa = null;
        $scope.Editar = false;
        $scope.Servicios = [];
        $scope.FallaDetectada = '';
        $scope.Sedes = [];
        $scope.Equipos = [];
        $scope.Usuarios = [];
        $scope.NumeroReporte = "";
        $scope.reporte = {};
        if (!$rootScope.solicitud) {
            window.history.back();
        } else {
            $scope.reporte = {
                NumeroReporte: "",
                SolicitudId: $rootScope.solicitud.SolicitudId,
                SedeId: $rootScope.solicitud.SedeId,
                Sede: $rootScope.solicitud.Sede,
                Fecha: "",
                ServicioId: $rootScope.solicitud.ServicioId,
                Servicio: $rootScope.solicitud.Servicio,
                Solicitante: $rootScope.solicitud.Solicitante,
                Ubicacion: $rootScope.solicitud.Ubicacion,
                Responsable: "",
                TipoServicio: "CORRECTIVO",
                EquipoId: $rootScope.solicitud.EquipoId,
                Equipo: $rootScope.solicitud.Equipo,
                Marca: $rootScope.solicitud.Marca,
                Modelo: $rootScope.solicitud.Modelo,
                Serie: $rootScope.solicitud.Serie,
                Inventario: $rootScope.solicitud.Inventario,
                FallaReportada: "",
                FallaDetectada: [],
                ProcedimientoRealizado: "",
                MedidasAplicadas: [],
                Repuestos: "",
                TotalRepuesto: "",
                Observaciones: "FALLA REPORTADA:\n" + $rootScope.solicitud.Descripcion,
                EstadoFinal: [],
                ResponsableNombre: $rootScope.username.NombreCompleto,
                ResponsableCargo: $rootScope.username.Cargo,
                ResponsableId: $rootScope.username.UserId,
                Ciudad: "N/A",
                HoraInicio: null,
                HoraFinal: null,
                NivelCombustible: null,
                NivelAguaRefrigerante: null,
                NivelAceite: null,
                NivelElectrolitoBateria: null,
                VoltajeBateria: null,
                FechaUltCambioAceite: null,
                FiltroAire: null,
                Fugas: null,
                RecibeFecha: new Date(),
                RecibeHora: new Date(),
                RecibeNombre: "",
                RecibeCargo: "",
                RecibeEmail: "",
                Fecha: new Date(),
                Repuestos: [],
                TotalRepuesto: 0,
                TipoReporte: "Manual",
                ReporteArchivo: null,
                CreatedBy: $rootScope.username.NombreUsuario
            };
            GetUsuarios();
        }

        $scope.repuesto = {
            Codigo: "",
            Descripcion: "",
            Cantidad: 1,
            Valor: 0,
            EditMode: false
        };
        $scope.EditRepuesto = {
            Codigo: "",
            Descripcion: "",
            Cantidad: 1,
            Valor: 0
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Botones">
        $scope.Guardar = function () {
            //            console.log($scope.ficha.ProveedorId.originalObject )
            if (!$scope.Datos.$valid) {
                angular.element("[name='" + $scope.Datos.$name + "']").find('.ng-invalid:visible:first').focus();
            } else if ($scope.reporte.TipoServicio === "") {
                swal("Error", 'Debe seleccionar un tipo de servicio.', "error");
            } else if ($scope.reporte.EquipoId === "") {
                swal("Error", 'Debe seleccionar un equipo.', "error");
            } else if ($scope.reporte.FallaDetectada.length == 0 && $scope.reporte.TipoServicio != 'INFRAESTRUCTURA') {
                swal("Error", 'Debe reportar como minimo una falla.', "error");
            } else if ($scope.reporte.MedidasAplicadas.length == 0) {
                swal("Error", 'Debe reportar como minimo una medida aplicada.', "error");
            } else if ($scope.reporte.EstadoFinal.length == 0) {
                swal("Error", 'Debe reportar como minimo un estado final del equipo.', "error");
            } else if ($scope.reporte.RecibeCargo === "" || $scope.reporte.RecibeEmail === "" || $scope.reporte.RecibeNombre === "") {
                swal("Error", 'Debe seleccionar un usuario que recibira el reporte.', "error");
            } else {

                $scope.reporte.FallaDetectada = JSON.stringify($scope.reporte.FallaDetectada);
                $scope.reporte.MedidasAplicadas = JSON.stringify($scope.reporte.MedidasAplicadas);
                $scope.reporte.EstadoFinal = JSON.stringify($scope.reporte.EstadoFinal);
                $scope.reporte.Repuestos = JSON.stringify($scope.reporte.Repuestos);
                var obj = {
                    Reporte: JSON.stringify([$scope.reporte]),
                    UserId: $rootScope.username.NombreUsuario
                };
                ReporteService.postReporte(obj).then(function (d) {
                    console.log(d.data);
                    if (typeof d.data != "string") {
                        swal("Enhorabuena!", 'Se hanguardado los datos satisfactoriamente', "success");
                        $scope.reporte.FallaDetectada = JSON.parse($scope.reporte.FallaDetectada);
                        $scope.reporte.MedidasAplicadas = JSON.parse($scope.reporte.MedidasAplicadas);
                        $scope.reporte.EstadoFinal = JSON.parse($scope.reporte.EstadoFinal);
                        $scope.reporte.Repuestos = JSON.parse($scope.reporte.Repuestos);
//                        $scope.Reset();
//                        $rootScope.ReporteId = d.data;
//                        $state.go("mantenimiento.ver_reporte", {reporte_id: $rootScope.ReporteId[0]});
                    } else {
                        swal("Error", d.data, "error");
                    }
                }, function (e) {
                    swal("Error", e, "error");
                });
            }
        };
        $scope.AddRepuesto = function () {
            if ($scope.repuesto.Cantidad > 0 && $scope.repuesto.Descripcion != "") {
                $scope.reporte.Repuestos.push($scope.repuesto);
                $scope.repuesto = {
                    Codigo: "",
                    Descripcion: "",
                    Cantidad: 1,
                    Valor: 0,
                    EditMode: false
                };
                GetTotal();
            } else {
                swal("Error", "La cantidad no puede ser CERO y la descripción no debe estar vacia", "error");
            }
        };

        $scope.DeleteRepuesto = function (i) {
            $scope.reporte.Repuestos.splice(i, 1);
        };
        $scope.Edit = function (i) {
            $scope.EditRepuesto = angular.copy($scope.reporte.Repuestos[i]);
            $scope.reporte.Repuestos[i].EditMode = true;
        };
        $scope.EditarRepuesto = function (i) {
            $scope.reporte.Repuestos[i] = $scope.EditRepuesto;
            $scope.reporte.Repuestos[i].EditMode = false;
            GetTotal();
        };
        $scope.Cancelar = function (i) {
            $scope.reporte.Repuestos[i].EditMode = false;
            $scope.EditRepuesto = {
                Codigo: "",
                Descripcion: "",
                Cantidad: 1,
                Valor: 0
            };
        };
        $scope.AddFallas = function (txt) {
            var cont = 0;
            for (var i in $scope.reporte.FallaDetectada) {
                if ($scope.reporte.FallaDetectada[i] === txt) {
                    cont++;
                    $scope.reporte.FallaDetectada.splice(i, 1);
                }
            }
            if (cont == 0 && txt != "") {
                $scope.reporte.FallaDetectada.push(txt);
            }
        };
        $scope.AddMedidas = function (txt) {
            var cont = 0;
            for (var i in $scope.reporte.MedidasAplicadas) {
                if ($scope.reporte.MedidasAplicadas[i] === txt) {
                    cont++;
                    $scope.reporte.MedidasAplicadas.splice(i, 1);
                }
            }
            if (cont == 0 && txt != "") {
                $scope.reporte.MedidasAplicadas.push(txt);
            }
        };
        $scope.AddEstadoFinal = function (txt) {
            var cont = 0;
            for (var i in $scope.reporte.EstadoFinal) {
                if ($scope.reporte.EstadoFinal[i] === txt) {
                    cont++;
                    $scope.reporte.EstadoFinal.splice(i, 1);
                }
            }
            if (cont == 0 && txt != "") {
                $scope.reporte.EstadoFinal.push(txt);
            }
        };
        $scope.Reset = function () {
            $('#configform')[0].reset();
            $scope.Usuarios = [];
            $scope.Equipos = [];
            $scope.reporte = {
                NumeroReporte: "",
                SolicitudId: $rootScope.solicitud.SolicitudId,
                SedeId: $rootScope.solicitud.SedeId,
                Sede: $rootScope.solicitud.Sede,
                Fecha: "",
                ServicioId: $rootScope.solicitud.ServicioId,
                Servicio: $rootScope.solicitud.Servicio,
                Solicitante: $rootScope.solicitud.Solicitante,
                Ubicacion: $rootScope.solicitud.Ubicacion,
                Responsable: "",
                TipoServicio: "CORRECTIVO",
                EquipoId: $rootScope.solicitud.EquipoId,
                Equipo: $rootScope.solicitud.Equipo,
                Marca: $rootScope.solicitud.Marca,
                Modelo: $rootScope.solicitud.Modelo,
                Serie: $rootScope.solicitud.Serie,
                Inventario: $rootScope.solicitud.Inventario,
                FallaReportada: "",
                FallaDetectada: [],
                ProcedimientoRealizado: "",
                MedidasAplicadas: [],
                Repuestos: "",
                TotalRepuesto: "",
                Observaciones: "FALLA REPORTADA:\n" + $rootScope.solicitud.Descripcion,
                EstadoFinal: [],
                ResponsableNombre: $rootScope.username.NombreCompleto,
                ResponsableCargo: $rootScope.username.Cargo,
                ResponsableId: $rootScope.username.UserId,
                Ciudad: "N/A",
                HoraInicio: null,
                HoraFinal: null,
                NivelCombustible: null,
                NivelAguaRefrigerante: null,
                NivelAceite: null,
                NivelElectrolitoBateria: null,
                VoltajeBateria: null,
                FechaUltCambioAceite: null,
                FiltroAire: null,
                Fugas: null,
                RecibeFecha: new Date(),
                RecibeHora: new Date(),
                RecibeNombre: "",
                RecibeCargo: "",
                RecibeEmail: "",
                Fecha: new Date(),
                Repuestos: [],
                TotalRepuesto: 0,
                TipoReporte: "Manual",
                ReporteArchivo: null,
                CreatedBy: $rootScope.username.NombreUsuario
            };
            $scope.repuesto = {
                Codigo: "",
                Descripcion: "",
                Cantidad: 1,
                Valor: 0,
                EditMode: false
            };
            $scope.EditRepuesto = {
                Codigo: "",
                Descripcion: "",
                Cantidad: 1,
                Valor: 0
            };
            $("#ex2_value").val('');
            $scope.FallaDetectada = '';
            $('input[type=checkbox]:checked').removeAttr('checked');
            _init();
        };
        $scope.Recibe = function () {
            for (var i in $scope.Usuarios) {
                if ($scope.reporte.Solicitante === $scope.Usuarios[i].NombreCompleto) {
                    $scope.reporte.RecibeCargo = $scope.Usuarios[i].Cargo;
                    $scope.reporte.RecibeNombre = $scope.Usuarios[i].NombreCompleto;
                    $scope.reporte.RecibeEmail = $scope.Usuarios[i].Email;
                    $scope.reporte.RecibeId = $scope.Usuarios[i].UsuarioId;
                }
            }
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Helpers">
        function GetTotal() {
            $scope.reporte.TotalRepuesto = 0;
            for (var i in $scope.reporte.Repuestos) {
                $scope.reporte.TotalRepuesto += parseFloat($scope.reporte.Repuestos[i].Valor);
            }
        }
        function printDiv() {

            $("#myElementId").print({
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

        }
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Consultas">

        function GetNReporte() {

            ReporteService.GetNReporte().then(function (d) {
                if (d.data.length > 0) {
                    $scope.NumeroReporte = 'N°         ' + lpad((parseInt(d.data[0].ReporteId) + 1).toString(), '0', 4);
                } else {
                    $scope.NumeroReporte = 'N°         ' + lpad('1', '0', 4);
                }
            });
        }
        function GetUsuarios() {
            UsuarioService.GetALLusuariosByServicio($scope.reporte.ServicioId).then(function (u) {
                $scope.Usuarios = u.data;
                $scope.Recibe();
            });
        }
        function _init() {
            GetNReporte();
            GetEmpresa();
            GetEncabezado();
        }
        function lpad(str, padString, length) {
            while (str.length < length)
                str = padString + str;
            return str;
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
//        printDiv();
        _init();
    }]);



