app.controller('UpdateRondaVerificacionCtrl', ["$scope", "$rootScope", "$stateParams", "$crypto", "$filter", "SectorService", "EmpresaService", "EncabezadoService",
    "PacienteCMService", "RondaVerificacionService", "TipoMedicamentoService", "MedicamentoService", "VehiculoService",
    function ($scope, $rootScope, $stateParams, $crypto, $filter, SectorService, EmpresaService, EncabezadoService, PacienteCMService,
            RondaVerificacionService, TipoMedicamentoService, MedicamentoService, VehiculoService) {
        //<editor-fold defaultstate="collapsed" desc="Variables">
        $scope.RondaVerificacionId = null;
        $scope.TipoMedicamentoId = null;
        $scope.ToPrint = false;
        $scope.cargado = false;
        $scope.Sectores = [];
        $scope.TipoMedicamentos = [];
        $scope.Medicamentos = [];
        $scope.Sector = "";
        $scope.token = $stateParams.token;
        $scope.TipoRonda = $stateParams.TipoRonda;
        var decrypt = $crypto.decrypt($stateParams.token, 'Franklin Ospino');
        $scope.Fecha = toConsultar(decrypt);
        $scope.RV = {
            Sector: "",
            TipoMedicamentoId: $stateParams.TipoMedicamentoId,
            Fecha: $scope.Fecha,
            Pacientes: [],
            CreatedBy: $rootScope.username.NombreCompleto
        };
        $scope.RV_original = {
            Sector: "",
            TipoMedicamentoId: $stateParams.TipoMedicamentoId,
            Fecha: $scope.Fecha,
            Pacientes: [],
            CreatedBy: $rootScope.username.NombreCompleto
        };
        $scope.PacienteNuevo = {
            PNombre: "",
            SNombre: "",
            PApellido: "",
            SApellido: "",
            IdAfiliado: "",
            NoAdmision: "",
            EstadoPaciente: "Nuevo",
            ListadoMedicamentos: []
        };
        $scope.op = null;
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Botones">
        $scope.AddToPacientes = () => {
            var o = angular.copy($scope.PacienteNuevo);
            $scope.RV.Pacientes.push(o);
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
                    console.log(d.data);
                    if (typeof d.data != "string") {
                        swal("Enhorabuena", "Se ha actualizado la ronda con exito", "success");
                        $scope.GetRondaVerificacionById($scope.TipoMedicamentoId);
//                        $scope.Reset();
                    } else {
                        swal("Error", d.data, "error");
                    }
                });
            }
        };
        $scope.GetRondaVerificacionById = function (TipoMedicamentoId) {
            if ($scope.RondaVerificacionId) {
                RondaVerificacionService.getRondaVerificacionById($scope.RondaVerificacionId, TipoMedicamentoId, $scope.Sector, $scope.Fecha, $scope.TipoRonda).then(function (d) {
                    console.log(d.data);
                    $scope.RV = d.data[0];
                    if (d.data[0].Pacientes) {
                        $scope.PacienteNuevo.ListadoMedicamentos = d.data[0].Pacientes[0].ListadoMedicamentos;
                    }
                    $scope.RV.TipoMedicamentoId = TipoMedicamentoId;
                });
            }
        };
        $scope.ChangeSector = function () {
//            $scope.RV.Pacientes = angular.copy($filter('filter')($scope.RV_original.Pacientes, {Sector: $scope.Sector }));
            $scope.GetRondaVerificacionById($scope.TipoMedicamentoId);
            console.log($scope.Sector);
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
            console.log(i);
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
        $scope.Reset = function () {
            $scope.RV = {
                Sector: "",
                Fecha: $scope.Fecha,
                Pacientes: [],
                CreatedBy: $rootScope.username.NombreCompleto
            };
            if ($stateParams.RondaVerificacionId) {
                $scope.RondaVerificacionId = $stateParams.RondaVerificacionId;
                $scope.GetRondaVerificacionById($scope.TipoMedicamentoId);
            } else {
//                $scope.ChangeSector();
            }
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
            $scope.Sectores = [];
            SectorService.getSectores().then(function (c) {
                $scope.Sectores = c.data;
                $scope.Sector = c.data[0].Sector;
            });
        }

        function GetVehiculos() {
            $scope.Vehiculos = [];
            VehiculoService.getVehiculos().then(function (v) {
                $scope.Vehiculos = v.data;
            });
        }

        function GetTipoMedicamentos() {
            $scope.TipoMedicamentos = [];
            TipoMedicamentoService.getTiposMedicamentos().then(function (c) {
                $scope.TipoMedicamentos = c.data;
                $scope.op = Number($stateParams.TipoMedicamentoId);
            });
        }

        function GetMedicamentos() {
            $scope.Medicamentos = [];
            if ($scope.TipoRonda == "Loteado") {
                MedicamentoService.getMedicamentosLoteados($scope.RV.TipoMedicamentoId).then(function (c) {
                    $scope.Medicamentos = c.data;
                });
            } else {
                MedicamentoService.getMedicamentos($scope.RV.TipoMedicamentoId).then(function (c) {
                    $scope.Medicamentos = c.data;
                });
            }

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
                TipoMedicamentoId: $scope.RV.TipoMedicamentoId
            };
            PacienteCMService.getPacientesBySector(obj).then(function (d) {
                console.log(d.data);
                $scope.RV.Pacientes = d.data;
            });
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
            $("#RondaActualizar").print({
                globalStyles: true,
                mediaPrint: false,
                stylesheet: null,
                noPrintSelector: ".no-print",
                iframe: true,
                append: null,
                prepend: null,
                manuallyCopyFormValues: true,
                deferred: $.Deferred(),
                timeout: 1000,
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
            GetSectores();
            GetVehiculos();
            if ($stateParams.RondaVerificacionId) {
                $scope.RV.TipoMedicamentoId = $stateParams.TipoMedicamentoId;
                $scope.TipoMedicamentoId = $stateParams.TipoMedicamentoId;
                GetMedicamentos();
                $scope.RondaVerificacionId = $stateParams.RondaVerificacionId;
                $scope.GetRondaVerificacionById($scope.RV.TipoMedicamentoId);
            } else {
                GetMedicamentos();
            }
        }
        _init();
    }]);


