app.controller('RotularMedicamentoCtrl', ["$scope", "$rootScope", "$stateParams", "$crypto", "MedicamentoService", "OrdenRRService", "EncabezadoService", "VehiculoService",
    function ($scope, $rootScope, $stateParams, $crypto, MedicamentoService, OrdenRRService, EncabezadoService, VehiculoService) {
        //<editor-fold defaultstate="collapsed" desc="Variables">
        $scope.Etiquetas = [];
        $scope.Dispositivos = [];
        $scope.UsuarioId = $rootScope.username.UserId;
        $scope.token = $stateParams.token;
        var decrypt = $crypto.decrypt($stateParams.token, 'Franklin Ospino');
        $scope.Fecha = toConsultar(decrypt);
        $scope.DetalleOrdenRR = {
            MedicamentoId: "",
            DispositivoMedicoId: null
        };
        $scope.PreOrdenRR = [];
        $scope.OrdenRR = {
            TipoMedicamento: 'Liquidos',
            Fecha: $scope.Fecha,
            DetalleOrdenRR: [],
            DireccionTecnicaId: $rootScope.username.UserId,
            DireccionTecnica: true,
            NombreDireccionTecnica: $rootScope.username.NombreCompleto,
            CreatedBy: $rootScope.username.NombreCompleto
        };
        $scope.Encabezado = {};
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Botones">
        $scope.GenerarEtiquetas = () => {
            console.log($scope.OrdenRR.DetalleOrdenRR)
            $scope.Etiquetas = Crearpaquetes($scope.OrdenRR.DetalleOrdenRR);
            console.log($scope.Etiquetas);
            $('#EtiquetasModal').modal('show');

        };
        $scope.GuardarPreOrdenRR = () => {
            if ($scope.PreOrdenRR.length >= 1) {
                $scope.OrdenRR.DetalleOrdenRR = $scope.PreOrdenRR;
                var obj = {
                    OrdenRR: JSON.stringify([$scope.OrdenRR])
                };
                OrdenRRService.postOrdenRR(obj).then(function (d) {
                    if (typeof d.data != "string") {
                        $scope.PreOrdenRR = [];
                        swal("Enhorabuena", "Se ha guardado la orden con exito", "success");
                        GetOrdenRR();
                    } else {
                        swal("Error", d.data, "error");
                    }
                });

            }
        };
        $scope.AprobarProduccion = function () {
            $scope.OrdenRR.ModifiedBy = $rootScope.username.NombreCompleto;
            var obj = {
                OrdenRR_AProduccion: JSON.stringify([$scope.OrdenRR])
            };
            OrdenRRService.putOrdenRR(obj).then(function (d) {
                swal("Enhorabuena", "Se ha firmado la orden con exito", "success");
                GetOrdenRR();
            });
        };
        $scope.AprobarAFarmacia = function () {
            $scope.OrdenRR.AFarmacia = true;
            $scope.OrdenRR.ModifiedBy = $rootScope.username.NombreCompleto;
            var obj = {
                OrdenRR_AFarmacia: JSON.stringify([$scope.OrdenRR])
            };
            OrdenRRService.putOrdenRR(obj).then(function (d) {
                swal("Enhorabuena", "Se ha firmado la orden con exito", "success");
                GetOrdenRR();
            });
        };
        $scope.CreateDetalleOrdenRRMedicamento = function () {
            if (!$scope.Datos.$valid) {
                angular.element("[name='" + $scope.Datos.$name + "']").find('.ng-invalid:visible:first').focus();
            } else {
                if ($scope.DetalleOrdenRR.Vehiculo) {
                    $scope.DetalleOrdenRR.DispositivoMedicoId = angular.copy($scope.DetalleOrdenRR.Vehiculo.description.DispositivoMedicoId);
                    $scope.DetalleOrdenRR.DispositivoMedico = angular.copy($scope.DetalleOrdenRR.Vehiculo.title);
                    $scope.DetalleOrdenRR.Item = $scope.PreOrdenRR.length + 1;
                    $scope.PreOrdenRR.push(angular.copy($scope.DetalleOrdenRR));
                    $("#ex10_value").val("");
                    $scope.DetalleOrdenRR = {
                        MedicamentoId: "",
                        DispositivoMedicoId: null
                    };
                } else {
                    swal("Error", "Debes ingresar un Dispositivo Medico", "error");
                }

            }
        };
        $scope.ChangeTipoMedicamento = function () {
            GetOrdenRR();
        };
        $scope.ChangeMedicamento = function () {
            MedicamentoService.getMedicamentoById($scope.DetalleOrdenRR.MedicamentoId).then(function (m) {
                $scope.DetalleOrdenRR.Medicamento = m.data[0].Nombre;
                $scope.DetalleOrdenRR.FormaFarmaceutica = m.data[0].FormaFarmaceutica;
                $scope.DetalleOrdenRR.Concentracion = m.data[0].Concentracion;
                $scope.DetalleOrdenRR.Laboratorio = m.data[0].Laboratorio;
                $scope.DetalleOrdenRR.RegInvima = m.data[0].RegInvima;
            });
        };
        $scope.Imprimir = function () {
            printDiv();
        };
        $scope.ImprimirEtiquetas = () =>{
            $("#Etiquetas").print({
                globalStyles: true,
                mediaPrint: false,
                stylesheet: "/intranet-2/public_html/styles/Rotulados.css",
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
        };
        //</editor-fold>
        function printDiv() {
            $("#TablaPreviewExcel").print({
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
        function toConsultar(dateStr) {
            var parts = dateStr.split("-");
            return parts[0] + "-" + parts[1] + "-" + parts[2];
        }
        function Crearpaquetes(lst) {
            var size = 2;
            var Listado = [];
            for (var i in lst) {
                for (var k = 0; k < lst[i].Cantidad + 1; k++) {
                    Listado.push(lst[i]);
                }
            }
            
            var groups = Listado.map(function (e, i) {
                return i % size === 0 ? Listado.slice(i, i + size) : null;
            }).filter(function (item) {
                return item;
            });
            return groups;
        }
        //<editor-fold defaultstate="collapsed" desc="Consultas">
        function GetVehiculos() {
            VehiculoService.getVehiculos().then(function (d) {
                $scope.Dispositivos = d.data;
            });
        }
        function GetEncabezado() {
            EncabezadoService.getEncabezado().then(function (e) {
                $scope.Encabezado = e.data;
            });
        }

        function GetMedicamentos() {
            $scope.Medicamentos = [];
            MedicamentoService.getAllMedicamentosByTipoMedicamento($scope.OrdenRR.TipoMedicamento).then(function (c) {
                $scope.Medicamentos = c.data;
            });
        }

        function GetOrdenRR() {
            $scope.Medicamentos = [];
            var tm = angular.copy($scope.OrdenRR.TipoMedicamento);
            OrdenRRService.GetOrdenByFecha($scope.OrdenRR.Fecha, $scope.OrdenRR.TipoMedicamento).then(function (c) {
                if (typeof c.data == "object" && c.data.length > 0) {
                    $scope.OrdenRR = c.data[0];
                    $scope.OrdenRR.TipoMedicamento = tm;
                } else {
                    $scope.OrdenRR = {
                        TipoMedicamento: tm,
                        Fecha: $scope.Fecha,
                        DetalleOrdenRR: [],
                        DireccionTecnicaId: $rootScope.username.UserId,
                        DireccionTecnica: true,
                        NombreDireccionTecnica: $rootScope.username.NombreCompleto,
                        CreatedBy: $rootScope.username.NombreCompleto
                    };
                }
            }).then(function () {
                GetMedicamentos();

            });
        }
        //</editor-fold>

        function _init() {
            GetOrdenRR();
            GetEncabezado();
            GetVehiculos();
        }
        _init();
    }]);





