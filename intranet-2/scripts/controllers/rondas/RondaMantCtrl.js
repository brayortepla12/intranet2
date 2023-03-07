app.controller('RondaMantCtrl', ["$scope", "$rootScope", "$filter", "RondaMantService", "SedeService", "ServicioService", "UsuarioService",
    function ($scope, $rootScope, $filter, RondaMantService, SedeService, ServicioService, UsuarioService) {
        var vm = $scope;
        //<editor-fold defaultstate="collapsed" desc="Variables">
        vm.HASRONDAS = false;
        vm.NEWRONDA = false;
        vm.VIEWRONDA = false;
        vm.PREFIJO = "pol";
        vm.Usuarios = [];
        vm.RondaMant = {
            SedeId: "",
            Fecha: moment().format("YYYY-MM-DD"), //FECHA Y HORA SE GENERAN
            Hora: moment().format("HH:mm:ss"),
            DetalleRonda: [],
            Responsable: $rootScope.username.NombreCompleto,
            CreatedBy: $rootScope.username.NombreCompleto
        };
        vm.Item = {
            ServicioId: "",
            Descripcion: "",
            TecnicoResponsable: "",
            CoordinadorId: ""
        };
        vm.Sedes = [];
        vm.Servicios = [];
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Botones-Eventos">
        vm.VerRondaMant = (i) => {
            vm.RondaMant = vm.simpleTableOptions.data[i];
            vm.RondaMant.DetalleRonda = [];
            RondaMantService.GetRondaMantById(vm.RondaMant.RondaMantId, vm.PREFIJO).then((d) => {
                vm.RondaMant.DetalleRonda = d.data;
                vm.VIEWRONDA = true;
            }).then(() => {
                GetSede();
            }).then(() => {
                GetServicio();
            });
        };
        vm.CrearRonda = () => {
            let obj = {
                RondaMant: JSON.stringify(vm.RondaMant),
                PREFIJO: vm.PREFIJO
            };
            RondaMantService.postRondaMant(obj).then((d) => {
                if (typeof d.data !== "string") {
                    GetRondaMants();
                    swal("Enhorabuena", "Se ha guardado los datos con exito", "success");
                    vm.NEWRONDA = false;
                    vm.RondaMant = {
                        SedeId: "--",
                        Fecha: moment().format("YYYY-MM-DD"), //FECHA Y HORA SE GENERAN
                        Hora: moment().format("HH:mm:ss"),
                        DetalleRonda: [],
                        Responsable: $rootScope.username.NombreCompleto,
                        CreatedBy: $rootScope.username.NombreCompleto
                    };
                } else {
                    swal("Error", d.data, "error");
                }
            });
        };
        vm.ActualizarRonda = () => {
            let RondaMant = angular.copy(vm.RondaMant);
            RondaMant.DetalleRonda = JSON.stringify(RondaMant.DetalleRonda);
            RondaMant.ModifiedBy = $rootScope.username.NombreCompleto;
            let obj = {
                RondaMant: JSON.stringify(RondaMant),
                PREFIJO: vm.PREFIJO
            };
            RondaMantService.putRondaMant(obj).then((d) => {
                if (typeof d.data !== "string") {
                    GetRondaMants();
                    swal("Enhorabuena", "Se ha actualizado los datos con exito", "success");
                    vm.NEWRONDA = false;
                    vm.VIEWRONDA = false;
                    vm.RondaMant = {
                        SedeId: "--",
                        Fecha: moment().format("YYYY-MM-DD"), //FECHA Y HORA SE GENERAN
                        Hora: moment().format("HH:mm:ss"),
                        DetalleRonda: [],
                        Responsable: $rootScope.username.NombreCompleto,
                        CreatedBy: $rootScope.username.NombreCompleto
                    };
                } else {
                    swal("Error", d.data, "error");
                }
            });
        };
        vm.NuevaRonda = () => {
            vm.HASRONDAS = false;
            GetSede();
            vm.NEWRONDA = true;
        };
        vm.ChangeSede = () => {
            GetServicio();
        };
        vm.ChangeServicio = () => {
            GetCoordinadores();
        };
        vm.ChangeUsuario = () => {
            for (let i in vm.Usuarios) {
                if (vm.Item.CoordinadorId == vm.Usuarios[i].UsuarioId) {
                    vm.Item.NombreCoordinador = vm.Usuarios[i].NombreCompleto;
                }
            }
        };
        vm.Atras = () => {
            vm.NEWRONDA = false;
            vm.VIEWRONDA = false;
            vm.HASRONDAS = true;
        };
        vm.AddItemToRonda = () => {
            if (vm.Item.ServicioId !== "" && vm.Item.Descripcion !== "" && vm.Item.TecnicoResponsable !== "") {
                vm.RondaMant.DetalleRonda.push(angular.copy(vm.Item));
                vm.Item.Descripcion = "";
                vm.Item.TecnicoResponsable = "";
            } else {
                swal("Error", "Debes llenar completamente los campos para poder añadir detalles a la ronda", "error");
            }
        };
        vm.SetCumplimiento = (i, value) => {
            vm.RondaMant.DetalleRonda[i].Cumplimiento =  vm.RondaMant.DetalleRonda[i].Cumplimiento === value ? null : value;
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="consultas">
        function GetServicio() {
            ServicioService.getServicioBySedeWithTA(vm.RondaMant.SedeId, $rootScope.username.UserId, vm.PREFIJO).then(function (c) {
                vm.Servicios = c.data;
                vm.Item.ServicioId = "";
            });
        }
        function GetSede() {
            if (vm.Sedes.length === 0) {
                SedeService.getAllSedeByUserId_TA($rootScope.username.UserId, vm.PREFIJO).then(function (c) {
                    vm.Sedes = c.data;
                    if (c.data.length === 1) {
                        vm.RondaMant.SedeId = c.data[0].SedeId;
                        vm.ChangeSede();
                    }
                });
            } else {
                vm.RondaMant.SedeId = vm.Sedes[0].SedeId;
            }
        }
        function GetCoordinadores() {
            vm.Usuarios = [];
            UsuarioService.GetALLusuariosByServicio(vm.Item.ServicioId).then(function (u) {
                vm.Usuarios = $filter("orderBy")(u.data, "NombreCompleto");
                for (let i in vm.Servicios) {
                    if (vm.Item.ServicioId == vm.Servicios[i].ServicioId) {
                        vm.Item.Servicio = vm.Servicios[i].Nombre;
                    }
                }
            });
        }
        function GetRondaMants() {
            vm.HASRONDAS = false;
            RondaMantService.GetAll($rootScope.username.UserId, vm.PREFIJO).then(function (c) {
                vm.simpleTableOptions = {
                    data: [],
                    aoColumns: [
                        {mData: 'RondaMantId'},
                        {mData: 'Sede'},
                        {mData: 'Fecha'},
                        {mData: 'Hora'},
                        {mData: 'Responsable'},
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
                vm.HASRONDAS = true;

            });
        }
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Helpers">
        function SetFormat(lst) {
            for (var i in lst) {
                lst[i].Opciones = '<a class="btn  btn-success btn-xs icon-only white" onclick=\"angular.element(this).scope().VerRondaMant(' + i + ')\" target="_blank"><i class="fa fa-file-text-o"></i></a>';
            }
            return lst;
        }
        //</editor-fold>
        function _init() {
            GetRondaMants();
        }
        _init();
    }]);
