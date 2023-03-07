app.controller('FichaTecnicaCtrl', ["$scope", "$rootScope", "HojaVidaService", "ServicioService", "ProveedorService", "SedeService", "FrecuenciaService",
    "SesionService", "EmpresaService", "EncabezadoService", "$stateParams", "$filter",
    function ($scope, $rootScope, HojaVidaService, ServicioService, ProveedorService, SedeService, FrecuenciaService, SesionService, EmpresaService, EncabezadoService,
            $stateParams, $filter) {
        //<editor-fold defaultstate="collapsed" desc="Variables">
        $rootScope.HojaVidaRapida = null;
        $scope.Encabezado = null;
        $scope.Empresa = null;
        $scope.pagina = 1;
        $scope.ToPrint = false;
        $rootScope.HojaVida = null;
        $rootScope.ver_reportes = null;
        $rootScope.ficha = null;
        $scope.onlyNumbers = /^\d+$/;
        $scope.HojaVidaId = null;
        $scope.Editar = false;
        $scope.Servicios = [];
        $scope.Proveedores = [];
        $scope.Frecuencias = [];
        $scope.NumeroHojaVida = "";
        $scope.image = "/intranet-2/public_html/image/avatar.jpg";
        $scope.accesorio = {
            Nombre: "",
            Cantidad: 0,
            EditMode: false
        };
        $scope.EditAccesorio = {
            Nombre: "",
            Cantidad: 0
        };
        $scope.ficha = SesionService.get("Ficha_" + $rootScope.username.NombreUsuario) || {
            SedeId: "--",
            ServicioId: "--",
            FrecuenciaMantenimientoId: "--",
            FrecuenciaCalibracionId: 5,
            RecomendacioneFabricante: "",
            Ubicacion: "",
            Equipo: "",
            Marca: "",
            Modelo: "",
            Serie: "",
            FechaInstalacion: null,
            FechaCalibracion: null,
            RegSanitario: "",
            FechaAdquisicion: "",
            Garantia: "",
            ProveedorId: null,
            Peso: "",
            Presion: "",
            TipoRiesgo: "NO TIENE",
            Voltaje: "",
            Temperatura: "",
            Amperaje: "",
            Frecuencia: "",
            Potencia: "",
            Capacidad: "",
            Accesorios: [],
            Foto: ""
        };
        $scope.DataImg = [];
        $scope.Photo = "";
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Botones">
        $scope.AddAccesorio = function () {
            $scope.ficha.Accesorios.push($scope.accesorio);
            $scope.accesorio = {
                Nombre: "",
                Cantidad: 0
            };
        };
        $scope.CrearHojaVida = function () {

            $scope.ficha.UserId = $rootScope.username.NombreUsuario;
            var obj = {
                HojaVida: JSON.stringify([$scope.ficha]),
                UserId: $rootScope.username.NombreUsuario
            };
            HojaVidaService.postHojaVida(obj).then(function (d) {
                if (typeof d.data != "string") {
                    swal("Enhorabuena", "Se ha guardado la hoja de vida con exito", "success");
                    $scope.Reset();
                } else {
                    swal("Error", d.data, "error");
                }
            }, function (e) {
                swal("Error", e, "error");
            });
        };
        $scope.UpdateHojaVida = function () {
            $scope.ficha.ModifiedBy = $rootScope.username.NombreUsuario;
            if ($scope.DataImg.compressed) {
                $scope.ficha.Foto = $scope.DataImg.compressed.dataURL;
            }
            var obj = {
                HojaVida: JSON.stringify([$scope.ficha]),
                UserId: $rootScope.username.NombreUsuario
            };
            HojaVidaService.putHojaVida(obj).then(function (d) {
                if (typeof d.data != "string") {
                    swal("Enhorabuena", "Se ha actualizado la hoja de vida con exito", "success");
                    $scope.Reset();
                } else {
                    swal("Error", d.data, "error");
                }
            }, function (e) {
                swal("Error", e, "error");
            });
        };
        $scope.DeleteAccesorio = function (i) {
            $scope.ficha.Accesorios.splice(i, 1);
        };
        $scope.Edit = function (i) {
            $scope.EditAccesorio = angular.copy($scope.ficha.Accesorios[i]);
            $scope.ficha.Accesorios[i].EditMode = true;
        };
        $scope.EditarAccesorio = function (i) {
            $scope.ficha.Accesorios[i] = $scope.EditAccesorio;
            $scope.ficha.Accesorios[i].EditMode = false;
        };
        $scope.Cancelar = function (i) {
            $scope.ficha.Accesorios[i].EditMode = false;
            $scope.EditAccesorio = {
                Nombre: "",
                Cantidad: 0
            };
        };
        $scope.Guardar = function () {
            if ($scope.DataImg.compressed) {
                $scope.ficha.Foto = $scope.DataImg.compressed.dataURL;
            }
            //            console.log($scope.ficha.ProveedorId.originalObject )
            if (!$scope.Datos.$valid) {
                angular.element("[name='" + $scope.Datos.$name + "']").find('.ng-invalid:visible:first').focus();
            } else if ($scope.ficha.ServicioId === "--") {
                swal("Error", 'Debe seleccionar un Servicio.', "error");
            } else if ($scope.ficha.ProveedorId === null || !$scope.ficha.ProveedorId.originalObject) {
                swal("Error", 'Debe seleccionar un proveedor.', "error");
            } else if (!$scope.ficha.Foto) {
                swal("Error", 'Debe añadir una foto.', "error");
            } else if ($scope.ficha.FrecuenciaMantenimientoId === "--") {
                swal("Error", 'Debe añadir una Frecuencia de Mantenimiento.', "error");
            } else if (!$scope.ficha.FrecuenciaCalibracionId) {
                swal("Error", 'Debe añadir una Frecuencia de Calibración.', "error");
            } else {
                $scope.ficha.ProveedorId = $scope.ficha.ProveedorId.originalObject.ProveedorId;
                $scope.CrearHojaVida();
            }
        };
        $scope.Reset = function () {
            GetNHojaVida();
            SesionService.remove("Ficha_" + $rootScope.username.NombreUsuario);
            $rootScope.HojaVida = null;
            $rootScope.ver_reportes = null;
            $rootScope.ficha = null;
            $scope.Editar = false;
            $scope.image = "/intranet-2/public_html/image/avatar.jpg";
            $scope.accesorio = {
                Nombre: "",
                Cantidad: 0,
                EditMode: false
            };
            $scope.EditAccesorio = {
                Nombre: "",
                Cantidad: 0
            };
            $scope.ficha = {
                SedeId: "--",
                ServicioId: "--",
                FrecuenciaMantenimientoId: "--",
                FrecuenciaCalibracionId: "--",
                RecomendacioneFabricante: "",
                Ubicacion: "",
                Equipo: "",
                Marca: "",
                Modelo: "",
                Serie: "",
                RegSanitario: "",
                FechaAdquisicion: "",
                Garantia: "",
                FechaInstalacion: null,
                FechaCalibracion: null,
                ProveedorId: null,
                Peso: "",
                Presion: "",
                TipoRiesgo: "NO TIENE",
                Voltaje: "",
                Temperatura: "",
                Amperaje: "",
                Frecuencia: "",
                Potencia: "",
                Capacidad: "",
                Accesorios: [],
                Foto: ""
            };
            $scope.DataImg = [];
            $scope.ARCHIVO = null;
            $scope.Photo = "";
            $("#ex1_value").val('');
            GetSede();
        };

        $scope.GetHojaVidaById = function () {
            if ($scope.HojaVidaId) {
                HojaVidaService.GetHojaVidaHojaVidaId($scope.HojaVidaId).then(function (d) {
                    $scope.ficha = d.data[0];
                    $scope.ficha.Accesorios = JSON.parse(d.data[0].Accesorios);
                    $("#ex1_value").val($scope.ficha.Proveedor);
                    $scope.image = $scope.ficha.Foto;
                    $scope.ChangeServicios();
                    $rootScope.HojaVida = angular.copy($scope.ficha);
                });
            }
        };
        $scope.Imprimir = function () {
            $scope.ToPrint = true;
            setTimeout(function () {
                printDiv();
            }, 600);

        };
        $scope.GuardarBorrador = function () {
            SesionService.set($scope.ficha, "Ficha_" + $rootScope.username.NombreUsuario);
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Consultas">
        $scope.ChangeServicios = function () {
            GetServicio();
        };
        function GetServicio() {
            ServicioService.getServicioByUserId($rootScope.username.UserId).then(function (c) {
                console.log(c.data)
                $scope.Servicios = $filter("orderBy")($filter('filter')(c.data, {SedeId: $scope.ficha.SedeId}), "Nombre");
                if (!$scope.HojaVidaId) {
                    $scope.ficha.ServicioId = "--";
                }
            });
        }
        function GetProveedor() {
            ProveedorService.getAllProveedor().then(function (c) {
                $scope.Proveedores = $filter("orderBy")(c.data, "Nombre");
            });
        }
        function GetSede() {
            SedeService.getAllSedeByUserId($rootScope.username.UserId).then(function (c) {
                $scope.Sedes = $filter("orderBy")(c.data, "Nombre");
                if ($scope.Sedes.length == 1) {
                    $scope.ficha.SedeId = $scope.Sedes[0].SedeId;
                    GetServicio();
                }
            });
        }
        function GetFrecuencia() {
            FrecuenciaService.getAllFrecuencia().then(function (c) {
                $scope.Frecuencias = $filter("orderBy")(c.data, "Nombre");
            });
        }
        function GetNHojaVida() {
            HojaVidaService.GetNHojaVida().then(function (d) {
                $scope.NumeroHojaVida = parseInt(d.data[0].HojaVidaId) + 1;
            });
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
        //<editor-fold defaultstate="collapsed" desc="Helpers">
        function printDiv() {
//            html2canvas(document.querySelector("#capture")).then(function (canvas) {
//                document.body.appendChild(canvas);
//            });
            $("#dospaginas").print({
                globalStyles: true,
                mediaPrint: false,
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
//            GetServicio();
            GetNHojaVida();
            GetProveedor();
            GetSede();
            GetFrecuencia();
            GetEmpresa();
            GetEncabezado();
            if ($stateParams.HojaVidaId) {
                $scope.HojaVidaId = $stateParams.HojaVidaId;
                $scope.GetHojaVidaById();
            }
        }
        _init();
    }]);



