app.controller('VerReporteSOL_sistemasCtrl', ["$scope", "$rootScope", "$state", "HojaVidaSistemaService", "SedeService", "ServicioService", "$stateParams", 'UsuarioService',
    'ReporteSistemaService', 'EmpresaService', '$filter', 'EncabezadoService',
    function ($scope, $rootScope, $state, HojaVidaSistemaService, SedeService, ServicioService, $stateParams, UsuarioService, ReporteSistemaService,
            EmpresaService, $filter, EncabezadoService) {
        //<editor-fold defaultstate="collapsed" desc="Variables">
        $scope.ImprimirReporte = false;
        $scope.IsAdminSistemas = $rootScope.username.IsAdminSistemas;
        $scope.ProcesandoPeticion = false;
        $scope.ReporteDiario = false;
        $scope.sol = "--";
        $scope.Empresa = null;
        $scope.Editar = false;
        $scope.Servicios = [];
        $scope.FallaDetectada = '';
        $scope.Sedes = [];
        $scope.Equipos = [];
        $scope.Usuarios = [];
        $scope.NumeroReporte = "";
        $scope.reporte = {
            NumeroReporte: "",
            SolicitudId: null,
            SedeId: $rootScope.HojaVidaRapida ? $rootScope.HojaVidaRapida.SedeId : $rootScope.solicitud ? $rootScope.solicitud.SedeId : "--",
            ServicioId: $rootScope.HojaVidaRapida ? $rootScope.HojaVidaRapida.ServicioId : $rootScope.solicitud ? $rootScope.solicitud.ServicioId : "--",
            Solicitante: "",
            Ubicacion: "",
            Responsable: "",
            TipoServicio: "",
            Fotos: "",
            EquipoId: $rootScope.HojaVidaRapida ? $rootScope.HojaVidaRapida.HojaVidaId : "",
            FallaReportada: "",
            FallaDetectada: [],
            ProcedimientoRealizado: "",
            Observaciones: "",
            Contador: $rootScope.HojaVidaRapida ? $rootScope.HojaVidaRapida.TipoArticulo === 'Impresora' ? "" : "N/A" : "N/A",
            TipoArticulo: "",
            EstadoFinal: [],
            ResponsableNombre: $rootScope.username.NombreCompleto,
            ResponsableCargo: $rootScope.username.Cargo,
            ResponsableFirma: $rootScope.username.Firma,
            RecibeFecha: new Date(),
            RecibeHora: new Date(),
            RecibeNombre: "",
            RecibeCargo: "",
            RecibeEmail: "",
            Fecha: new Date(),
            TipoReporte: "Manual",
            ReporteArchivo: null,
            ResponsableId: $rootScope.username.UserId,
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
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Botones">

        $scope.GetReporteById = function () {
            if ($rootScope.REPORTEID) {
                ReporteSistemaService.GetReporteById($rootScope.REPORTEID).then(function (d) {
                    console.log(d.data)
                    if (typeof d.data !== "string" && d.data.length > 0) {
                        $scope.reporte = d.data[0];
                        GetServicio();
                        GetUsuarios();
                        GetEquipos();
                        if (d.data[0].Servicio === "PLANTAS ELÉCTRICAS") {

                            $scope.ReporteDiario = true;
                        }
                        $scope.reporte.EquipoId = d.data[0].EquipoId;
                        $scope.reporte.FallaDetectada = typeof d.data[0].FallaDetectada === 'string' ? JSON.parse(d.data[0].FallaDetectada) : null;
                        $scope.reporte.EstadoFinal = typeof d.data[0].EstadoFinal === 'string' ? JSON.parse(d.data[0].EstadoFinal) : null;
                        $scope.reporte.Fotos = typeof d.data[0].Fotos === 'string' ? JSON.parse(d.data[0].Fotos) : null;
                        $scope.NumeroReporte = 'N°         ' + lpad(d.data[0].ReporteId.toString(), '0', 4);
                        $scope.reporte.ModifiedBy = $rootScope.username.NombreUsuario;
                        $scope.reporte.TipoReporte = "Manual";
                        $scope.reporte.RecibeFirma = d.data[0].RecibeFirma;
                        $scope.reporte.RecibeFecha = d.data[0].RecibeFecha;
                    } else {
                        swal("Error", d.data, "error");
                    }
                });
            }
        };
        $scope.Guardar = function () {
            //            console.log($scope.ficha.ProveedorId.originalObject )
            if (!$scope.Datos.$valid) {
                angular.element("[name='" + $scope.Datos.$name + "']").find('.ng-invalid:visible:first').focus();
            } else if ($scope.reporte.TipoServicio === "") {
                swal("Error", 'Debe seleccionar un tipo de servicio.', "error");
            } else if ($scope.reporte.EquipoId === "" && $scope.reporte.TipoServicio != "REDES") {
                swal("Error", 'Debe seleccionar un equipo.', "error");
            } else if ($scope.reporte.FallaDetectada.length == 0 && $scope.reporte.TipoServicio != "REDES") {
                swal("Error", 'Debe reportar como minimo una falla.', "error");
            } else if ($scope.reporte.EstadoFinal.length == 0 && $scope.reporte.TipoServicio != "REDES") {
                swal("Error", 'Debe reportar como minimo un estado final del equipo.', "error");
            } else if ($scope.reporte.RecibeCargo === "" || $scope.reporte.RecibeEmail === "" || $scope.reporte.RecibeNombre === "") {
                swal("Error", 'Debe seleccionar un usuario que recibira el reporte.', "error");
            } else {

                $scope.reporte.FallaDetectada = JSON.stringify($scope.reporte.FallaDetectada);
                $scope.reporte.MedidasAplicadas = JSON.stringify($scope.reporte.MedidasAplicadas);
                $scope.reporte.EstadoFinal = JSON.stringify($scope.reporte.EstadoFinal);
                $scope.reporte.Repuestos = JSON.stringify($scope.reporte.Repuestos);
                $scope.reporte.Fotos = JSON.stringify($scope.reporte.Fotos);
                var obj = {
                    Reporte: JSON.stringify([$scope.reporte]),
                    UserId: $rootScope.username.NombreUsuario
                };
                $scope.ProcesandoPeticion = true;
                ReporteSistemaService.postReporte(obj).then(function (d) {
                    $scope.ProcesandoPeticion = false;
                    if (typeof d.data != "string") {
                        $rootScope.$broadcast('ReporteId_new', { ReporteId: d.data[0] });
                        swal("Enhorabuena!", 'Se hanguardado los datos satisfactoriamente', "success");
                        if($state.current.name === "sistemas.reporte_servicio_sistemas"){
                            location.reload();
                        }
//                        $scope.Reset();
//                        $rootScope.ReporteId = d.data;
//                        $state.go("mantenimiento.ver_reporte", {reporte_id: $rootScope.ReporteId[0]});
                    } else {
                        $scope.reporte.FallaDetectada = JSON.parse($scope.reporte.FallaDetectada);
                        $scope.reporte.EstadoFinal = JSON.parse($scope.reporte.EstadoFinal);
                        $scope.reporte.Fotos = JSON.parse($scope.reporte.Fotos);
                        swal("Error", d.data, "error");
                    }
                }, function (e) {
                    $scope.ProcesandoPeticion = false;
                    $scope.reporte.FallaDetectada = JSON.parse($scope.reporte.FallaDetectada);
                    $scope.reporte.EstadoFinal = JSON.parse($scope.reporte.EstadoFinal);
                    $scope.reporte.Fotos = JSON.parse($scope.reporte.Fotos);
                    swal("Error", e, "error");
                });
            }
        };
        $scope.Actualizar = function () {
            if (!$scope.Datos.$valid) {
                angular.element("[name='" + $scope.Datos.$name + "']").find('.ng-invalid:visible:first').focus();
            } else if ($scope.reporte.TipoServicio === "") {
                swal("Error", 'Debe seleccionar un tipo de servicio.', "error");
            } else if ($scope.reporte.EquipoId === "") {
                swal("Error", 'Debe seleccionar un equipo.', "error");
            } else if ($scope.reporte.FallaDetectada.length == 0 && $scope.reporte.TipoServicio != "REDES") {
                swal("Error", 'Debe reportar como minimo una falla.', "error");
            } else if ($scope.reporte.EstadoFinal.length == 0 && $scope.reporte.TipoServicio != "REDES") {
                swal("Error", 'Debe reportar como minimo un estado final del equipo.', "error");
            } else if ($scope.reporte.RecibeCargo === "" || $scope.reporte.RecibeEmail === "" || $scope.reporte.RecibeNombre === "") {
                swal("Error", 'Debe seleccionar un usuario que recibira el reporte.', "error");
            } else {

                $scope.reporte.FallaDetectada = JSON.stringify($scope.reporte.FallaDetectada);
                $scope.reporte.MedidasAplicadas = JSON.stringify($scope.reporte.MedidasAplicadas);
                $scope.reporte.EstadoFinal = JSON.stringify($scope.reporte.EstadoFinal);
                $scope.reporte.Repuestos = JSON.stringify($scope.reporte.Repuestos);
                $scope.reporte.Fotos = JSON.stringify($scope.reporte.Fotos);
                $scope.reporte.ResponsableId = $rootScope.username.UserId;
                $scope.reporte.ResponsableNombre = $rootScope.username.NombreCompleto;
                $scope.reporte.ResponsableCargo = $rootScope.username.Cargo;
                var obj = {
                    Reporte: JSON.stringify([$scope.reporte]),
                    UserId: $rootScope.username.NombreUsuario
                };
                $scope.ProcesandoPeticion = true;
                ReporteSistemaService.putReporte(obj).then(function (d) {
                    console.log(d.data);
                    $scope.ProcesandoPeticion = false;
                    if (typeof d.data !== "string") {
                        swal("Enhorabuena!", 'Se hanguardado los datos satisfactoriamente', "success");
                        $scope.Reset();
                        $rootScope.ReporteId = d.data;
                    } else {
                        $scope.reporte.FallaDetectada = JSON.parse($scope.reporte.FallaDetectada);
                        $scope.reporte.MedidasAplicadas = JSON.parse($scope.reporte.MedidasAplicadas);
                        $scope.reporte.EstadoFinal = JSON.parse($scope.reporte.EstadoFinal);
                        $scope.reporte.Repuestos = JSON.parse($scope.reporte.Repuestos);
                        $scope.reporte.Fotos = JSON.parse($scope.reporte.Fotos);
                        swal("Error", d.data, "error");
                    }
                }, function (e) {
                    $scope.ProcesandoPeticion = false;
                    $scope.reporte.FallaDetectada = JSON.parse($scope.reporte.FallaDetectada);
                    $scope.reporte.MedidasAplicadas = JSON.parse($scope.reporte.MedidasAplicadas);
                    $scope.reporte.EstadoFinal = JSON.parse($scope.reporte.EstadoFinal);
                    $scope.reporte.Repuestos = JSON.parse($scope.reporte.Repuestos);
                    $scope.reporte.Fotos = JSON.parse($scope.reporte.Fotos);
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
        $scope.Imprimir = function () {
            $scope.ImprimirReporte = true;
            printDiv();
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
        $scope.ResetParcial = function () {
            $scope.sol = "--"
            $scope.reporte.Proveedor = "";
            $scope.reporte.NSerial = "";
            $scope.reporte.Modelo = "";
            $scope.reporte.SO = "";
            $scope.reporte.EquipoId = "";
        };
        $scope.Reset = function () {
            $scope.ReporteDiario = false;
            $scope.sol = "--";
            $('#configform')[0].reset();
            $scope.Usuarios = [];
            $scope.Equipos = [];
            $scope.reporte = {
                NumeroReporte: "",
                SolicitudId: null,
                SedeId: $rootScope.HojaVidaRapida ? $rootScope.HojaVidaRapida.SedeId : $rootScope.solicitud ? $rootScope.solicitud.SedeId : "--",
                ServicioId: $rootScope.HojaVidaRapida ? $rootScope.HojaVidaRapida.ServicioId : $rootScope.solicitud ? $rootScope.solicitud.ServicioId : "--",
                Solicitante: "",
                Ubicacion: "",
                Responsable: "",
                TipoServicio: "",
                Contador: $rootScope.HojaVidaRapida ? $rootScope.HojaVidaRapida.TipoArticulo === 'Impresora' ? "" : "N/A" : "N/A",
                EquipoId: $rootScope.HojaVidaRapida ? $rootScope.HojaVidaRapida.HojaVidaId : "",
                FallaReportada: "",
                FallaDetectada: [],
                ProcedimientoRealizado: "",
                Observaciones: "",
                EstadoFinal: [],
                Fotos: "",
                ResponsableNombre: $rootScope.username.NombreCompleto,
                ResponsableCargo: $rootScope.username.Cargo,
                ResponsableFirma: $rootScope.username.Firma,
                RecibeFecha: new Date(),
                RecibeHora: new Date(),
                RecibeNombre: "",
                RecibeCargo: "",
                RecibeEmail: "",
                Fecha: new Date(),
                TipoReporte: "Manual",
                ReporteArchivo: null,
                ResponsableId: $rootScope.username.UserId,
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
//            _init();
        };
        $scope.Recibe = function (i) {
            $scope.reporte.RecibeCargo = $scope.Usuarios[i].Cargo;
            $scope.reporte.RecibeNombre = $scope.Usuarios[i].NombreCompleto;
            $scope.reporte.Solicitante = $scope.Usuarios[i].NombreCompleto;
            $scope.reporte.RecibeId = $scope.Usuarios[i].UsuarioId;
            $scope.reporte.RecibeEmail = $scope.Usuarios[i].Email;
        };
        $scope.ChangeEquipo = function (i) {
            $scope.reporte.Ubicacion = $scope.Equipos[i].Ubicacion;
            $scope.reporte.Proveedor = $scope.Equipos[i].Proveedor;
            $scope.reporte.NSerial = $scope.Equipos[i].NSerial;
            $scope.reporte.Modelo = $scope.Equipos[i].Modelo;
            $scope.reporte.SO = $scope.Equipos[i].SO;
            $scope.reporte.EquipoId = $scope.Equipos[i].HojaVidaId;
//            $scope.reporte.Contador = $scope.Equipos[i].Contador;
            $scope.reporte.TipoArticulo = $scope.Equipos[i].TipoArticulo;
            if ($scope.Equipos[i].TipoArticulo === 'Impresora') {
                $scope.reporte.Contador = "";
            } else {
                $scope.reporte.Contador = "N\A";
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
            $("#ReporteSistema").print({
                globalStyles: true,
                mediaPrint: false,
                stylesheet: null,
                noPrintSelector: ".no-print",
                iframe: true,
                append: null,
                prepend: null,
                manuallyCopyFormValues: true,
                deferred: $.Deferred(),
                timeout: 2000,
                title: null,
                doctype: '<!doctype html>'
            });

            setTimeout(function () {
                $scope.ImprimirReporte = false;
                $scope.$apply();
            }, 1000);

        }
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Consultas">
        $scope.ChangeServicios = function () {
            GetServicio();
            $scope.ResetParcial();
        };
        $scope.ChangeEquipos = function () {
            GetEquipos();
            GetUsuarios();
            $scope.ResetParcial();
        };
        function GetServicio() {
            ServicioService.getServicioByUserId($rootScope.username.UserId).then(function (c) {
                var f = $filter('filter')(c.data, {SedeId: $scope.reporte.SedeId});
                $scope.Servicios = $filter("orderBy")(f, "Nombre");
            });
        }
        function GetSede() {
            SedeService.getAllSedeByUserId($rootScope.username.UserId).then(function (c) {
                $scope.Sedes = $filter("orderBy")(c.data, "Nombre");
                if ($scope.Sedes.length == 1) {
                    $scope.reporte.SedeId = $scope.Sedes[0].SedeId;
                    GetServicio();
                }
            });
        }
        function GetNReporte() {

            ReporteSistemaService.GetNReporte().then(function (d) {
                if (d.data.length > 0) {
                    $scope.NumeroReporte = 'N°         ' + lpad((parseInt(d.data[0].ReporteId) + 1).toString(), '0', 4);
                } else {
                    $scope.NumeroReporte = 'N°         ' + lpad('1', '0', 4);
                }
            });
        }
        function GetEquipos() {
            HojaVidaSistemaService.GetHojaVida($scope.reporte.ServicioId).then(function (e) {
//                console.log(e.data)
                $scope.Equipos = $filter("orderBy")(e.data, "Ubicacion");
            });
        }
        function GetUsuarios() {
            UsuarioService.GetALLusuariosByServicio($scope.reporte.ServicioId).then(function (u) {
                $scope.Usuarios = $filter("orderBy")(u.data, "NombreCompleto");
            });
        }
        function GetEncabezado() {
            EncabezadoService.getEncabezado().then(function (e) {
                $scope.Encabezado = e.data;
            });
        }
        function _init() {
            //            GetServicio();
            GetSede();
            GetNReporte();
            GetEmpresa();
            GetEncabezado();
            if ($rootScope.REPORTEID) {
                $scope.GetReporteById();
            }
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
        $scope.isInArray = function (list, item) {
            for (var i in list) {
                if (list[i]) {
                    if (list[i] == item) {
                        return true;
                    }
                }
            }
            return false;
        };
        //</editor-fold>
//        printDiv();
        _init();
    }]);



