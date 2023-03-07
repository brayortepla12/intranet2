app.controller('MisHistoriasTHCCtrl', ["$scope", "$rootScope", "HistoriaService", "GrupoThcService", "NotasService",
    function ($scope, $rootScope, HistoriaService, GrupoThcService, NotasService) {
        //<editor-fold defaultstate="collapsed" desc="Variables">
        $scope.UsuarioId = $rootScope.username.UserId;
        $scope.Tab = 1;
        $scope.HTab = 1;
        $scope.TrasladarH = false;
        $scope.RecibirH = false;
        $scope.Historia = null;
        $scope.MisHistorias = [];
        $scope.HistoriasPendientes = [];
        $scope.HistoriasPendientesName = [];
        $scope.IsListHP = false;
        $scope.Historias = [];
        $scope.HistoriasPP = [];
        $scope.Grupos = [];
        $scope.Usuarios = [];
        $scope.Notas = [];
        $scope.TrazabilidadH = [];
        $scope.Nota = {
            UsuarioId: $scope.UsuarioId,
            Observacion: "",
//            GrupoId: null,
//            HistoriaId: null,
//            Fecha
            CreatedBy: $rootScope.username.NombreCompleto
        };
        $scope.Traslado = {
            GrupoId: null,
            UsuarioRecibeId: null,
            UsuarioEntregaId: $scope.UsuarioId,
            Historias: [],
            CreatedBy: $rootScope.username.NombreCompleto
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Botones">
        $scope.GetTrazabilidad = () => {
            HistoriaService.GetTrazabilidadByHistoriaId($scope.Historia.HistoriaId).then((d) => {
                $scope.TrazabilidadH = d.data;
            });
        };
        $scope.CrearNota = () => {
            if ($scope.Nota.Observacion.length <= 2) {
                swal("Error", "Debe ingresar una nota", "error");
            } else {
                $scope.Nota.HistoriaId = $scope.Historia.HistoriaId;
                let obj = {
                    Nota: JSON.stringify($scope.Nota)
                };
                NotasService.postNotas(obj).then((d) => {
                    console.log(d.data);
                    if (typeof d.data != "string") {
                        swal("Enhorabuena", "Se han guardado las historias con exito", "success");
                        GetNotas();
                        $scope.Nota.Observacion = "";
                    } else {
                        swal("Error", d.data, "error");
                    }
                });
            }

        };
        $scope.RecibirHistorias = () => {
            let obj = {
                data_pr: JSON.stringify({
                    HistoriaPR: $scope.HistoriasPP,
                    UsuarioRecibeId: $rootScope.username.UserId,
                    NombreUsuario: $rootScope.username.NombreCompleto,
                    IsRecibido: 1,
                    CreatedBy: $rootScope.username.NombreCompleto,
                    Estado: "Recibido",
                    UsuarioId: $rootScope.username.UserId,
                })
            };
            HistoriaService.putHistoria(obj).then(function (d) {
                if (typeof d.data != "string") {
                    swal("Enhorabuena", "Se han guardado las historias con exito", "success");
                    GetHistoriasPendientes();
                    $scope.Reset();
                } else {
                    swal("Error", d.data, "error");
                }
            }, function (e) {
                swal("Error", e, "error");
            });
        };
        $scope.TrasladarHistorias = () => {
            if ($scope.Traslado.GrupoId == null) {
                swal("Error", 'Debe seleccionar un grupo.', "error");
            } else if ($scope.Traslado.UsuarioRecibeId == null) {
                swal("Error", 'Debe seleccionar un usuario.', "error");
            } else if ($scope.Historias.length == 0) {
                swal("Error", 'Debe seleccionar minimo una historia.', "error");
            } else {
                $scope.Traslado.Historias = $scope.Historias;
                $scope.Traslado.Estado = "Entrega";
                $scope.Traslado.NombreUsuario = $rootScope.username.NombreCompleto;
                $scope.Traslado.UsuarioId = $rootScope.username.UserId;
//                for (let i in $scope.Usuarios) {
//                    if($scope.Usuarios[i].UsuarioId === $scope.Traslado.UsuarioRecibeId){
//                        $scope.Traslado.
//                    }
//                }
                var obj = {
                    Traslado: JSON.stringify([$scope.Traslado])
                };
                HistoriaService.postHistoria(obj).then(function (d) {
                    if (typeof d.data != "string") {
                        swal("Enhorabuena", "Se han guardado las historias con exito", "success");
                        $scope.Reset();
                    } else {
                        swal("Error", d.data, "error");
                    }
                }, function (e) {
                    swal("Error", e, "error");
                });
            }
        };
        $scope.AddHistoria = (o) => {
            let pos = isInArray(o.NOADMISION, $scope.Historias);
            if (pos === -1) {
                $scope.Historias.push(o);
                o.Selected = true;
            } else {
                o.Selected = false;
                $scope.Historias.splice(pos, 1);
            }
        };
        $scope.RecibirHistoria = (o) => {
            let pos = isInArray(o.NOADMISION, $scope.HistoriasPP);
            if (pos === -1) {
                $scope.HistoriasPP.push(o);
                o.Selected = true;
            } else {
                o.Selected = false;
                $scope.HistoriasPP.splice(pos, 1);
            }
        };
        $scope.setHTab = (d) => {
            $scope.HTab = d;
        };
        $scope.VerHistoria = (HistoriaId) => {
            
            HistoriaService.GetHistoriaById(HistoriaId).then((d) => {
                $scope.Historia = d.data;
                GetNotas();
                console.log($scope.HTab)
                if($scope.HTab === 2){
                    $scope.GetTrazabilidad();
                }
            });
        };
        $scope.ChangeGrupo = () => {
            $scope.Traslado.UsuarioRecibeId = null;
            GetUsuariosByGrupoId();
        };
        $scope.Reset = () => {
            $scope.TrasladarH = false;
            $scope.MisHistorias = [];
            $scope.Historias = [];
            $scope.HistoriasPP = [];
            $scope.Usuarios = [];
            $scope.items = Array.from(Array(30), (x, index) => {
                return {Item: index + 1};
            });
            $scope.Traslado = {
                GrupoId: null,
                UsuarioRecibeId: null,
                UsuarioEntregaId: $scope.UsuarioId,
                Historias: [],
                CreatedBy: $rootScope.username.NombreCompleto
            };
            GetMisHistorias();
        };
        $scope.SetbanderaTrasladar = () => {
            $scope.TrasladarH = $scope.TrasladarH ? false : true;
        };
        $scope.SetbanderaRecibir = () => {
            $scope.RecibirH = $scope.RecibirH ? false : true;
        };
        $scope.ViewListHistoriasPendientes = () => {
            if (!$scope.IsListHP) {
                $scope.IsListHP = true;
            }
        };
        $scope.ViewNameHistoriasPendientes = () => {
            if ($scope.IsListHP) {
                $scope.IsListHP = false;
                $scope.HistoriasPendientesName = _.mapValues(_.groupBy($scope.HistoriasPendientes, 'NombreUsuarioEntrega'));
//                ,clist => clist.map(o => _.omit(o, 'NombreUsuarioEntrega'))
//                console.log($scope.HistoriasPendientesName);
            }
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="consultas">
        function GetNotas() {
            NotasService.GetNotas($scope.Historia.HistoriaId).then((d) => {
                $scope.Notas = d.data;
            });
        }
        function GetGrupos() {
            GrupoThcService.getGrupos().then((d) => {
                $scope.Grupos = d.data;
            });
        }
        function GetUsuariosByGrupoId() {
            GrupoThcService.getUsuariosByGrupoId($scope.Traslado.GrupoId).then((d) => {
                $scope.Usuarios = d.data;
            });
        }
        function GetMisHistorias() {
            HistoriaService.GetMH($rootScope.username.UserId).then((d) => {
                if (typeof d.data != "string") {
                    $scope.MisHistorias = d.data;
                    $scope.MisHistorias = $scope.MisHistorias.map((o, index) => {
                        o.Item = index + 1;
                        return o;
                    });
                }
            });
        }

        function GetHistoriasPendientes() {
            HistoriaService.GetMHPR($rootScope.username.UserId).then((d) => {
                if (typeof d.data != "string") {
                    $scope.HistoriasPendientes = d.data;
                    $scope.HistoriasPendientes = $scope.HistoriasPendientes.map((o, index) => {
                        o.Item = index + 1;
                        return o;
                    });
                    $scope.HistoriasPendientesName = _.mapValues(_.groupBy($scope.HistoriasPendientes, 'Paquete'));
                }
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
            GetGrupos();
            GetMisHistorias();
            GetHistoriasPendientes();
        }
        _init();
    }]);