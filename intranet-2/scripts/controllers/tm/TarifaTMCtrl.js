app.controller('TarifaTMCtrl', ["$scope", "$rootScope", "TmEventoService", "MaternaService", "DepartamentoService", "MunicipioService", "TarifaService",
    "EmpresaService", "EncabezadoService",
    function ($scope, $rootScope, TmEventoService, MaternaService, DepartamentoService, MunicipioService, TarifaService, EmpresaService, EncabezadoService) {
        //<editor-fold defaultstate="collapsed" desc="Variables">
        var fechaHoy = moment().format().split('T')[0];
        $scope.cargado = false;
        $scope.simpleTableOptions = {};
        $scope.Evento = {
            Nombres: "",
            Documento: "",
            Telefono: "",
            DepartamentoId: 11,
            MunicipioId: "",
            Procedimiento: "",
            Comentario: "",
            TipoEvento: "Control Prenatal",
            TipoTransporte: "Particular",
            FechaParto: "",
            Detalles: [],
            Acompanante: false,
            MaternaId: null,
            CreatedBy: $rootScope.username.NombreCompleto
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Botones">
        $scope.EliminarEvento = (EventoId) => {
            swal({
                title: "¿Deseas ELIMINAR este evento?",
                text: "Nota: Este paso no se puede deshacer!",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "ELIMINAR!",
                cancelButtonText: "Cancelar!",
                closeOnConfirm: false,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {
                    let obj = {EventoId: EventoId};
                    TmEventoService.deleteTmEvento(obj).then(function (d) {
                        if (typeof d.data !== 'string') {
                            swal("Enhorabuena!", "Se ha ELIMINADO este evento.", "success");
                            GetEventos();
                        } else {
                            swal("Error", d.data, "error");
                        }
                    });
                }
            });
        };
        $scope.BuscarEventos = () => {
            GetEventos();
        };
        $scope.ConsultarTarifaByMunicipio = () => {
            GetTarifaByMunicipio();
        };
        $scope.Reset = () => {
            Reset();
        };
        $scope.Imprimir = () => {
            $scope.Impresion = true;
            printDiv();
        };
        $scope.ShowEvento = function (EventoId) {
            $scope.EventosAnteriores = [];
            $scope.UEvento = [];
            TmEventoService.getEventoByEventoId(EventoId).then(function (e) {
                $scope.UEvento = e.data;
                TmEventoService.getEventosByMaternaIdMenosEste($scope.UEvento.MaternaId, $scope.UEvento.EventoId).then((c) => {
                    $scope.EventosAnteriores = c.data;
                    $('#UpdateEventoModal').modal('show');
                });
            });
        };
        $scope.CrearEventoModal = function () {
            $('#CrearEventoModal').modal('show');
        };
        $scope.GuardarEvento = function () {
            if(!$scope.CargandoBandera){
                var obj = {
                    Evento: JSON.stringify($scope.Evento)
                };
                if (!$scope.Datos.$valid) {
                    angular.element("[name='" + $scope.Datos.$name + "']").find('.ng-invalid:visible:first').focus();
                } else if ($scope.Evento.Detalles.length === 0 && $scope.Evento.TipoEvento != "Control Prenatal") {
                    swal("Error", 'Debe seleccionar una tarifa.', "error");
                } else {
                    $scope.CargandoBandera = true;
                    TmEventoService.postTmEvento(obj).then(function (d) {
                        if (typeof d.data != "string") {
                            swal("Enhorabuena", "Se ha guardado el evento con exito", "success");
                            $('#CrearEventoModal').modal('hide');
                            GetEventos();
                            Reset();
                        } else {
                            swal("Error", d.data, "error");
                        }
                        $scope.CargandoBandera = false;
                    }, () => {
                        $scope.CargandoBandera = false;
                    });
                } 
            }
        };

        $scope.GetMaternaByDocumento = function () {
            GetMaternaByDocumento();
        };
        $scope.ChangeTransporte = () => {
            $scope.Evento.Detalles = [];
            if ($scope.Evento.Documento != '' && $scope.Evento.TipoTransporte == 'Particular') {
                GetTarifaByMaterna();
            }
        };
        $scope.GetTarifa = function () {
            var Color = getRandomColor();
            TarifaService.getTarifaByTarifaId($scope.TarifaId).then(function (t) {
                if (typeof t.data != "string" && t.data.length > 0) {
                    for (var i in t.data) {
                        t.data[i].Color = Color;
                    }
                    $scope.Evento.Detalles = $scope.Evento.Detalles.concat(t.data);
                    $scope.TarifaId = "";
                    if ($scope.Evento.Acompanante) {
                        for (var i in $scope.Evento.Detalles) {
                            $scope.Evento.Detalles[i].PrecioMaterna = angular.copy($scope.Evento.Detalles[i].Precio);
                            $scope.Evento.Detalles[i].PrecioAcompanante = angular.copy($scope.Evento.Detalles[i].Precio);
                            if ($scope.Evento.Detalles[i].Nombre !== "TAXI" && !$scope.Evento.Detalles[i].Multiplicado) {
                                $scope.Evento.Detalles[i].Precio = $scope.Evento.Detalles[i].Precio * 2;
                                $scope.Evento.Detalles[i].Multiplicado = true;
                            }
                        }
                    }
                }
            });
        };
        $scope.setAcompanante = function () {
            $scope.Evento.Acompanante = $scope.Evento.Acompanante ? false : true;
            if ($scope.Evento.Acompanante) {
                for (var i in $scope.Evento.Detalles) {
                    $scope.Evento.Detalles[i].PrecioMaterna = angular.copy($scope.Evento.Detalles[i].Precio);
                    $scope.Evento.Detalles[i].PrecioAcompanante = angular.copy($scope.Evento.Detalles[i].Precio);
                    if ($scope.Evento.Detalles[i].Nombre !== "TAXI" && !$scope.Evento.Detalles[i].Multiplicado) {
                        $scope.Evento.Detalles[i].PrecioViejo = angular.copy($scope.Evento.Detalles[i].Precio);
                        $scope.Evento.Detalles[i].Precio = $scope.Evento.Detalles[i].PrecioMaterna + $scope.Evento.Detalles[i].PrecioAcompanante;
                        $scope.Evento.Detalles[i].Multiplicado = true;
                    }
                }
            } else {
                for (var i in $scope.Evento.Detalles) {
                    if ($scope.Evento.Detalles[i].Nombre !== "TAXI" && $scope.Evento.Detalles[i].Multiplicado) {
                        $scope.Evento.Detalles[i].Precio = $scope.Evento.Detalles[i].PrecioViejo;
                        $scope.Evento.Detalles[i].Multiplicado = false;
                    }
                }
            }
        };

        $scope.CambiarPrecio = (d) => {
            d.Precio = d.PrecioMaterna + d.PrecioAcompanante;
        };
        $scope.RemoverDetalle = function (i) {
            $scope.Evento.Detalles.splice(i, 1);
        };
        $scope.ChangeDepartamento = function () {
            GetMunicipioByDepartamento();
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="consultas">
        function GetTarifas() {
            $scope.cargado = false;
            TarifaService.getTarifas().then(function (c) {
                $scope.simpleTableOptions = {
                    data: [],
                    aoColumns: [
                        {mData: 'TarifaId'},
                        {mData: 'Nombre'},
                        {mData: 'OrigenId'},
                        {mData: 'Origen'},
                        {mData: 'DestinoId'},
                        {mData: 'Destino'},
                        {mData: 'Precio'},
                        {mData: 'OtroId'},
                        {mData: 'PrecioOtro'},
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
                $scope.simpleTableOptions.data = SetFormat(c.data.data);
                $scope.cargado = true;
            });
        }
        function GetMaternaByDocumento() {
            MaternaService.GetMaternaByDocumento($scope.Evento.Documento).then(function (d) {
                if (typeof d.data != "string" && d.data.length > 0) {
                    if (d.data[0].HaveParto > 0) {
                        swal("Atención!", 'Esta materna ya tiene un registro de Parto o Parto Externo.', "warning");
                    }
                    $scope.Evento.Nombres = d.data[0].Nombres;
                    $scope.Evento.Telefono = d.data[0].Telefono;
                    $scope.Evento.MunicipioId = d.data[0].MunicipioId;
                    $scope.Evento.DepartamentoId = d.data[0].DepartamentoId;
                    $scope.Evento.MaternaId = d.data[0].MaternaId;
                    $scope.Evento.FechaParto = d.data[0].FechaProbableParto;

                } else {
                    $scope.Evento.Nombres = "";
                    $scope.Evento.Telefono = "";
                    $scope.Evento.Comentario = "";
                    $scope.Evento.Procedimiento = "";
                    $scope.Evento.MunicipioId = "";
                    $scope.Evento.DepartamentoId = 11;
                    $scope.Evento.MaternaId = null;
                }
            }).then(() => {
                if ($scope.Evento.TipoTransporte == 'Particular') {
                    GetTarifaByMaterna();
                }
            });
        }
        function GetDepartamentos() {
            DepartamentoService.GetDepartamentos().then(function (d) {
                console.log(d.data);
                $scope.Departamentos = d.data;
            }).then(function () {
                GetMunicipioByDepartamento();
            });
        }
        function GetMunicipioByDepartamento() {
            MunicipioService.GetMunicipiosByDepartamentoId($scope.Evento.DepartamentoId).then(function (m) {
                console.log(m.data);
                $scope.Municipios = m.data;
            });
        }
        
        function GetTarifaByMaterna() {
            TarifaService.getTarifaByMaterna($scope.Evento.Documento).then(function (t) {
                $scope.Evento.Detalles = t.data;
            });
        }
        function GetTarifaByMunicipio() {
            TarifaService.getTarifaByMunicipio($scope.Evento.MunicipioId).then(function (t) {
                $scope.Evento.Detalles = t.data;
            });
        }
        function getRandomColor() {
            var letters = 'BCDEF'.split('');
            var color = '#';
            for (var i = 0; i < 6; i++) {
                color += letters[Math.floor(Math.random() * letters.length)];
            }
            return color;
        }
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Helpers">
        function Reset() {
            $scope.UEvento = {
                Nombres: "",
                Documento: "",
                Telefono: "",
                DepartamentoId: 11,
                MunicipioId: "",
                Procedimiento: "",
                Comentario: "",
                FechaParto: "",
                TipoEvento: "Control Prenatal",
                TipoTransporte: "Particular",
                Detalles: [],
                Acompanante: false,
                MaternaId: null,
                ModifiedBy: $rootScope.username.NombreCompleto
            };
            $scope.Evento = {
                Nombres: "",
                Documento: "",
                Telefono: "",
                DepartamentoId: 11,
                MunicipioId: "",
                Procedimiento: "",
                Comentario: "",
                TipoEvento: "Control Prenatal",
                TipoTransporte: "Particular",
                FechaParto: "",
                Detalles: [],
                Acompanante: false,
                MaternaId: null,
                CreatedBy: $rootScope.username.NombreCompleto
            };
        }
        function toDate(dateStr) {
            var parts = dateStr.split("-");
            return new Date(parts[0], parts[1] - 1, parts[2]);
        }

        function toConsultar(dateStr) {
            var parts = dateStr.split("-");
            return parts[0] + "-" + parts[1] + "-" + parts[2];
        }
        function printDiv() {
            $("#impresion_evento").print({
                globalStyles: true,
                mediaPrint: false,
                stylesheet: null,
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
                $scope.Impresion = false;
                $scope.$apply();
            }, 1000);
        }
        function SetFormat(lst) {
            
            for (var i in lst) {
                lst[i].Opciones = '<a class="btn  btn-primary btn-xs icon-only white" onclick=\"angular.element(this).scope().ShowEvento(' + i + ')\"/><i class="fa fa-eye"></i></a>';
                lst[i].OtroId = lst[i].OtroId ? 'Taxi' : '-';
                lst[i].PrecioOtro = lst[i].OtroId ?  lst[i].PrecioOtro : '-';
            }
            return lst;
        }
        //</editor-fold>
        function _init() {
//            GetDepartamentos();
            GetTarifas();
        }
        _init();
    }]);


