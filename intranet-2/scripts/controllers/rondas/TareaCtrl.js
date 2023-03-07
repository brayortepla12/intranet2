app.controller('TareaCtrl', ["$scope", "$rootScope", "$stateParams", "RondaService", "EmpresaService", "$crypto", "UsuarioService", "SesionService", "$state",
    "SedeService", "ServicioService", "HojaVidaService", "$filter",
    function ($scope, $rootScope, $stateParams, RondaService, EmpresaService, $crypto, UsuarioService, SesionService, $state, SedeService,
            ServicioService, HojaVidaService, $filter) {
        //<editor-fold defaultstate="collapsed" desc="Prototipos fechas">
        Date.prototype.monthNames = [
            "ENERO", "FEBRERO", "MARZO",
            "ABRIL", "MAYO", "JUNIO",
            "JULIO", "AGOSTO", "SEPTIEMBRE",
            "OCTUBRE", "NOVIEMBRE", "DICIEMBRE"
        ];

        Date.prototype.getMonthName = function () {
            return this.monthNames[this.getMonth()];
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Variables">
        $scope.Sedes = [];
        $scope.Servicios = [];
        $scope.Equipos = [];
        $scope.Equipos_list = [];
        $scope.Servicios_list = [];
        $scope.Rondas = [];
        $scope.Rondas2 = [];
        $scope.Empresa = null;
        $scope.FechaHoy = "";
        $scope.Usuarios = [];
        $scope.options = [];
        $scope.labels = {
            "itemsSelected": "Seleccionados",
            "selectAll": "Seleccionar Todo",
            "unselectAll": "Deseleccionar Todo",
            "search": "Buscar",
            "select": "Seleccionar"
        };
        var tzoffset = (new Date()).getTimezoneOffset() * 60000; //offset in milliseconds
        $scope.item = {
            SedeId: "",
            ServicioId: "",
            NombreJefeArea: "",
            Observaciones: "",
            Fecha: (new Date(Date.now() - tzoffset)).toISOString().replace("Z", ''),
            Realizo: $rootScope.username.NombreCompleto,
            Equipos: []
        };
        $scope.Equipo = {
            Tipo: "",
            Equipo: "",
            Descripcion: ""
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Botones">
        $scope.DeleteActividadRonda = function (RondaId) {
            swal({
                title: "¿Deseas ELIMINAR esta tarea?",
                text: "Nota: Este paso no se puede retroceder!",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "ELIMINAR!",
                cancelButtonText: "Cancelar!",
                closeOnConfirm: true,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {
                    RondaService.DeleteRondaByRondaId({RondaId: RondaId}).then(function (d) {
                        _init();
                    });
                }
            });
        };
        $scope.GuardarTarea = function () {
            if ($scope.item.SedeId === "" && $scope.item.ServicioId === "" && $scope.item.SedeId === "" && $scope.item.Fecha === "" && $scope.item.NombreJefeArea === "") {
                swal("Error", "Debe llenar el formulario.", "error");
            } else if ($scope.item.Equipos.length === 0) {
                swal("Error", "Debe añadir como minimo un detalle.", "error");
            } else {
                var obj = {
                    ListadoItems: JSON.stringify([$scope.item])
                };
                RondaService.postRondas(obj).then(function (r) {
                    if (typeof r.data === "string") {
                        swal("Error", r.data, "error");
                    } else {
                        $scope.item = {
                            SedeId: "",
                            ServicioId: "",
                            NombreJefeArea: "",
                            Observaciones: "",
                            Fecha: (new Date(Date.now() - tzoffset)).toISOString().replace("Z", ''),
                            Realizo: $rootScope.username.NombreCompleto,
                            Equipos: []
                        };
                        swal("Enhorabuena!", 'Se han guardado los datos satisfactoriamente', "success");
                        _init();
                    }
                    
                }, function (e) {
                    swal("Error", "No se encontro el servidor, verifique la conexión.", "error");
                });
            }

        };
        $scope.RemoveEquipo = function (i) {
            $scope.item.Equipos.splice(i, 1);
        };
        $scope.Imprimir = function () {
            $scope.ToPrint = true;
            setTimeout(function () {
                printDiv();
            }, 600);
        };
        $scope.ViewFormTareas = function () {
            $('#CrearTarea').modal('show');
            GetSedes();
            GetServicio();
            GetHojasDeVida();
        };
        $scope.AsignarTareas = function () {
            var obj = {
                RondaTarea: JSON.stringify([$scope.Rondas]),
                CreatedBy: $rootScope.username.NombreCompleto
            };
            RondaService.postRondaUsuario(obj).then(function (d) {
                console.log(d.data)
                if (typeof d.data != "string") {
                    swal("Enhorabuena!", 'Se han guardado los datos satisfactoriamente', "success");
                    GetRondaById();
                } else {
                    swal("Error", d.data, "error");
                }
            });
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Consultas">
        function GetUsuario() {
            UsuarioService.GetALLusuarios(SesionService.get("UserData_Polivalente").key, SesionService.get("UserData_Polivalente").Email).then(function (d) {
                if (typeof d.data === "string") {
                    swal("Error", d.data, "error");
                    SesionService.remove("UserData_Polivalente");
                    SesionService.remove("MenuAPP_Polivalente");
                    $state.go("login");
                } else {
                    $scope.Usuarios = d.data;
                    GetEmpresa();
                    for (var i in d.data) {
                        $scope.options.push(d.data[i].NombreCompleto)
                    }
                    GetRondaById();
                }
            });
        }
        function GetRondaById() {
            $scope.Rondas = [];
            var decrypt = $crypto.decrypt($stateParams.token, 'Franklin Ospino');
            $scope.FechaHoy = toDate(decrypt);
            RondaService.GetAllByFecha(toConsultar(decrypt), $rootScope.username.UserId).then(function (d) {
                console.log(d.data);
                $scope.Rondas = angular.copy(d.data);
                $scope.Rondas2 = angular.copy(d.data);
            });
        }
        function GetEmpresa() {
            EmpresaService.getEmpresa().then(function (e) {
                $scope.Empresa = e.data;
            });
        }
        function GetSedes() {
            SedeService.getAllSede().then(function (c) {
                $scope.Sedes = $filter("orderBy")(c.data, "-SedeId");
            });
        }
        function GetServicio() {
            ServicioService.getAllServicio().then(function (c) {
                $scope.Servicios = $filter("orderBy")(c.data, "-ServicioId");
            });
        }
        function GetHojasDeVida() {
            HojaVidaService.GetAllHojas().then(function (d) {
                console.log(d.data)
                if (typeof d.data != "string") {
                    $scope.Equipos = d.data;
                }
            });
        }
        //</editor-fold>
        $scope.AddEquipo = function () {
            if ($scope.Equipo.Tipo != "" && $scope.Equipo.Descripcion != "") {
                $scope.item.Equipos.push(angular.copy($scope.Equipo));
                $scope.Equipo = {
                    Tipo: "",
                    Equipo: "",
                    Descripcion: ""
                };
            } else {
                swal("Error", "Debes llenar completamente los campos para poder añadir detalles", "error");
            }

        };
        $scope.ChangeSede = function () {
            $scope.Servicios_list = $filter("orderBy")($filter("filter")($scope.Servicios, {
                SedeId: $scope.item.SedeId
            }), "Nombre");
        };
        $scope.ChangeServicio = function () {
            $scope.Equipos_list = $filter("orderBy")($filter('filter')($scope.Equipos, {ServicioId: $scope.item.ServicioId}), "Ubicacion");
        };

        function toDate(dateStr) {
            var parts = dateStr.split("-");
            return new Date(parts[0], parts[1] - 1, parts[2]);
        }

        function toConsultar(dateStr) {
            var parts = dateStr.split("-");
            return parts[0] + "-" + (parts[1] < 10 ? "0" + parts[1] : parts[1]) + "-" + (parts[2] < 10 ? "0" + parts[2] : parts[2]);
        }


        function printDiv() {
            $("#Planilla").print({
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

        function _init() {
            GetUsuario();
        }
        _init();
    }]);




