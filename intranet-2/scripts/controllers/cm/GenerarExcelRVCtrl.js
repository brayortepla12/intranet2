app.controller('GenerarExcelRVCtrl', ["$scope", "$rootScope", "$crypto", "$state", "$stateParams",
    "RondaVerificacionService", "MedicamentoService", "VehiculoService", "UsuarioService", "SesionService",
    function ($scope, $rootScope, $crypto, $state, $stateParams, RondaVerificacionService, MedicamentoService, VehiculoService, UsuarioService, SesionService) {
        //<editor-fold defaultstate="collapsed" desc="Variables">
        $scope.RondaVerificacionId = $stateParams.RondaVerificacionId;
        $scope.TipoMedicamentoId = $stateParams.TipoMedicamentoId;
        $scope.UsuarioId = $rootScope.username.UserId;
        $scope.IsDireccionTecnica = $rootScope.username.IsDireccionTecnica;
        $scope.IsACalidad = $rootScope.username.IsACalidad;
        $scope.IsAFarmacia = $rootScope.username.IsAFarmacia;
        $scope.IsQFarmaceutico = $rootScope.username.IsQFarmaceutico;
        $scope.MedicamentoId = "";
        $scope.DetallesRonda = [];
        $scope.Productos = [];
        $scope.Dispositivo = {};
        $scope.Dispositivos = [];
        $scope.Etiquetas = [];
        $scope.Historico = [];
        $scope.Elabora = $rootScope.username.NombreCompleto;
        $scope.TipoRonda = $stateParams.TipoRonda;
        $scope.PRUEBAS = false;
        $scope.Consecutivo = 0;

        var decrypt = $crypto.decrypt($stateParams.FechaSeleccionada, 'Franklin Ospino');
        $scope.Fecha = toConsultar(decrypt);
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Botones">
        $scope.GetHistoricoByPacienteAndMedicamentoId = ($IdAfiliado) => {
            RondaVerificacionService.GetHistoricoByPacienteAndMedicamentoId($IdAfiliado, $scope.MedicamentoId).then(function (d) {
                $scope.Historico = d.data;
                $('#HistoricoModal').modal('show');
            });
        };
        $scope.Goto = (url) => {
            $('#ArchivoModal').modal('hide');
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();
            $state.go(url);
        };
        $scope.Archivos = () => {
            $rootScope.RondaV = $scope.DetallesRonda;
            $('#ArchivoModal').modal('show');
        };
        $scope.EliminarDispositivoMedico = (o) => {
            swal({
                title: "¿Deseas Eliminar este dispositivo medico?",
                text: "Nota: Este paso solo se puede deshacer notificando a sistemas!",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "ELIMINAR!",
                cancelButtonText: "Cancelar!",
                closeOnConfirm: false,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {
                    var obj = {
                        Eliminar_DMR: JSON.stringify([{
                                DispositivoMedicoByRondaId: o.IsDP,
                                ModifiedBy: $rootScope.username.NombreCompleto
                            }])
                    };
                    RondaVerificacionService.deleteRondaVerificacion(obj).then(function (d) {
                        if (typeof d.data != "string") {
                            swal("Enhorabuena", "Se ha eliminado este dispositivo con exito", "success");
                            GetPreviewRondas();
                        }else{
                            swal("Error", d.data, "error");
                        }
                        
                    });
                }
            });
        };
        $scope.GenerarLote = () => {
            swal({
                title: "¿Deseas Generar el número del Consecutivo? Consecutivo Actual: " + $scope.Consecutivo,
                text: "Nota: Este paso solo se puede deshacer notificando a sistemas!",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-success",
                confirmButtonText: "Generar CONSECUTIVO!",
                cancelButtonText: "Cancelar!",
                closeOnConfirm: false,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {
                    var obj = {
                        ConsecutivoRonda: JSON.stringify([{
                                RondaVerificacionId: $scope.RondaVerificacionId,
                                Mes:$scope.Fecha.split("-")[1],
                                Anno:$scope.Fecha.split("-")[0],
                                TipoMedicamentoId: $scope.TipoMedicamentoId,
                                MedicamentoId: $scope.MedicamentoId,
                                CreatedBy: $rootScope.username.NombreCompleto
                            }])
                    };
                    RondaVerificacionService.postRondaVerificacion(obj).then(function (d) {
                        if (typeof d.data != "string") {
                            swal("Enhorabuena", "Se ha actualizado la ronda con exito", "success");
//                            $('#SelectModal').modal('hide');
                            GetPreviewRondas();
                        }else{
                            swal("Error", d.data, "error");
                        }
                        
                    });
                }
            });
        };
        $scope.SeleccionarUsuarios = () => {
            swal({
                title: "¿Deseas seleccionar estos usuarios?",
                text: "Nota: Este paso solo se puede deshacer notificando a sistemas!",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-success",
                confirmButtonText: "SELECCIONAR!",
                cancelButtonText: "Cancelar!",
                closeOnConfirm: false,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {
                    var obj = {
                        SelectUsuario: JSON.stringify([{
                                RondaVerificacionId: $scope.RondaVerificacionId,
                                DireccionTecnicaId:$scope.DetallesRonda.DireccionTecnicaId,
                                ACalidadId: $scope.DetallesRonda.ACalidadId,
                                QFarmaceuticoId: $scope.DetallesRonda.QFarmaceuticoId,
                                AFarmaciaId: $scope.DetallesRonda.AFarmaciaId,
                                UsuarioId: $rootScope.username.UserId,
                                ModifiedBy: $rootScope.username.NombreCompleto
                            }])
                    };
                    RondaVerificacionService.putRondaVerificacion(obj).then(function (d) {
                        if (typeof d.data != "string") {
                            swal("Enhorabuena", "Se ha actualizado la ronda con exito", "success");
                            $('#SelectModal').modal('hide');
                            GetPreviewRondas();
                        }else{
                            swal("Error", d.data, "error");
                        }
                        
                    });
                }
            });
        };
        $scope.SelectModal = () => {
            $('#SelectModal').modal('show');
        };
        $scope.Verificar = (data) => {
            let obj = {
                VerificarDetalle: JSON.stringify([{
                        DetalleRondaVerificacionId: data.DetalleRondaVerificacionId,
                        Verificado: true,
                        ModifiedBy: $rootScope.username.NombreCompleto
                    }])
            };
            RondaVerificacionService.putRondaVerificacion(obj).then(function (d) {
                data.Verificado = true;
            });
        };

        $scope.Eliminar = (d) => {
            swal({
                title: "¿Deseas ELIMINAR este item?",
                text: "Nota: Este paso solo se puede deshacer notificando a sistemas!",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "ELIMINAR!",
                cancelButtonText: "Cancelar!",
                closeOnConfirm: false,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {
                    var obj = {
                        EliminarDetalle: JSON.stringify([{
                                DetalleRondaVerificacionId: d.DetalleRondaVerificacionId,
                                Estado: 'Inactivo',
                                ModifiedBy: $rootScope.username.NombreCompleto
                            }])
                    };
                    RondaVerificacionService.deleteRondaVerificacion(obj).then(function (d) {
                        swal("Enhorabuena", "Se ha actualizado la ronda con exito", "success");
                        GetPreviewRondas();
                    });
                }
            });
        };
        $scope.GenerateStickers = (d) => {
            let list = [];
            for (let i = 0; i < d.Cantidad + 1; i++) {
                list.push(d);
            }
            $scope.Etiquetas = angular.copy(Crearpaquetes(list));
            $('#EtiquetasModal').modal('show');
        };
        $scope.ImprimirEtiqueta = () => {
            $("#Etiquetas").print({
                globalStyles: true,
                mediaPrint: false,
                stylesheet: "/intranet-2/public_html/styles/Etiquetas.css",
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
        };
        $scope.FirmarDireccionTecnica = function () {
            $scope.DetallesRonda.ModifiedBy = $rootScope.username.NombreCompleto;
            $scope.DetallesRonda.DireccionTecnica = true;
            let obj = {
                DireccionTecnica: JSON.stringify($scope.DetallesRonda)
            };
            RondaVerificacionService.putRondaVerificacion(obj).then(function (d) {
                if (typeof d.data != "string") {
                    swal("Enhorabuena", "Se ha actualizado la ronda con exito", "success");
                } else {
                    swal("Error", d.data, "error");
                }
            });
        };
        $scope.FirmarACalidad = function () {
            $scope.DetallesRonda.ModifiedBy = $rootScope.username.NombreCompleto;
            $scope.DetallesRonda.ACalidad = true;
            let obj = {
                ACalidad: JSON.stringify($scope.DetallesRonda)
            };
            RondaVerificacionService.putRondaVerificacion(obj).then(function (d) {
                if (typeof d.data != "string") {
                    swal("Enhorabuena", "Se ha actualizado la ronda con exito", "success");
                } else {
                    swal("Error", d.data, "error");
                }
            });
        };
        $scope.FirmarQFarmaceutico = function () {
            $scope.DetallesRonda.ModifiedBy = $rootScope.username.NombreCompleto;
            $scope.DetallesRonda.QFarmaceutico = true;
            let obj = {
                QFarmaceutico: JSON.stringify($scope.DetallesRonda)
            };
            RondaVerificacionService.putRondaVerificacion(obj).then(function (d) {
                if (typeof d.data != "string") {
                    swal("Enhorabuena", "Se ha actualizado la ronda con exito", "success");
                } else {
                    swal("Error", d.data, "error");
                }
            });
        };

        $scope.FirmarAFarmacia = function () {
            $scope.DetallesRonda.ModifiedBy = $rootScope.username.NombreCompleto;
            $scope.DetallesRonda.AFarmacia = true;
            let obj = {
                AFarmacia: JSON.stringify($scope.DetallesRonda)
            };
            RondaVerificacionService.putRondaVerificacion(obj).then(function (d) {
                if (typeof d.data != "string") {
                    swal("Enhorabuena", "Se ha actualizado la ronda con exito", "success");
                } else {
                    swal("Error", d.data, "error");
                }
            });
        };

        $scope.AddDispositivoMedico = function () {
            if ($scope.Dispositivo.DispositivoMedicoId) {
                var data = {
                    RondaVerificacionId: $scope.RondaVerificacionId,
                    MedicamentoId: $scope.MedicamentoId,
                    Cantidad: $scope.Dispositivo.Cantidad,
                    DispositivoMedicoId: $scope.Dispositivo.DispositivoMedicoId.description.DispositivoMedicoId,
                    CreatedBy: $rootScope.username.NombreCompleto
                };
                var obj = {
                    DMByRonda: JSON.stringify(data)
                };
                VehiculoService.postDM(obj).then(function (d) {
                    if (typeof d.data != "string") {
                        swal("Enhorabuena", "Se ha guardado la ronda con exito", "success");
                        $scope.Dispositivo = {};
                        GetPreviewProductoInicial();
                    } else {
                        swal("Error", d.data, "error");
                    }
                });
            } else {
                swal("Error", "Debes ingresar un Dispositivo Medico", "error");
            }

        };
        $scope.ChangeMedicamento = function () {
            $scope.DetallesRonda = [];
            GetPreviewRondas();
//            GetPreviewProductoInicial();
        };
        $scope.Imprimir = function () {
            printDiv();
        };
        //</editor-fold>
        function toConsultar(dateStr) {
            let parts = dateStr.split("-");
            return parts[0] + "-" + parts[1] + "-" + parts[2];
        }
        function printDiv() {
            $("#TablaPreviewExcel").print({
                globalStyles: true,
                mediaPrint: false,
                stylesheet: "/intranet-2/public_html/styles/ExcelCM.css",
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
        function Crearpaquetes(lst) {
            let size = 2;
            let groups = lst.map(function (e, i) {
                return i % size === 0 ? lst.slice(i, i + size) : null;
            }).filter(function (item) {
                return item;
            });
            return groups;
        }
        //<editor-fold defaultstate="collapsed" desc="Consultas">
        function GetConsecutivos(){
            RondaVerificacionService.GetConsecutivos($scope.RondaVerificacionId, $scope.MedicamentoId, $scope.Fecha).then((d) => {
                console.log(d.data);
                $scope.Consecutivo = d.data.NumeroEnMes;
            });
        }
        function GetUsuario(){
        UsuarioService.GetUsuariosCM(SesionService.get("UserData_Polivalente").key, SesionService.get("UserData_Polivalente").Email).then(function (d) {
                console.log(d.data);
                $scope.Usuarios = d.data;
            });
        }
        function GetPreviewRondas() {
            RondaVerificacionService.GetPreviewRondas($scope.RondaVerificacionId, $scope.MedicamentoId, $scope.Fecha).then(function (c) {
                $scope.DetallesRonda = angular.copy(c.data);
                console.log(c.data)
            }).then(function () {
                GetPreviewProductoInicial();
            }).then(() =>{
                GetConsecutivos();
            });
        }
        function GetPreviewProductoInicial() {
            RondaVerificacionService.GetPreviewProductoInicial($scope.RondaVerificacionId, $scope.MedicamentoId, $scope.Fecha).then(function (c) {
                $scope.Productos = angular.copy(c.data);
            });
        }

        function GetMedicamentos() {
            $scope.Medicamentos = [];
            $scope.Productos = [];
            if ($scope.TipoRonda == "Loteado") {
                MedicamentoService.getMedicamentosLoteados($scope.TipoMedicamentoId).then(function (c) {
                    $scope.Medicamentos = c.data;
                    $scope.MedicamentoId = c.data[0].MedicamentoId;
                    GetPreviewRondas();
                    GetPreviewProductoInicial();
                });
            } else {
                MedicamentoService.getMedicamentos($scope.TipoMedicamentoId).then(function (c) {
                    $scope.Medicamentos = c.data;
                    $scope.MedicamentoId = c.data[0].MedicamentoId;
                    GetPreviewRondas();
                    GetPreviewProductoInicial();
                });
            }

        }

        function GetVehiculos() {
            VehiculoService.getAllVehiculos().then(function (d) {
                $scope.Dispositivos = d.data;
            });
        }
        //</editor-fold>

        function _init() {
            GetMedicamentos();
            GetVehiculos();
            GetUsuario();
            let host = window.location.hostname;
            if(host === '192.168.9.139'){
                swal("ATENCIÓN", "ESTAS EN MODO PRUEBAS", "warning");
                $scope.PRUEBAS = true;
            }
        }
        _init();
    }]);




