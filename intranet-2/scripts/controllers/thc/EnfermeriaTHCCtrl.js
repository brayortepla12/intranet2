app.controller('EnfermeriaTHCCtrl', ["$scope", "$rootScope", "HistoriaService", "GrupoThcService", "SectorService",
    function ($scope, $rootScope, HistoriaService, GrupoThcService, SectorService) {
        //<editor-fold defaultstate="collapsed" desc="Variables">
        let vm = $scope;
        vm.Sectores = [];
        vm.NoAdmision = "";
        vm.Historia = null;
        vm.Grupos = [];
        vm.Usuarios = [];
        vm.items = Array.from(Array(30), (x, index) => {
            return {Item: index + 1};
        });
        vm.Entrega = {
            GrupoId: null,
            OGrupoId: 6,
            UsuarioRecibeId: null,
            IsEntrega: 1,
            Historias: [],
            UsuarioEntregaId: $rootScope.username.UserId,
            PersonaEntregaId: $rootScope.username.PersonaId,
            NombreUsuarioEntrega: $rootScope.username.NombreCompleto,
            CreatedBy: $rootScope.username.NombreCompleto,
            Estado: "Entrega Enfermeria"
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Botones">
        vm.EntregarHistorias = () => {
            let hs = vm.items.map(function (e) {
                if (e.NOADMISION) {
                    return e;
                }
            });
            if (vm.Entrega.GrupoId == null) {
                swal("Error", 'Debe seleccionar un grupo.', "error");
            } else if (vm.Entrega.UsuarioId == null) {
                swal("Error", 'Debe seleccionar un usuario.', "error");
            } else if (hs.length == 0) {
                swal("Error", 'Debe seleccionar minimo una historia.', "error");
            } else {
                vm.Entrega.Historias = vm.items;
                vm.Entrega.GrupoId = 6;
                var obj = {
                    Entrega: JSON.stringify([vm.Entrega])
                };
                HistoriaService.postHistoria(obj).then(function (d) {
                    if (typeof d.data != "string") {
                        swal("Enhorabuena", "Se han guardado las historias con exito", "success");
                        vm.Reset();
                    } else {
                        swal("Error", d.data, "error");
                    }
                }, function (e) {
                    swal("Error", e, "error");
                });
            }
        };
        vm.ConsultarHistoria = () => {
            if (vm.NoAdmision) {
                vm.Historia = null;
                HistoriaService.getHistoriaKrysByNoAdmision(vm.NoAdmision.originalObject.NOADMISION).then((d) => {
                    if (typeof d.data != "string") {
                        if (d.data.length > 0) {
                            vm.Historia = d.data[0];
                        }
                    } else {
                        swal("Error", d.data, "error");
                    }
                });
            }
        };

        vm.AddHistoria = () => {
            let obj = angular.copy(vm.Historia);
            let pos = isInArray(obj.NOADMISION, vm.items);
            if (pos === -1) {
                for (let i = 0; i < vm.items.length; i++) {
                    if (!vm.items[i].NOADMISION) {
                        obj.Item = i + 1;
                        vm.items[i] = obj;
                        break;
                    }
                }
            } else {
                swal("Error", `Ya existe esta admisión en la lista, es la número ${pos + 1}.`, "error");
            }
            vm.Historia = null;
            $("#historia_input_value").focus();
        };
        vm.EliminarHistoria = (i) => {
            vm.items[i] = {Item: i + 1};
        };
        vm.ChangeGrupo = () => {
            vm.Entrega.UsuarioId = null;
            GetUsuariosByGrupoId();
        };
        vm.Reset = () => {
            vm.Historia = null;
            vm.Usuarios = [];
            vm.items = Array.from(Array(30), (x, index) => {
                return {Item: index + 1};
            });
            vm.Entrega = {
                GrupoId: null,
                OGrupoId: 6,
                UsuarioRecibeId: null,
                IsEntrega: 1,
                Historias: [],
                UsuarioEntregaId: $rootScope.username.UserId,
                PersonaEntregaId: $rootScope.username.PersonaId,
                NombreUsuarioEntrega: $rootScope.username.NombreCompleto,
                CreatedBy: $rootScope.username.NombreCompleto,
                Estado: "Entrega Enfermeria"
            };
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="consultas">
        function GetGrupos() {
            GrupoThcService.getGrupos().then((d) => {
                vm.Grupos = d.data;
            });
        }
        function GetUsuariosByGrupoId() {
            GrupoThcService.getUsuariosByGrupoId(vm.Entrega.GrupoId).then((d) => {
                vm.Usuarios = d.data;
            });
        }
        function IsInEnfermeria() {
            GrupoThcService.IsInEnfermeria($rootScope.username.UserId).then((d) => {
                if(d.data){
                    GetGrupos();
                }else{
                    swal("Error", `Este usuario NO SE ENCUENTRA VINCULADO al grupo de enfermeria`, "error");
                }
            });
        }
        function GetSectores() {
            vm.Sectores = [];
            SectorService.getSectores().then(function (c) {
                vm.Sectores = c.data;
            });
        }
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Helpers">
        function isInArray(NOADMISION, array) {
            return array.map(function (e) {
                return e.NOADMISION;
            }).indexOf(NOADMISION);
        }
        //</editor-fold>
        function _init() {
            IsInEnfermeria();
//            GetSectores();
        }
        _init();
    }]);