app.controller('CrearLoteadoCtrl', ["$scope", "$rootScope", "$stateParams", "$crypto", "$state", "SectorService", "EmpresaService", "EncabezadoService",
    "PacienteCMService", "RondaVerificacionService", "TipoMedicamentoService", "MedicamentoService", "SesionService", "VehiculoService",
    function ($scope, $rootScope, $stateParams, $crypto, $state, SectorService, EmpresaService, EncabezadoService, PacienteCMService,
            RondaVerificacionService, TipoMedicamentoService, MedicamentoService, SesionService, VehiculoService) {
        //<editor-fold defaultstate="collapsed" desc="Variables">
        $scope.Guardando = false;
        $scope.PacienteNuevo = {
            PNombre: "",
            SNombre: "",
            PApellido: "",
            SApellido: "",
            IdAfiliado: "",
            NoAdmision: "",
            EstadoPaciente: "Activo"
        };
        $scope.PacienteSeleccionado = {};
        $scope.RondaVerificacionId = null;
        $scope.ToPrint = false;
        $scope.cargado = false;
        $scope.Sectores = [];
        $scope.TipoMedicamentos = [];
        $scope.Medicamentos = [];
        $scope.Vehiculos = [];
        $scope.token = $stateParams.token;
        var decrypt = $crypto.decrypt($stateParams.token, 'Franklin Ospino');
        $scope.Fecha = toConsultar(decrypt);
        $scope.RV = {
            Sector: "N/A",
            TipoMedicamentoId: 1,
            Fecha: $scope.Fecha,
            Pacientes: [],
            CreatedBy: $rootScope.username.NombreCompleto,
            TipoRonda: 'Loteado'
        };
        $scope.op = 1;
        $scope.Local = [];
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Botones">
        $scope.Guardar = function () {
            $scope.Local = [];
            for (var i in $scope.TipoMedicamentos) {
                var data = SesionService.get("TipoMedicamentoId_Loteado" + $scope.TipoMedicamentos[i].TipoMedicamentoId);
                if (data) {
                    data.TipoRonda = "Loteado";
                    $scope.Local.push(data);
                }
            }
            var obj = {
                RondaVerificacion: JSON.stringify($scope.Local)
            };
            console.log($scope.Local);
            $scope.Guardando = true;
            RondaVerificacionService.postRondaVerificacion(obj).then(function (d) {
                $scope.Guardando = false;
                if (typeof d.data != "string") {
                    swal("Enhorabuena", "Se ha guardado la ronda con exito", "success");
                    $scope.Reset();
                    $scope.Local = d.data;
                    for (var i in $scope.Local) {
                        SesionService.remove("TipoMedicamentoId_Loteado" + $scope.Local[i].TipoMedicamentoId);
                    }
                } else {
                    swal("Error", d.data, "error");
                }
            });
        };
        $scope.Actualizar = function () {
            if (!$scope.Datos.$valid) {
                angular.element("[name='" + $scope.Datos.$name + "']").find('.ng-invalid:visible:first').focus();
            } else {
                $scope.RV.ModifiedBy = $rootScope.username.NombreCompleto;
                var obj = {
                    RondaVerificacion: JSON.stringify($scope.RV)
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
        $scope.GetRondaVerificacionById = function (TipoMedicamentoId) {
            if ($scope.RondaVerificacionId) {
                RondaVerificacionService.getRondaVerificacionById($scope.RondaVerificacionId, TipoMedicamentoId).then(function (d) {
                    $scope.RV = d.data[0];
                    $scope.RV.TipoMedicamentoId = TipoMedicamentoId;
                });
            }
        };
        $scope.ChangeSector = function () {
            GetPacientes();
        };
        $scope.ChangeTipoMedicamento = function (TipoMedicamentoId) {
            $scope.RV.TipoMedicamentoId = TipoMedicamentoId;
            GetMedicamentos();
            if (!$stateParams.RondaVerificacionId) {
                $scope.ChangeSector();
            } else {
                $scope.RondaVerificacionId = $stateParams.RondaVerificacionId;
                $scope.GetRondaVerificacionById(TipoMedicamentoId);
            }
        };
        $scope.Imprimir = function () {
            $scope.ToPrint = true;
            setTimeout(function () {
                printDiv();
            }, 600);
        };
        $scope.ChangeEstadoPaciente = function (paciente, i) {
            if (paciente.EstadoPaciente != "Suspender") {
                swal("Enhorabuena", "Paciente suspendido", "warning");
                $scope.RV.Pacientes[i].EstadoPaciente = "Suspender";
                paciente.EstadoPaciente = "Suspender";
            } else if (paciente.EstadoPaciente == "Suspender") {
                swal("Enhorabuena", "Paciente Habilidado", "warning");
                paciente.EstadoPaciente = "Continuar";
                $scope.RV.Pacientes[i].EstadoPaciente = "Continuar";
            }
        };
        $scope.SaveLocal = function () {
            var TipoMedicamentos_data = SesionService.get("TipoMedicamentoId_Loteado" + $scope.RV.TipoMedicamentoId);
            var data = angular.copy($scope.RV);
            if (TipoMedicamentos_data) {
                if (TipoMedicamentos_data.TipoMedicamentoId === $scope.RV.TipoMedicamentoId) {
                    if (TipoMedicamentos_data.RVs.length > 0) {
                        for (var j in TipoMedicamentos_data.RVs) {
                            if (TipoMedicamentos_data.RVs[j].Sector === $scope.RV.Sector) {
                                TipoMedicamentos_data.RVs[j] = data;
                            }
                        }
                    }
                    if (!IsInArray(TipoMedicamentos_data, data.Sector)) {
                        TipoMedicamentos_data.RVs.push(data);
                    }
                    SesionService.set(TipoMedicamentos_data, "TipoMedicamentoId_Loteado" + $scope.RV.TipoMedicamentoId);
                }

            } else {
                var Obj = {
                    TipoMedicamentoId: angular.copy($scope.RV.TipoMedicamentoId),
                    RVs: [data]
                };
                SesionService.set(Obj, "TipoMedicamentoId_Loteado" + $scope.RV.TipoMedicamentoId);
            }
        };
        $scope.Reset = function () {
            $scope.RV = {
                Sector: "",
                Fecha: $scope.Fecha,
                Pacientes: [],
                CreatedBy: $rootScope.username.NombreCompleto,
                TipoRonda: 'Loteado'
            };
            if ($stateParams.RondaVerificacionId) {
                $scope.RondaVerificacionId = $stateParams.RondaVerificacionId;
                $scope.GetRondaVerificacionById();
            } else {
//                $scope.ChangeSector();
            }
        };
        $scope.ModalGuardar = function () {
            $scope.Local = [];
            for (var i in $scope.TipoMedicamentos) {
                var data = SesionService.get("TipoMedicamentoId_Loteado" + $scope.TipoMedicamentos[i].TipoMedicamentoId);
                if (data) {
                    $scope.Local.push(data);
                }
            }
            $('#LocalModal').modal('show');
        };
        $scope.SaveLocalBTn = function(){
            swal("Enhorabuena", "Guardado localmente", "success");
            $scope.SaveLocal();
        };
//        ui-sref="cm.UpdateRondaVerificacion({token: token, TipoMedicamento: tm.TipoMedicamentoId})"
        $scope.GotoUpdate = function (TipoMedicamentoId, RondaVerificacionId) {
            $('#LocalModal').modal('hide');
            var url = $state.href("cm.UpdateRondaVerificacion", {token: $scope.token, TipoMedicamentoId: TipoMedicamentoId, RondaVerificacionId: RondaVerificacionId});
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
        function GetLocalBySector() {
            var TipoMedicamentos_data = SesionService.get("TipoMedicamentoId_Loteado" + $scope.RV.TipoMedicamentoId);
            if (TipoMedicamentos_data) {
                if (TipoMedicamentos_data.TipoMedicamentoId === $scope.RV.TipoMedicamentoId) {
                    if (TipoMedicamentos_data.RVs.length > 0) {
                        for (var j in TipoMedicamentos_data.RVs) {
                            if (TipoMedicamentos_data.RVs[j].Sector === $scope.RV.Sector) {
                                return angular.copy(TipoMedicamentos_data.RVs[j]);
                            }
                        }
                    }
                }
            } else {
                return false;
            }
        }

        function GetTipoMedicamentos() {
            $scope.TipoMedicamentos = [];
            TipoMedicamentoService.getTiposMedicamentos().then(function (c) {
                $scope.TipoMedicamentos = c.data;
                for (var i in c.data) {
                    var Obj = {
                        TipoMedicamentoId: c.data[i].TipoMedicamentoId,
                        RVs: []
                    };
                }
            });
        }

        function GetVehiculos() {
            $scope.Vehiculos = [];
            VehiculoService.getVehiculos().then(function (v) {
                console.log(v.data);
                $scope.Vehiculos = v.data;
            }).then(() => {
                GetMedicamentos();
            }).then(() => {
                GetPacientes();
            });
        }

        function GetMedicamentos() {
            $scope.Medicamentos = [];
            MedicamentoService.getMedicamentosLoteados($scope.RV.TipoMedicamentoId).then(function (c) {
                $scope.Medicamentos = c.data;
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
        function GetPacientes() {
            $scope.RV.Pacientes = [];
            var obj = {
                Sector: $scope.RV.Sector,
                TipoMedicamentoId: $scope.RV.TipoMedicamentoId,
                FechaPS: $scope.Fecha,
                TipoRonda: "Loteado"
            };
            var DataLocal = GetLocalBySector();
            if (!DataLocal) {
                PacienteCMService.getPacientesBySector(obj).then(function (d) {
                    $scope.RV.Pacientes = d.data;
                    $scope.PacienteSeleccionado = $scope.RV.Pacientes[0];
                    console.log(d.data);
                });
            } else {
                $scope.RV = angular.copy(DataLocal);
                $scope.PacienteSeleccionado = $scope.RV.Pacientes[0];
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
                $scope.ToPrint = false;
                $scope.$apply();
            }, 1000);
        }
        //</editor-fold>
        function _init() {
            GetEmpresa();
            GetEncabezado();
            GetTipoMedicamentos();
            GetVehiculos();
//            if ($stateParams.RondaVerificacionId) {
//                $scope.RV.TipoMedicamentoId = 1;
//                GetMedicamentos();
//                $scope.RondaVerificacionId = $stateParams.RondaVerificacionId;
//                $scope.GetRondaVerificacionById($scope.RV.TipoMedicamentoId);
//            } else {
//                GetMedicamentos();
//                GetSectores();
//            }
        }
        _init();
    }]);



