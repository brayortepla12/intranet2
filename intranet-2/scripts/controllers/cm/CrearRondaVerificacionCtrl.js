app.controller('CrearRondaVerificacionCtrl', ["$scope", "$rootScope", "$stateParams", "$crypto", "$state", "SectorService", "EmpresaService", "EncabezadoService",
    "PacienteCMService", "RondaVerificacionService", "TipoMedicamentoService", "MedicamentoService", "SesionService", "VehiculoService",
    function ($scope, $rootScope, $stateParams, $crypto, $state, SectorService, EmpresaService, EncabezadoService, PacienteCMService,
            RondaVerificacionService, TipoMedicamentoService, MedicamentoService, SesionService, VehiculoService) {
        //<editor-fold defaultstate="collapsed" desc="Variables">
        let host = window.location.hostname;
        const vm = $scope;
        vm.Guardando = false;
        vm.PacienteNuevo = {
            PNombre: "",
            SNombre: "",
            PApellido: "",
            SApellido: "",
            IdAfiliado: "",
            NoAdmision: "",
            EstadoPaciente: "Activo"
        };
        vm.SMedicamento = 'TODOS';
        vm.PacienteSeleccionado = {};
        vm.RondaVerificacionId = null;
        vm.ToPrint = false;
        vm.cargado = false;
        vm.Sectores = [];
        vm.TipoMedicamentos = [];
        vm.Medicamentos = [];
        vm.Vehiculos = [];
        vm.token = $stateParams.token;
        var decrypt = $crypto.decrypt($stateParams.token, 'Franklin Ospino');
        vm.Fecha = toConsultar(decrypt);
        vm.RV = {
            Sector: "",
            TipoMedicamentoId: 1,
            Fecha: vm.Fecha,
            Pacientes: [],
            CreatedBy: $rootScope.username.NombreCompleto,
            TipoRonda: 'Unidosis'
        };
        vm.op = 1;
        vm.Local = [];
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Botones">
        vm.DeleteLocal = (TipoMedicamentoId) => {
            swal({
                title: "¿Deseas ELIMINAR este formulario?",
                text: "Equipo a eliminar ",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Eliminar!",
                cancelButtonText: "Cancelar!",
                closeOnConfirm: false,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {
                    swal("Enhorabuena", "Se ha eliminado el formulario con exito", "success");
                    SesionService.remove("TipoMedicamentoId" + vm.Fecha + host+ TipoMedicamentoId);
                    vm.Local = [];
                    for (var i in vm.TipoMedicamentos) {
                        var data = SesionService.get("TipoMedicamentoId" + vm.Fecha + host+ vm.TipoMedicamentos[i].TipoMedicamentoId);
                        if (data) {
                            data.TipoRonda = "Unidosis";
                            vm.Local.push(data);
                            vm.Reset();
                            _init();
                        }
                    }
                    vm.$apply();
                }
            });
        };
        vm.AddToPacientes = () => {
            var TipoMedicamentos_data = SesionService.get("TipoMedicamentoId" + vm.Fecha + host+ vm.RV.TipoMedicamentoId);
            var o = angular.copy(vm.PacienteNuevo);
            vm.RV.Pacientes.push(o);
            vm.PacienteNuevo = {
                PNombre: "",
                SNombre: "",
                PApellido: "",
                SApellido: "",
                IdAfiliado: "",
                NoAdmision: "",
                EstadoPaciente: "Activo"
            };
            var data = angular.copy(vm.RV);
            if (TipoMedicamentos_data) {
                if (TipoMedicamentos_data.TipoMedicamentoId === vm.RV.TipoMedicamentoId) {
                    if (TipoMedicamentos_data.RVs.length > 0) {
                        for (var j in TipoMedicamentos_data.RVs) {
                            if (TipoMedicamentos_data.RVs[j].Sector === vm.RV.Sector) {
                                TipoMedicamentos_data.RVs[j] = data;
                            }
                        }
                    }
                    if (!IsInArray(TipoMedicamentos_data, data.Sector)) {
                        TipoMedicamentos_data.RVs.push(data);
                    }
                    SesionService.set(TipoMedicamentos_data, "TipoMedicamentoId" + vm.Fecha + host+ vm.RV.TipoMedicamentoId);
                }

            } else {
                var Obj = {
                    TipoMedicamentoId: angular.copy(vm.RV.TipoMedicamentoId),
                    RVs: [data]
                };
                SesionService.set(Obj, "TipoMedicamentoId" + vm.Fecha + host+ vm.RV.TipoMedicamentoId);
            }
        };
        vm.Guardar = function () {
            var obj = {
                RondaVerificacion: JSON.stringify(vm.Local)
            };
            vm.Guardando = true;
            RondaVerificacionService.postRondaVerificacion(obj).then(function (d) {
                vm.Guardando = false;
                if (typeof d.data != "string") {
                    console.log(d.data)
                    swal("Enhorabuena", "Se ha guardado la ronda con exito", "success");
                    vm.Reset();
                    vm.Local = d.data;
                    for (var i in vm.Local) {
                        SesionService.set(vm.Local[i], "TipoMedicamentoId" + vm.Fecha + host+ vm.Local[i].TipoMedicamentoId);
                    }
                } else {
                    swal("Error", d.data, "error");
                }
            });
        };
        vm.Actualizar = function () {
            if (!vm.Datos.$valid) {
                angular.element("[name='" + vm.Datos.$name + "']").find('.ng-invalid:visible:first').focus();
            } else {
                vm.RV.ModifiedBy = $rootScope.username.NombreCompleto;
                var obj = {
                    RondaVerificacion: JSON.stringify(vm.RV)
                };
                RondaVerificacionService.putRondaVerificacion(obj).then(function (d) {
                    if (typeof d.data != "string") {
                        swal("Enhorabuena", "Se ha actualizado la ronda con exito", "success");
                    } else {
                        swal("Error", d.data, "error");
                    }
                });
            }
        };
        vm.GetRondaVerificacionById = function (TipoMedicamentoId) {
            if (vm.RondaVerificacionId) {
                RondaVerificacionService.getRondaVerificacionById(vm.RondaVerificacionId, TipoMedicamentoId).then(function (d) {
                    vm.RV = d.data[0];
                    vm.RV.TipoMedicamentoId = TipoMedicamentoId;
                });
            }
        };
        vm.ChangeSector = function () {
            GetPacientes();
        };
        vm.ChangeTipoMedicamento = function (TipoMedicamentoId) {
            vm.RV.TipoMedicamentoId = TipoMedicamentoId;
            GetMedicamentos();
            if (!$stateParams.RondaVerificacionId) {
                vm.ChangeSector();
            } else {
                vm.RondaVerificacionId = $stateParams.RondaVerificacionId;
                vm.GetRondaVerificacionById(TipoMedicamentoId);
            }
        };
        vm.Imprimir = function () {
            vm.ToPrint = true;
            setTimeout(function () {
                printDiv();
            }, 600);
        };
        vm.ChangeEstadoPaciente = function (paciente, i) {
            if (paciente.EstadoPaciente != "Suspender") {
                swal("Enhorabuena", "Paciente suspendido", "warning");
                vm.RV.Pacientes[i].EstadoPaciente = "Suspender";
                paciente.EstadoPaciente = "Suspender";
            } else if (paciente.EstadoPaciente == "Suspender") {
                swal("Enhorabuena", "Paciente Habilidado", "warning");
                paciente.EstadoPaciente = "Continuar";
                vm.RV.Pacientes[i].EstadoPaciente = "Continuar";
            }
        };
        vm.Reset = function () {
            vm.RV = {
                Sector: "",
                Fecha: vm.Fecha,
                Pacientes: [],
                CreatedBy: $rootScope.username.NombreCompleto,
                TipoRonda: 'Unidosis'
            };
            if ($stateParams.RondaVerificacionId) {
                vm.RondaVerificacionId = $stateParams.RondaVerificacionId;
                vm.GetRondaVerificacionById();
            } else {
//                vm.ChangeSector();
            }
        };
        vm.SaveLocalBTn = function () {
            swal("Enhorabuena", "Guardado localmente", "success");
            vm.SaveLocal();
        };
        vm.SaveLocal = function () {
            var TipoMedicamentos_data = SesionService.get("TipoMedicamentoId" + vm.Fecha + host+ vm.RV.TipoMedicamentoId);
            var data = angular.copy(vm.RV);
            if (TipoMedicamentos_data) {
                if (TipoMedicamentos_data.TipoMedicamentoId === vm.RV.TipoMedicamentoId) {
                    if (TipoMedicamentos_data.RVs.length > 0) {
                        for (var j in TipoMedicamentos_data.RVs) {
                            if (TipoMedicamentos_data.RVs[j].Sector === vm.RV.Sector) {
                                TipoMedicamentos_data.RVs[j] = data;
                            }
                        }
                    }
                    if (!IsInArray(TipoMedicamentos_data, data.Sector)) {
                        TipoMedicamentos_data.RVs.push(data);
                    }
                    SesionService.set(TipoMedicamentos_data, "TipoMedicamentoId" + vm.Fecha + host+ vm.RV.TipoMedicamentoId);
                }

            } else {
                var Obj = {
                    TipoMedicamentoId: angular.copy(vm.RV.TipoMedicamentoId),
                    RVs: [data]
                };
                SesionService.set(Obj, "TipoMedicamentoId" + vm.Fecha + host+ vm.RV.TipoMedicamentoId);
            }
        };
        vm.ModalGuardar = function () {
            vm.Local = [];
            for (var i in vm.TipoMedicamentos) {
                var data = SesionService.get("TipoMedicamentoId" + vm.Fecha + host+ vm.TipoMedicamentos[i].TipoMedicamentoId);
                if (data) {
                    data.TipoRonda = "Unidosis";
                    vm.Local.push(data);
                }
            }
            $('#LocalModal').modal('show');
        };
//        ui-sref="cm.UpdateRondaVerificacion({token: token, TipoMedicamento: tm.TipoMedicamentoId})"
        vm.GotoUpdate = function (TipoMedicamentoId, RondaVerificacionId) {
            $('#LocalModal').modal('hide');
            var url = $state.href("cm.UpdateRondaVerificacion", {token: vm.token, TipoMedicamentoId: TipoMedicamentoId, RondaVerificacionId: RondaVerificacionId});
            window.open(url);
        };

        function IsInArray(TipoMedicamentos_data, Sector) {
            for (var j in TipoMedicamentos_data.RVs) {
                if (TipoMedicamentos_data.RVs[j].Sector === Sector) {
                    return true;
                }
            }
            return false;
        }
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="consultas">
        function GetSectores() {
            vm.Sectores = [];
            SectorService.getSectores().then(function (c) {
                vm.Sectores = c.data;
            });
        }

        function GetTipoMedicamentos() {
            vm.TipoMedicamentos = [];
            TipoMedicamentoService.getTiposMedicamentos().then(function (c) {
                vm.TipoMedicamentos = c.data;
                for (var i in c.data) {
                    var Obj = {
                        TipoMedicamentoId: c.data[i].TipoMedicamentoId,
                        RVs: []
                    };
                }
            });
        }

        function GetVehiculos() {
            vm.Vehiculos = [];
            VehiculoService.getVehiculos().then(function (v) {
                console.log(v.data);
                vm.Vehiculos = v.data;
            });
        }

        function GetMedicamentos() {
            vm.Medicamentos = [];
            MedicamentoService.getMedicamentos(vm.RV.TipoMedicamentoId).then(function (c) {
                vm.Medicamentos = c.data;
            });
        }

        function GetEmpresa() {
            EmpresaService.getEmpresa().then(function (e) {
                vm.Empresa = e.data;
            });
        }
        function GetEncabezado() {
            EncabezadoService.getEncabezado().then(function (e) {
                vm.Encabezado = e.data;
            });
        }
        function GetPacientes() {
            vm.RV.Pacientes = [];
            var obj = {
                Sector: vm.RV.Sector,
                TipoMedicamentoId: vm.RV.TipoMedicamentoId,
                FechaPS: vm.Fecha,
                TipoRonda: "Unidosis"
            };
            var DataLocal = GetLocalBySector();
            if (!DataLocal) {
                PacienteCMService.getPacientesBySector(obj).then(function (d) {
                    console.log(d.data);
                    vm.RV.Pacientes = d.data;
                    vm.PacienteNuevo.ListadoMedicamentos = d.data[0].ListadoMedicamentos;
                });
            } else {
                vm.RV = angular.copy(DataLocal);
                vm.PacienteNuevo.ListadoMedicamentos = DataLocal.Pacientes[0].ListadoMedicamentos;
            }
        }

        function GetLocalBySector() {
            var TipoMedicamentos_data = SesionService.get("TipoMedicamentoId" + vm.Fecha + host+ vm.RV.TipoMedicamentoId);
            if (TipoMedicamentos_data) {
                if (TipoMedicamentos_data.TipoMedicamentoId === vm.RV.TipoMedicamentoId) {
                    if (TipoMedicamentos_data.RVs.length > 0) {
                        for (var j in TipoMedicamentos_data.RVs) {
                            if (TipoMedicamentos_data.RVs[j].Sector === vm.RV.Sector) {
                                return angular.copy(TipoMedicamentos_data.RVs[j]);
                            }
                        }
                    }
                }
            } else {
                return false;
            }
        }
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Helpers">
        function toDate(dateStr) {
            var parts = dateStr.split("-");
            return new Date(parts[0], parts[1] - 1, parts[2]);
        }

        function toConsultar(dateStr) {
            var parts = dateStr.split("-");
            return parts[0] + "-" + parts[1] + "-" + parts[2];
        }
        function printDiv() {
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
                timeout: 750,
                title: null,
                doctype: '<!doctype html>'
            });

            setTimeout(function () {
                vm.ToPrint = false;
                vm.$apply();
            }, 1000);
        }
        //</editor-fold>
        function _init() {
            GetEmpresa();
            GetEncabezado();
            GetTipoMedicamentos();
            GetVehiculos();
            if ($stateParams.RondaVerificacionId) {
                vm.RV.TipoMedicamentoId = 1;
                GetMedicamentos();
                vm.RondaVerificacionId = $stateParams.RondaVerificacionId;
                vm.GetRondaVerificacionById(vm.RV.TipoMedicamentoId);
            } else {
                GetMedicamentos();
                GetSectores();
            }
        }
        _init();
    }]);


