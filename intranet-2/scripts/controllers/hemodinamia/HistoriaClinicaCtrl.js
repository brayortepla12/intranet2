'use strict';
app.controller('HistoriaClinicaCtrl', ["$scope", "$rootScope", 'EmpresaService', 'PacienteService', 'EncabezadoService',
    function ($scope, $rootScope, EmpresaService, PacienteService, EncabezadoService) {
        //<editor-fold defaultstate="collapsed" desc="inicializar variables">
        $scope.Historia = false;
        $scope.Paciente = {
            Nombres: "",
            Documento: "",
            Edad: "",
            EstadoCivil: "SOLTERO",
            Religion: "",
            Procedencia: "",
            ResidenciaActual: "",
            DireccionActual: "",
            Telefono: "",
            Ocupacion: "",
            RegimenSeguridadSocial: "CONTRIBUTIVO",
            Entidad: "",
            HistoricaClinica: {
                TratamientoFarmacologicoActual: "",
                AntecedentesFamiliares: "",
                FactoresRiesgoCardiovasculares: "",
                ManejoActual: "",
                // antecedentes AntecedentesPersonales
                Patologicos: "",
                EnfermedadesInfancia: "",
                EnfermedadesAdultez: "",
                Quirurgicos: "",
                Hospitalizaciones: "",
                Transfusiones: "",
                Toxicos: "",
                Alergicos: "",
                // AntecedentesPsicosociales
                Alimenticios: "",
                Cigarrillo: "",
                Alcohol: "",
                Drogas: ""
            },
            // -- Contantenos
            TEsposo_a: "",
            TMadre_Padre: "",
            THermano_a: "",
            TAmigo_a: "",
            THijo_a: "",
            CreatedBy: $rootScope.username.NombreCompleto
        };
        // inicializamos las variables en _init()
        _init();
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Consultas">
        function GetEncabezado() {
            EncabezadoService.getEncabezado().then(function (e) {
                $scope.Encabezado = e.data;
            });
        }
        function GetEmpresa() {
            EmpresaService.getEmpresa().then(function (e) {
                $scope.Empresa = e.data;
            });
        }
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Botones guardar e imprimir">
        $scope.Guardar = function () {
            if (!$scope.Datos.$valid) {
                angular.element("[name='" + $scope.Datos.$name + "']").find('.ng-invalid:visible:first').focus();
            } else {
//                console.log($scope.Paciente);
                var obj = {
                    Paciente: JSON.stringify($scope.Paciente)
                };
                PacienteService.PostPaciente(obj).then(function (d) {
                    console.log(d.data);
                    if (typeof d.data != "string") {
                        swal("Enhorabuena!", 'Se han guardado los datos satisfactoriamente', "success");
                        $scope.Paciente = {
                            Nombres: "",
                            Documento: "",
                            Edad: "",
                            EstadoCivil: "SOLTERO",
                            Religion: "",
                            Procedencia: "",
                            ResidenciaActual: "",
                            DireccionActual: "",
                            Telefono: "",
                            Ocupacion: "",
                            RegimenSeguridadSocial: "CONTRIBUTIVO",
                            Entidad: "",
                            HistoricaClinica: {
                                TratamientoFarmacologicoActual: "",
                                AntecedentesFamiliares: "",
                                FactoresRiesgoCardiovasculares: "",
                                ManejoActual: "",
                                // antecedentes AntecedentesPersonales
                                Patologicos: "",
                                EnfermedadesInfancia: "",
                                EnfermedadesAdultez: "",
                                Quirurgicos: "",
                                Hospitalizaciones: "",
                                Transfusiones: "",
                                Toxicos: "",
                                Alergicos: "",
                                // AntecedentesPsicosociales
                                Alimenticios: "",
                                Cigarrillo: "",
                                Alcohol: "",
                                Drogas: ""
                            },
                            // -- Contantenos
                            TEsposo_a: "",
                            TMadre_Padre: "",
                            THermano_a: "",
                            TAmigo_a: "",
                            THijo_a: "",
                            CreatedBy: $rootScope.username.NombreCompleto
                        };
                    } else {
                        swal("Error", d.data, "error");
                    }
                });
            }
        };

        $scope.Imprimir = function () {
            printDiv();
        };

        //</editor-fold>

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

        function _init() {
            GetEncabezado();
            GetEmpresa();
        }


    }]);






