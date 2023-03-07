app.controller('ListadoMaternasCtrl', ["$scope", "$rootScope", "TmLiderService", "MaternaService", "DepartamentoService", "MunicipioService", "TmEPSService", "TmEventoService",
    function ($scope, $rootScope, TmLiderService, MaternaService, DepartamentoService, MunicipioService, TmEPSService, TmEventoService) {
        //<editor-fold defaultstate="collapsed" desc="Variables">
        let vm = $scope;
        vm.ToPrint = false;
        vm.cargado = false;
        vm.Excel = [];
        vm.Eventos = [];
        vm.NombreExcel = "";
        vm.DataSet = [];
        vm.UMaterna = {
            Nombres: "",
            Documento: "",
            Telefono: "",
            DepartamentoId: 11,
            FechaUltimaRegla: "",
            EdadUltimaEcografia: "",
            FechaProbableParto: "",
            FechaPrimeraEcografia: "",
            MunicipioId: "",
            EPSId: null,
            LiderId: "",
            EdadGestional: null,
            ModifiedBy: $rootScope.username.NombreCompleto
        };
        vm.Departamentos = [];
        vm.Municipios = [];
        vm.Lideres = [];
        vm.EPSs = [];
        vm.Materna = {
            Nombres: "",
            Documento: "",
            Telefono: "",
            DepartamentoId: 11,
            FechaUltimaRegla: "",
            EdadUltimaEcografia: "",
            FechaProbableParto: "",
            FechaPrimeraEcografia: "",
            MunicipioId: "",
            EPSId: null,
            LiderId: "",
            EdadGestional: null,
            CreatedBy: $rootScope.username.NombreCompleto
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Botones">
        vm.ShowEventos = (MaternaId) => {
            getEventosByMaternaId(MaternaId);
        };
        vm.CrearMaternaModal = function () {
            $('#CrearMaternaModal').modal('show');
        };
        vm.ExcelMaternaModal = () => {
            $('#ExcelMaternaModal').modal('show');
        };
        vm.Reset = () => {
            Reset();
        };
        vm.GuardarMaterna = function () {
            var obj = {
                TmMaterna: JSON.stringify(vm.Materna)
            };
            debugger;
            if (!vm.Datos.$valid) {
                angular.element("[name='" + vm.Datos.$name + "']").find('.ng-invalid:visible:first').focus();
            } else if (!vm.Materna.EPSId) {
                swal("Error", "Debe seleccionar una EPS", "error");
            } else {
                MaternaService.postTmMaterna(obj).then(function (d) {
                    if (typeof d.data != "string") {
                        swal("Enhorabuena", "Se han guardado los datos con exito", "success");
                        $('#CrearMaternaModal').modal('hide');
                        GetMaternas();
                        Reset();
                    } else {
                        swal("Error", d.data, "error");
                    }
                });
            }
        };
        vm.ActualizarMaterna = function () {
            vm.UMaterna.ModifiedBy = $rootScope.username.NombreCompleto;
            var obj = {
                TmMaterna: JSON.stringify(vm.UMaterna)
            };
            if (!vm.UDatos.$valid) {
                angular.element("[name='" + vm.UDatos.$name + "']").find('.ng-invalid:visible:first').focus();
            } else if (!vm.UMaterna.EPSId) {
                swal("Error", "Debe seleccionar una EPS", "error");
            } else {
                MaternaService.putTmMaterna(obj).then(function (d) {
                    console.log(d.data)
                    if (typeof d.data != "string") {
                        swal("Enhorabuena", "Se han actualizado los datos con exito", "success");
                        $('#UpdateMaternaModal').modal('hide');
                        GetMaternas();
                        Reset();
                    } else {
                        swal("Error", d.data, "error");
                    }
                });
            }
        };
        vm.CalcularFUR = () => {
            let fur = new Date(vm.Materna.FechaUltimaRegla);
            fur = addDays(fur, 281);
            vm.Materna.FechaProbableParto = dateToYMD(fur);
        };
        vm.CalcularEdadGestacional = function () {
            let semana_restante = 40 - vm.Materna.EdadUltimaEcografia;
            let fecha = addDays(moment(vm.Materna.FechaPrimeraEcografia), (semana_restante * 7) + 1);
            vm.Materna.FechaProbableParto = dateToYMD(fecha);
        };
        vm.CalcularFUR_U = () => {
            let fur = new Date(vm.UMaterna.FechaUltimaRegla);
            fur = addDays(fur, 281);
            vm.UMaterna.FechaProbableParto = dateToYMD(fur);
        };
        vm.CalcularEdadGestacional_U = function () {
            let semana_restante = 40 - vm.UMaterna.EdadUltimaEcografia;
            let fecha = addDays(moment(vm.UMaterna.FechaPrimeraEcografia), (semana_restante * 7) + 1);
            vm.UMaterna.FechaProbableParto = dateToYMD(fecha);
        };
//        //<editor-fold defaultstate="collapsed" desc="XLSX">
//        vm.read = function (workbook) {
//            /* DO SOMETHING WITH workbook HERE */
//            console.log(workbook);
//            vm.Excel = angular.copy(workbook);
//            vm.$apply();
//        };
//        
//        vm.error = function (e) {
//            /* DO SOMETHING WHEN ERROR IS THROWN */
//            swal("Error", e, "error");
//        };
//        //</editor-fold>
//        vm.ChangeTable = () => {
//            vm.DataSet = vm.Excel.Sheets[vm.NombreExcel];
//            console.log(vm.Excel.Sheets[vm.NombreExcel]);
//        };
        vm.ShowMaterna = function (Id) {
            getMaternaById(Id);
        };
        vm.ChangeDepartamento = function () {
            GetMunicipioByDepartamento();
        };
        vm.ChangeUDepartamento = function () {
            GetMunicipioUByDepartamento();
        };
        vm.ChangeMunicipio = function () {
            GetLiderByMunicipioId(vm.Materna.MunicipioId);
        };
        vm.ChangeUMunicipio = function () {
            GetLiderByMunicipioId(vm.UMaterna.MunicipioId);
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="consultas">
        function GetMaternas() {
            vm.cargado = false;
            MaternaService.getMaternas().then(function (c) {
                vm.Maternas = c.data;
                vm.simpleTableOptions = {
                    data: [],
                    aoColumns: [
                        {mData: 'MaternaId'},
                        {mData: 'Nombres'},
                        {mData: 'Documento'},
                        {mData: 'FechaProbableParto'},
                        {mData: 'Ciudad'},
                        {mData: 'Telefono'},
                        {mData: 'Lider'},
                        {mData: 'TelefonoLider'},
                        {mData: 'HaveParto'},
                        {mData: 'Opciones'},
                    ],
                    "searching": true,
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
                vm.simpleTableOptions.data = SetFormat(c.data);
                vm.cargado = true;
            });
        }
        function GetDepartamentos() {
            DepartamentoService.GetDepartamentos().then(function (d) {
                vm.Departamentos = d.data;
            }).then(function () {
                GetMunicipioByDepartamento();
            });
        }
        function GetMunicipioByDepartamento() {
            MunicipioService.GetMunicipiosByDepartamentoId(vm.Materna.DepartamentoId).then(function (m) {
                vm.Municipios = m.data;
            });
        }

        function GetMunicipioUByDepartamento() {
            MunicipioService.GetMunicipiosByDepartamentoId(vm.UMaterna.DepartamentoId).then(function (m) {
                vm.Municipios = m.data;
            });
        }
        function getEventosByMaternaId(MaternaId) {
            vm.Eventos = [];
            TmEventoService.getEventosByMaternaId(MaternaId).then((e) => {
                vm.Eventos = e.data;
                $('#EventosModal').modal('show');
            });
        }
        function getMaternaById(MaternaId) {
            MaternaService.getMaternaByMaternaId(MaternaId).then((m) => {
                $('#UpdateMaternaModal').modal('show');
                vm.UMaterna = m.data[0];
                $('#Update_value').val(m.data[0].EPS);
            })
                    .then(() => {
                        vm.UMaterna.EPSId = {
                            originalObject: {EPSId: vm.UMaterna.EPSId}
                        };
                        vm.ChangeUMunicipio();
                    });
        }
        function GetLiderByMunicipioId(MunicipioId) {
            TmLiderService.GetLiderByMunicipioId(MunicipioId).then(function (c) {
                if (c.data.length > 0 && typeof c.data !== "string") {
                    vm.Lideres = c.data;
                    vm.Materna.LiderId = c.data[0].LiderId;
                    vm.UMaterna.LiderId = c.data[0].LiderId;
                } else {
                    vm.Lideres = [];
                    vm.Materna.LiderId = "";
                    vm.UMaterna.LiderId = "";
                }
            });
        }
        function GetEPSs() {
            TmEPSService.getEPSs().then(function (d) {
                if (d.data.length > 0 && typeof d.data !== "string") {
                    vm.EPSs = d.data;
                } else {
                    console.log(d.data);
                }
            });
        }
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Helpers">
        function Reset() {
            $('#ex8_value').val('');
            $('#Update_value').val('');
            vm.Materna = {
                Nombres: "",
                Documento: "",
                Telefono: "",
                DepartamentoId: 11,
                FechaUltimaRegla: "",
                EdadUltimaEcografia: "",
                FechaProbableParto: "",
                FechaPrimeraEcografia: "",
                MunicipioId: "",
                EPSId: null,
                LiderId: "",
                EdadGestional: null,
                CreatedBy: $rootScope.username.NombreCompleto
            };
            vm.UMaterna = {
                Nombres: "",
                Documento: "",
                Telefono: "",
                DepartamentoId: 11,
                FechaUltimaRegla: "",
                EdadUltimaEcografia: "",
                FechaProbableParto: "",
                MunicipioId: "",
                FechaPrimeraEcografia: "",
                EPSId: null,
                LiderId: "",
                EdadGestional: null,
                ModifiedBy: $rootScope.username.NombreCompleto
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
                vm.ToPrint = false;
                vm.$apply();
            }, 1000);
        }
        function SetFormat(lst) {
            for (var i in lst) {
                if (lst[i].HaveParto == 1) {
                    lst[i].HaveParto = '<img src="/intranet-2/public_html/image/bebe.png" width="20"/>';
                }else if(lst[i].HaveParto == 2){
                    lst[i].HaveParto = '<img src="/intranet-2/public_html/image/uncheck.png" width="20"/>';
                }else{
                    lst[i].HaveParto = '...';
                }
                lst[i].Opciones = '<a class="btn  btn-primary btn-xs icon-only white" onclick=\"angular.element(this).scope().ShowMaterna(' + lst[i].MaternaId + ')\"><i class="fa fa-eye"></i></a> ' +
                                    '<a class="btn  btn-primary btn-xs icon-only white" onclick=\"angular.element(this).scope().ShowEventos(' + lst[i].MaternaId + ')\"><i class="fa fa-list-ol"></i></a>';
            }
            return lst;
        }
        function addDays(date, days) {
            var result = new Date(date);
            result.setDate(result.getDate() + days);
            return result;
        }
        function dateToYMD(date) {
            var d = date.getDate();
            var m = date.getMonth() + 1; //Month from 0 to 11
            var y = date.getFullYear();
            return '' + y + '-' + (m <= 9 ? '0' + m : m) + '-' + (d <= 9 ? '0' + d : d);
        }

        //</editor-fold>
        function _init() {
            GetMaternas();
            GetDepartamentos();
            GetEPSs();
        }
        _init();
    }]);




