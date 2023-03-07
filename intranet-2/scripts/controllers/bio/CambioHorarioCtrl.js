app.controller('CambioHorarioCtrl', ["$scope", "$rootScope", "$state", "$crypto", "$filter", "PersonaService", "SedeService",
    function ($scope, $rootScope, $state, $crypto, $filter, PersonaService, SedeService) {
        //<editor-fold defaultstate="collapsed" desc="Variables">
        $scope.BanderaVerHorario = false;
        $scope.BanderaCambiarHorario = false;
        $scope.Sedes = [];
        $scope.SedeId = 1;
        $scope.Mes = moment().format('M');
        $scope.Year = moment().format('YYYY');
        $scope.SolicitudHorarios = [];
        $scope.cargado = false;
        $scope.Tabla = [];
        $scope.simpleTableOptions = {};
        $scope.SolicitudHorario = {};
        var no_leidos = function (data, type, full) {
            if (full.IsRevisado == 0) {
                return `<strong>${data}</strong>`;
            } else {
                return data;
            }
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Eventos">
        $scope.RevisarModal = (i) => {
            $scope.SolicitudHorario = $scope.simpleTableOptions.data[i];
            PersonaService.getFileText($scope.SolicitudHorario.JefeId).then((d) => {
                $scope.Tabla = d.data;
                $scope.UltimoDiaMes = getDaysInMonth($scope.Mes, $scope.Year);
                $scope.BanderaVerHorario = true;
                $scope.$apply();
            });
        };
        $scope.ExportarHorario = () => {
            $("#TablaHorario").tableExport({
                position: "top", // (top, bottom), position of the caption element relative to table, (default: 'bottom')
            });
        };
        $scope.AutorizarHorario = () => {
            if(!$scope.BanderaCambiarHorario){
                swal({
                    title: `¿Deseas autorizar este horario?`,
                    text: "",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-success",
                    confirmButtonText: "Autorizar",
                    cancelButtonText: "Cancelar",
                    closeOnConfirm: true,
                    closeOnCancel: true
                }, function (isConfirm) {
                    if (isConfirm && !$scope.BanderaCambiarHorario) {
                        $scope.SolicitudHorario.Tabla = $scope.Tabla;
                        $scope.SolicitudHorario.CreatedBy = $rootScope.username.NombreCompleto;
                        let obj = {
                            SolicitudHorario: JSON.stringify([$scope.SolicitudHorario])
                        };
                        $scope.BanderaCambiarHorario = true;
                        PersonaService.putPersona(obj).then((d) => {
                            $scope.BanderaCambiarHorario = false;
                            console.log(d.data);
                            if (typeof d.data !== 'string') {
                                swal("Enhorabuena", "Se han actulizado los datos con exito", "success");
                                $scope.BanderaVerHorario = false;
                                GetSolicitudHorarios();
                            } else {
                                swal("Error", d.data, "error");
                            }
                        }).then(() => {
                            $scope.BanderaCambiarHorario = false;
                        });
                    }
                });
            }
        };
        $scope.VerColaboradores = (PersonaId) => {
            var encrypted = $crypto.encrypt(PersonaId, 'Franklin Ospino');
            $state.go("bio.listado_personal", {PersonaId: encrypted});
        };
        $scope.getCambios = () => {
            GetSolicitudHorarios();
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Consultas">
        function GetSede() {
            SedeService.getAllSede().then(function (c) {
                $scope.Sedes = $filter("orderBy")(c.data, "Nombre");
                $scope.SedeId = $scope.SedeId === 1 ? $scope.Sedes[0].SedeId : $scope.SedeId;
            }).then(() => {
                GetSolicitudHorarios();
            });
        }
        function GetSolicitudHorarios() {
            $scope.cargado = false;
            $scope.simpleTableOptions.data = [];
            PersonaService.getCambioHorario($scope.SedeId, $scope.Mes, $scope.Year).then((d) => {
                $scope.simpleTableOptions = {
                    data: [],
                    aoColumns: [
                        {mData: 'SolicitudHorarioId', "mRender": no_leidos},
                        {mData: 'NombreJefe', "mRender": no_leidos},
                        {mData: 'CreatedAt', "mRender": no_leidos},
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
                $scope.simpleTableOptions.data = SetFormat(d.data);
                $scope.cargado = true;
            });
        }
        $scope.ImprimirEstadisticas = () => {
            printDiv("#TablaEstadisticas");
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Helpers">
        function getDaysInMonth(m, y) {
            return moment(new Date(y, m - 1, 1)).endOf('month').format('DD');
        }
        function getText(JefeId) {
            // read text from URL location
            var request = new XMLHttpRequest();
            request.open('GET', `/Polivalente/horarios_temp/${JefeId}.txt`, true);
            request.send(null);
            request.onreadystatechange = function () {
                if (request.readyState === 4 && request.status === 200) {
                    var type = request.getResponseHeader('Content-Type');
                    if (type.indexOf("text") !== 1) {
                        return request.responseText;
                    }
                }
            };
        }
        function toDate(dateStr) {
            var parts = dateStr.split("-");
            return new Date(parts[0], parts[1] - 1, parts[2]);
        }
        function printDiv(id) {
            $(id).print({
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
                $scope.ToPrint = false;
                $scope.$apply();
            }, 1000);
        }
        function SetFormat(lst) {
            for (var i in lst) {
                lst[i].Opciones = `<a class="btn btn-warning btn-xs icon-only white" data-toggle="tooltip" title="Ver colaboradores"  onclick=\"angular.element(this).scope().VerColaboradores('${lst[i].UsuarioIntranetId}')\"><i class="fa fa-list-ol"></i></a>`;
                if (lst[i].IsRevisado != 1) {
                    lst[i].Opciones += `<a class="btn btn-primary btn-xs icon-only white" data-toggle="tooltip" title="Ver Horario"  onclick=\"angular.element(this).scope().RevisarModal('${i}')\"><i class="fa fa-eye"></i></a>`;
                }
            }
            return lst;
        }
        //</editor-fold>
        function __init__() {
            GetSede();
        }
        __init__();
    }]);

