app.controller('VerReporteServicioBIOMEDICOCtrl', ["$scope", "$rootScope", "$state", "HojaVidaServiceBiomedico", "SedeBiomedicoService", 
    "ServicioBiomedicoService", "SesionService", 'UsuarioBiomedicoService', 'ReporteBiomedicoService',
    'EmpresaService', '$filter', 'EncabezadoBiomedicoService', '$stateParams',
    function ($scope, $rootScope, $state, HojaVidaServiceBiomedico, SedeService, ServicioService, SesionService, 
    UsuarioService, ReporteService, EmpresaService, $filter,
            EncabezadoService, $stateParams) {
        //<editor-fold defaultstate="collapsed" desc="Variables">
        $scope.ProcesandoInformacion = false;
        $scope.ReporteId = null;
        $scope.sol = "--";
        $scope.Empresa = null;
        $scope.Editar = false;
        $scope.Servicios = [];
        $scope.FallaDetectada = '';
        $scope.Sedes = [];
        $scope.Equipos = [];
        $scope.Usuarios = [];
        $scope.NumeroReporte = "";
        var fechas = new Date();
        $scope.reporte = {
            NumeroReporte: "",
            SolicitudId: null,
            SedeId: $rootScope.HojaVidaRapida ? $rootScope.HojaVidaRapida.SedeId : $rootScope.solicitud ? $rootScope.solicitud.SedeId : "--",
            ServicioId: $rootScope.HojaVidaRapida ? $rootScope.HojaVidaRapida.ServicioId : $rootScope.solicitud ? $rootScope.solicitud.ServicioId : "--",
            Solicitante: "",
            Ubicacion: "",
            Responsable: "",
            TipoServicio: "",
            EquipoId: $rootScope.HojaVidaRapida ? $rootScope.HojaVidaRapida.HojaVidaId : "",
            FallaReportada: "",
            FallaDetectada: [],
            ProcedimientoRealizado: "",
            MedidasAplicadas: [],
            Repuestos: "",
            TotalRepuesto: "",
            Observaciones: "",
            EstadoFinal: [],
            ResponsableNombre: $rootScope.username.NombreCompleto,
            ResponsableCargo: $rootScope.username.Cargo,
//            ResponsableFirma: $rootScope.username.FirmaJefe,
            ResponsableId: $rootScope.username.UserId,
            RecibeFecha: fechas.getFullYear() + "-" + (fechas.getMonth() < 10 ? '0' + (fechas.getMonth() + 1) : fechas.getMonth() + 1) + "-" + fechas.getDate(),
            RecibeHora: new Date(),
            RecibeNombre: "",
            RecibeCargo: "",
            RecibeEmail: "",
            Fecha: fechas.getFullYear() + "-" + (fechas.getMonth() < 10 ? '0' + (fechas.getMonth() + 1) : fechas.getMonth() + 1) + "-" + fechas.getDate(),
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
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Botones">
        $scope.GetReporteById = function () {
            if ($scope.ReporteId) {
                ReporteService.GetReporteById($scope.ReporteId).then(function (d) {
                    if (typeof d.data !== "string") {
                        console.log(d.data[0]);
                        $scope.reporte = d.data[0];
                        GetServicio();
                        GetUsuarios();
                        GetEquipos();
                        $scope.reporte.EquipoId = d.data[0].EquipoId;
                        $scope.reporte.FallaDetectada = typeof d.data[0].FallaDetectada === 'string' ? JSON.parse(d.data[0].FallaDetectada) : null;
                        $scope.reporte.MedidasAplicadas = typeof d.data[0].MedidasAplicadas === 'string' ? JSON.parse(d.data[0].MedidasAplicadas) : null;
                        $scope.reporte.Repuestos = typeof d.data[0].Repuestos === 'string' ? JSON.parse(d.data[0].Repuestos) : null;
                        $scope.reporte.EstadoFinal = typeof d.data[0].EstadoFinal === 'string' ? JSON.parse(d.data[0].EstadoFinal) : null;
                        $scope.NumeroReporte = 'N°         ' + lpad(d.data[0].ReporteId.toString(), '0', 4);
                        $scope.reporte.ModifiedBy = $rootScope.username.NombreUsuario;
                        $scope.reporte.TipoReporte = "Manual";
                        $scope.reporte.RecibeFirma = d.data[0].RecibeFirma;
                    } else {
                        swal("Error", "No existe esta hoja de vida", "error");
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
            } else if ($scope.reporte.EquipoId === "") {
                swal("Error", 'Debe seleccionar un equipo.', "error");
            } else if ($scope.reporte.FallaDetectada.length == 0) {
                swal("Error", 'Debe reportar como minimo una falla.', "error");
            } else if ($scope.reporte.MedidasAplicadas.length == 0) {
                swal("Error", 'Debe reportar como minimo una medida aplicada.', "error");
            } else if ($scope.reporte.EstadoFinal.length == 0) {
                swal("Error", 'Debe reportar como minimo un estado final del equipo.', "error");
            } else if ($scope.reporte.RecibeCargo === "" || $scope.reporte.RecibeEmail === "" || $scope.reporte.RecibeNombre === "") {
                swal("Error", 'Debe seleccionar un usuario que recibira el reporte.', "error");
            } else {
                let reporte = angular.copy($scope.reporte);
                reporte.FallaDetectada = JSON.stringify($scope.reporte.FallaDetectada);
                reporte.MedidasAplicadas = JSON.stringify($scope.reporte.MedidasAplicadas);
                reporte.EstadoFinal = JSON.stringify($scope.reporte.EstadoFinal);
                reporte.Repuestos = JSON.stringify($scope.reporte.Repuestos);
                var obj = {
                    Reporte: JSON.stringify([reporte]),
                    UserId: $rootScope.username.NombreUsuario
                };
                $scope.ProcesandoInformacion = true;
                ReporteService.postReporte(obj).then(function (d) {
                    console.log(d.data);
                    $scope.ProcesandoInformacion = false;
                    if (typeof d.data !== "string") {
                        $rootScope.$broadcast('ReporteId_new', { ReporteId: d.data[0] });
                        swal("Enhorabuena!", 'Se hanguardado los datos satisfactoriamente', "success");
                        $scope.Reset();
                        $rootScope.ReporteId = d.data;
                        $state.go("mantenimiento.ver_reporte", {reporte_id: $rootScope.ReporteId[0]});
                    } else {
                        swal("Error", d.data, "error");
                    }
                }, function (e) {
                    $scope.ProcesandoInformacion = false;
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
            } else if ($scope.reporte.FallaDetectada.length == 0) {
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
                    UserId3: $rootScope.username.NombreUsuario
                };
                $scope.ProcesandoInformacion = true;
                ReporteService.putReporte(obj).then(function (d) {
                    console.log(d.data);
                    $scope.ProcesandoInformacion = false;
                    if (typeof d.data !== "string") {
                        swal("Enhorabuena!", 'Se hanguardado los datos satisfactoriamente', "success");
                        $scope.Reset();
                        $rootScope.ReporteId = d.data;
                    } else {
                        $scope.reporte.FallaDetectada = JSON.parse($scope.reporte.FallaDetectada);
                        $scope.reporte.MedidasAplicadas = JSON.parse($scope.reporte.MedidasAplicadas);
                        $scope.reporte.EstadoFinal = JSON.parse($scope.reporte.EstadoFinal);
                        $scope.reporte.Repuestos = JSON.parse($scope.reporte.Repuestos);
                        swal("Error", d.data, "error");
                    }
                }, function (e) {
                    $scope.ProcesandoInformacion = false;
                    $scope.reporte.FallaDetectada = JSON.parse($scope.reporte.FallaDetectada);
                    $scope.reporte.MedidasAplicadas = JSON.parse($scope.reporte.MedidasAplicadas);
                    $scope.reporte.EstadoFinal = JSON.parse($scope.reporte.EstadoFinal);
                    $scope.reporte.Repuestos = JSON.parse($scope.reporte.Repuestos);
                    swal("Error", e, "error");
                });
            }
        };
        $scope.AddRepuesto = function () {
            if ($scope.repuesto.Cantidad > 0 && $scope.repuesto.Descripcion !== "") {
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
        $scope.ResetParcial = function () {
            $scope.sol = "--"
            $scope.reporte.Marca = "";
            $scope.reporte.Serie = "";
            $scope.reporte.Modelo = "";
            $scope.reporte.Inventario = "";
            $scope.reporte.EquipoId = "";
        };
        $scope.Reset = function () {
            $scope.sol = "--";
            $('#configform')[0].reset();
            $scope.Usuarios = [];
            $scope.Equipos = [];
            $scope.reporte = {
                NumeroReporte: "",
                SolicitudId: null,
                SedeId: "--",
                Fecha: "",
                ServicioId: "--",
                Solicitante: "",
                Ubicacion: "",
                Responsable: "",
                TipoServicio: "",
                EquipoId: "",
                FallaReportada: "",
                FallaDetectada: [],
                ProcedimientoRealizado: "",
                MedidasAplicadas: [],
                Repuestos: "",
                TotalRepuesto: "",
                Observaciones: "",
                EstadoFinal: [],
                ResponsableNombre: $rootScope.username.NombreCompleto,
                ResponsableCargo: $rootScope.username.Cargo,
//            ResponsableFirma: $rootScope.username.FirmaJefe,
                ResponsableId: $rootScope.username.UserId,
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
        $scope.Recibe = function (i) {
            $scope.reporte.RecibeCargo = $scope.Usuarios[i].Cargo;
            $scope.reporte.RecibeId = $scope.Usuarios[i].UsuarioId;
            $scope.reporte.RecibeNombre = $scope.Usuarios[i].NombreCompleto;
            $scope.reporte.Solicitante = $scope.Usuarios[i].NombreCompleto;
            $scope.reporte.RecibeEmail = $scope.Usuarios[i].Email;
        };
        $scope.ChangeEquipo = function (i) {
            $scope.reporte.Marca = $scope.Equipos[i].Marca;
            $scope.reporte.Serie = $scope.Equipos[i].Serie;
            $scope.reporte.Modelo = $scope.Equipos[i].Modelo;
            $scope.reporte.Inventario = $scope.Equipos[i].Inventario;
            $scope.reporte.EquipoId = $scope.Equipos[i].HojaVidaId;
            $scope.reporte.Ubicacion = $scope.Equipos[i].Ubicacion;
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

            ReporteService.GetNReporte().then(function (d) {
                if (d.data.length > 0) {
                    $scope.NumeroReporte = 'N°         ' + lpad((parseInt(d.data[0].ReporteId) + 1).toString(), '0', 4);
                } else {
                    $scope.NumeroReporte = 'N°         ' + lpad('1', '0', 4);
                }
            });
        }
        function GetEquipos() {
            HojaVidaServiceBiomedico.GetHojaVida($scope.reporte.ServicioId).then(function (e) {
                $scope.Equipos = $filter("orderBy")(e.data, "Equipo");
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
            GetSede();
            GetNReporte();
            GetEmpresa();
            GetEncabezado();
            if ($rootScope.REPORTEID) {
                $scope.ReporteId = $rootScope.REPORTEID;
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
                    if (list[i] === item) {
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



