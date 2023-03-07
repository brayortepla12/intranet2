"use strict"
app.controller('ReporteDiarioCtrl', ["$scope", "$rootScope", "$state", "SesionService", 'ReporteService',
    'EmpresaService', '$filter', 'EncabezadoService', 'EquipoService',
    function ($scope, $rootScope, $state, SesionService, ReporteService, EmpresaService, $filter, EncabezadoService, EquipoService) {
        //<editor-fold defaultstate="collapsed" desc="Variables">
        $scope.Plantas = [];
        $scope.item = [];
        $scope.ResponsableFirma = $rootScope.username.Firma;
        $scope.ResponsableCargo = $rootScope.username.Cargo;
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Botones">
        $scope.Guardar = function () {
            if (!$scope.Datos.$valid) {
                angular.element("[name='" + $scope.Datos.$name + "']").find('.ng-invalid:visible:first').focus();
            } else {
                var cont = 1;
                for (var i in $scope.item) {
                    $scope.item[i].reporte_diario.CreatedBy = $rootScope.username.NombreUsuario;
                    $scope.item[i].reporte_diario.RecibeFecha = new Date();
                    $scope.item[i].reporte_diario.RecibeEmail = $rootScope.username.NombreUsuario;
                    $scope.item[i].reporte_diario.ResponsableNombre = $rootScope.username.NombreCompleto;
                    $scope.item[i].reporte_diario.ResponsableCargo = $rootScope.username.Cargo;
                    $scope.item[i].reporte_diario.ResponsableFirma = $rootScope.username.Firma;
                    $scope.item[i].reporte_diario.RecibeNombre = $rootScope.username.NombreJefe;
                    $scope.item[i].reporte_diario.RecibeCargo = $rootScope.username.CargoJefe;

                    var obj = {
                        Reporte: JSON.stringify([$scope.item[i].reporte_diario]),
                        UserId: $rootScope.username.UserId
                    };
                    console.log($scope.item.length);
                    ReporteService.postReporte(obj).then(function (d) {
                        console.log(d.data)
                        if (typeof d.data === "string") {
                            swal("Error", d.data, "error");
                        }
                    }, function (e) {
                        swal("Error", e, "error");
                    });
                    if (cont === $scope.item.length) {
                        $scope.Reset();
                        swal("Enhorabuena!", 'Se hanguardado los datos satisfactoriamente', "success");
                    }
                    cont++;
                }
            }
        };

        $scope.Reset = function () {
            $scope.item = [];
            $scope.Plantas = [];
            $('input[type=checkbox]:checked').removeAttr('checked');
            _init();
        };
        $scope.Recibe = function (i) {
            $scope.reporte.RecibeCargo = $scope.Usuarios[i].Cargo;
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
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Helpers">

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

        function GetEncabezado() {
            EncabezadoService.getEncabezado().then(function (e) {
                $scope.Encabezado = e.data;
            });
        }
        function GetPlantasBySede() {
            EquipoService.GetAllPlantaBySede($rootScope.username.UserId).then(function (d) {
                console.log(d.data);
                $scope.Plantas = d.data;
            });
        }
        function _init() {
            GetEmpresa();
            GetEncabezado();
            GetPlantasBySede();
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
        //</editor-fold>
//        printDiv();
        _init();
    }]);






