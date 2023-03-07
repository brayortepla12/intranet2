app.controller('ListadoLideresCtrl', ["$scope", "$rootScope", "TmLiderService", "MaternaService", "DepartamentoService", "MunicipioService", "TarifaService",
    function ($scope, $rootScope, TmLiderService, MaternaService, DepartamentoService, MunicipioService, TarifaService) {
        //<editor-fold defaultstate="collapsed" desc="Variables">
        $scope.ToPrint = false;
        $scope.cargado = false;
        $scope.ULider = {
            Nombres: "",
            Documento: "",
            Telefono: "",
            DepartamentoId: 11,
            MunicipioId: "",
            Detalles: [],
            Acompanante: false,
            MaternaId: null,
            ModifiedBy: $rootScope.username.NombreCompleto
        };
        $scope.Departamentos = [];
        $scope.Municipios = [];
        $scope.TarifaId = "";
        $scope.Tarifas = [];
        $scope.Lider = {
            Nombres: "",
            Documento: "",
            Telefono: "",
            DepartamentoId: 11,
            MunicipioId: "",
            Detalles: [],
            Acompanante: false,
            MaternaId: null,
            CreatedBy: $rootScope.username.NombreCompleto
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Botones">
        $scope.Reset = () => {
            Reset();
        };
        $scope.CrearLiderModal = function () {
            $('#CrearLiderModal').modal('show');
        };
        $scope.GuardarLider = function () {
            var obj = {
                Lider: JSON.stringify($scope.Lider)
            };
            if (!$scope.Datos.$valid) {
                angular.element("[name='" + $scope.Datos.$name + "']").find('.ng-invalid:visible:first').focus();
            } else {
                TmLiderService.postTmLider(obj).then(function (d) {
                    if (typeof d.data != "string") {
                        swal("Enhorabuena", "Se ha guardado el evento con exito", "success");
                        GetLideres();
                        Reset();
                    } else {
                        swal("Error", d.data, "error");
                    }
                });
            }
        };
        $scope.ShowLider = function (Id) {
            getLiderById(Id);
        };

        $scope.ChangeDepartamento = function () {
            GetMunicipioByDepartamento();
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="consultas">
        function GetLideres() {
            $scope.cargado = false;
            TmLiderService.getLideres().then(function (c) {
                $scope.Lideres = c.data;
                $scope.simpleTableOptions = {
                    data: [],
                    aoColumns: [
                        {mData: 'LiderId'},
                        {mData: 'Nombres'},
                        {mData: 'Ciudad'},
                        {mData: 'Opciones'},
                    ],
                    "searching": true,
                    //                "sDom": "Tflt<'row DTTTFooter'<'col-sm-6'i><'col-sm-6'p>>",
                    //                "oTableTools": {
                    //                    "aButtons": [
                    //                        "xls", "pdf"
                    //                    ],
                    //                    "sSwfPath": "assets/swf/copy_csv_xls_pdf.swf"
                    //                },
                    "iDisplayLength": 25,
                    "language": {
                        "lengthMenu": "Mostrar _MENU_ registros por página",
                        "zeroRecords": " No hay Items Registrados ",
                        "infoFiltered": "(filtro de _MAX_ registros totales)",
                        "search": " Filtrar : ",
                        "oPaginate": {
                            "sPrevious": "Anterior",
                            "sNext": "Siguiente"
                        }
                    },
                    "aaSorting": []
                };
                $scope.simpleTableOptions.data = SetFormat(c.data);
                $scope.cargado = true;
            });
        }
        function GetDepartamentos() {
            DepartamentoService.GetDepartamentos().then(function (d) {
                $scope.Departamentos = d.data;
            }).then(function () {
                GetMunicipioByDepartamento();
            });
        }
        function GetMunicipioByDepartamento() {
            MunicipioService.GetMunicipiosByDepartamentoId($scope.Lider.DepartamentoId).then(function (m) {
                $scope.Municipios = m.data;
            });
        }
        function getLiderById(Id) {
            TmLiderService.getLiderByLiderId(Id).then(function (d) {
                $scope.ULider = d.data;
                $('#UpdateLiderModal').modal('show');
            });
        }
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Helpers">
        function Reset() {
            $scope.ULider = {
                Nombres: "",
                Documento: "",
                Telefono: "",
                DepartamentoId: 11,
                MunicipioId: "",
                Detalles: [],
                Acompanante: false,
                MaternaId: null,
                ModifiedBy: $rootScope.username.NombreCompleto
            };
            $scope.Lider = {
                Nombres: "",
                Documento: "",
                Telefono: "",
                DepartamentoId: 11,
                MunicipioId: "",
                Detalles: [],
                Acompanante: false,
                MaternaId: null,
                CreatedBy: $rootScope.username.NombreCompleto
            };
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
        function SetFormat(lst) {
            for (var i in lst) {
                lst[i].Opciones = '<a class="btn  btn-primary btn-xs icon-only white" onclick=\"angular.element(this).scope().ShowLider(' + lst[i].LiderId + ')\"/><i class="fa fa-eye"></i></a>';

            }
            return lst;
        }
        //</editor-fold>
        function _init() {
            GetLideres();
            GetDepartamentos();
        }
        _init();
    }]);


