
'use strict';
app.controller('RondaSistemaServicioCtrl', ["$scope", "$rootScope", "RondaSistemaServicioService", "SedeService", "ServicioService", "UsuarioService", "SesionService", "$filter",
    function ($scope, $rootScope, RondaSistemaServicioService, SedeService, ServicioService, UsuarioService, SesionService, $filter) {
        //<editor-fold defaultstate="collapsed" desc="inicializar variables">
        $scope.Rondas = [];
        $scope.Sedes = [];
        $scope.ServiciosLST = [];
        $scope.Servicios = [];
        $scope.Usuarios = [];
        $scope.UsuariosBackup = [];
        $scope.ServiciosAsignados = [];
        $scope.UsuariosAsignados = [];
        $scope.IsEdit = false;
        $scope.RondaSistemaServicio = {
            Nombre: "",
            ServiciosAsignados: [],
            UsuariosAsignados: [],
            SedeId: null,
            Tipo: "Impresora",
            CreatedBy: $rootScope.username.NombreUsuario
        };
        $scope.selected = {};
        // inicializamos las variables en _init()
        _init();

        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Consultas">
        function GetRondaSistemaServicio() {
            RondaSistemaServicioService.getAllRondaSistemaServicio().then(function (c) {
                console.log(c.data)
                $scope.Rondas = $filter("orderBy")(c.data, "-RondaId");
            });
        }
        function GetSede() {
            SedeService.getAllSede().then(function (c) {
                $scope.Sedes = $filter("orderBy")(c.data, "-SedeId");
                GetRondaSistemaServicio();
            });
        }
        function GetServicio() {
            ServicioService.getAllServicio().then(function (c) {
                $scope.ServiciosLST = Format($filter("orderBy")(c.data, "-ServicioId"));
            });
        }
        function GetUsuario() {
            UsuarioService.GetALLusuarios(SesionService.get("UserData_Polivalente").key, SesionService.get("UserData_Polivalente").Email).then(function (d) {
                $scope.Usuarios = $filter("orderBy")(d.data, "NombreCompleto");
                $scope.UsuariosBackup = angular.copy($filter("orderBy")(d.data, "NombreCompleto"));
            });
        }
        $scope.ChangeSede = function () {
            $scope.Servicios = $filter("filter")($scope.ServiciosLST, {SedeId: $scope.RondaSistemaServicio.SedeId});
            RevisarServicio_seleccionados();
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Botones guardar e imprimir">
        $scope.editObj = function(RondaId){
            console.log(RondaId);
            RondaSistemaServicioService.getRondaSistemaServicioByRondaId(RondaId).then(function(d){
                console.log(d.data);
                $scope.RondaSistemaServicio = d.data[0];
                $('#RondaSistemaServicioModal').modal('show');
                RevisarUsuario_seleccionados();
                $scope.IsEdit = true;
            });
        };
        
        $scope.GuardarRondaSistemaServicio = function () {
            if ($scope.RondaSistemaServicio.Nombre.length === 0) {
                swal("Hubo un Error", "Debe ingresar un nombre", "error");
            }else if($scope.RondaSistemaServicio.ServiciosAsignados.length === 0){
                swal("Hubo un Error", "Debe añadir minimo un servicio", "error");
            }else if ($scope.RondaSistemaServicio.UsuariosAsignados.length === 0) {
                swal("Hubo un Error", "Debe añadir minimo un usuario", "error");
            }else{
                var data = {
                    RondaSistemaServicio: JSON.stringify([$scope.RondaSistemaServicio])
                };
                console.log("solicitud");
                RondaSistemaServicioService.PostRondaSistemaServicio(data).then(function (d) {
                     console.log(d.data);
                    if (typeof d.data !== "object") {
                        swal("Hubo un Error", d.data, "error");
                    } else {
                        swal("Enhorabuena!!", "Se han guardado los datos satisfactoriamente", "success");
                        $scope.RondaSistemaServicio = {
                            Nombre: null,
                            ServiciosAsignados: [],
                            UsuariosAsignados: [],
                            SedeId: null,
                            Tipo: "Impresora",
                            CreatedBy: $rootScope.username.NombreUsuario
                        };
                        $('#RondaSistemaServicioModal').modal('hide');
                        GetRondaSistemaServicio();
                    }
                }, function (e) {
                    swal("Hubo un Error", e, "error");
                });
            }
        };
        
        $scope.ActualizarRondaSistemaServicio = function () {
            if ($scope.RondaSistemaServicio.Nombre.length === 0) {
                swal("Hubo un Error", "Debe ingresar un nombre", "error");
            }else if($scope.RondaSistemaServicio.ServiciosAsignados.length === 0){
                swal("Hubo un Error", "Debe añadir minimo un servicio", "error");
            }else if ($scope.RondaSistemaServicio.UsuariosAsignados.length === 0) {
                swal("Hubo un Error", "Debe añadir minimo un usuario", "error");
            }else{
                $scope.RondaSistemaServicio.ModifiedBy = $rootScope.username.NombreUsuario;
                var data = {
                    ID: $scope.RondaSistemaServicio.RondaId,
                    RondaSistemaServicio: JSON.stringify([$scope.RondaSistemaServicio])
                };
                RondaSistemaServicioService.PutRondaSistemaServicio(data).then(function (d) {
                     console.log(d.data);
//                    if (typeof d.data !== "object") {
//                        swal("Hubo un Error", d.data, "error");
//                    } else {
//                        swal("Enhorabuena!!", "Se han guardado los datos satisfactoriamente", "success");
//                        $scope.RondaSistemaServicio = {
//                            Nombre: null,
//                            ServiciosAsignados: [],
//                            UsuariosAsignados: [],
//                            SedeId: null,
//                            Tipo: "Impresora",
//                            CreatedBy: $rootScope.username.NombreUsuario
//                        };
//                        $('#RondaSistemaServicioModal').modal('hide');
//                        GetRondaSistemaServicio();
//                    }
                }, function (e) {
                    swal("Hubo un Error", e, "error");
                });
            }
        };
        
        

        $scope.Imprimir = function () {
            $.print("#printable");
        };

        //</editor-fold>
        function Format(lst) {
            for (var i in lst) {
                for (var k in $scope.Sedes) {
                    if ($scope.Sedes[k].SedeId == lst[i].SedeId) {
                        lst[i].NombreSede = $scope.Sedes[k].Nombre;
                    }
                }
            }
            return lst;
        }
        function _init() {
            GetSede();
            GetServicio();
            GetUsuario();
        }

        //<editor-fold defaultstate="collapsed" desc="Watchers">
        $scope.$watch('ServiciosSeleccionados', function (nowSelected) {
            // reset to nothing, could use `splice` to preserve non-angular references
            //$scope.RondaSistemaServicio.ServiciosAsignados = [];
            
            if (!nowSelected) {
                // sometimes selected is null or undefined
                return;
            }
            
            // here's the magic
            angular.forEach(nowSelected, function (val) {
                for (var k in $scope.Servicios) {
                    if ($scope.Servicios[k].ServicioId == val) {
                        $scope.RondaSistemaServicio.ServiciosAsignados.push($scope.Servicios[k]);
                        $scope.ServiciosAsignados.push($scope.Servicios[k]);
                    }
                }
            });
            $scope.ChangeSede();
        });
        
        
        $scope.$watch('ServiciosAsignados', function (nowSelected) {
            // reset to nothing, could use `splice` to preserve non-angular references
            //$scope.RondaSistemaServicio.ServiciosAsignados = [];
            
            if (!nowSelected) {
                // sometimes selected is null or undefined
                return;
            }
            
            // here's the magic
            angular.forEach(nowSelected, function (val) {
                for (var k in $scope.RondaSistemaServicio.ServiciosAsignados) {
                    if ($scope.RondaSistemaServicio.ServiciosAsignados[k].ServicioId == val) {
                        $scope.RondaSistemaServicio.ServiciosAsignados.splice(k,1);
                        $scope.ServiciosAsignados.splice(k,1);
                    }
                }
            });
            $scope.ChangeSede();
        });
        
        $scope.$watch('UsuariosSeleccionados', function (nowSelected) {
            // reset to nothing, could use `splice` to preserve non-angular references
            //$scope.RondaSistemaServicio.ServiciosAsignados = [];
            
            if (!nowSelected) {
                // sometimes selected is null or undefined
                return;
            }
            
            // here's the magic
            angular.forEach(nowSelected, function (val) {
                for (var k in $scope.Usuarios) {
                    if ($scope.Usuarios[k].UsuarioId == val) {
                        $scope.RondaSistemaServicio.UsuariosAsignados.push($scope.Usuarios[k]);
                        $scope.UsuariosAsignados.push($scope.Usuarios[k]);
                    }
                }
            });
            RevisarUsuario_seleccionados();
        });
        
        $scope.$watch('UsuariosAsignados', function (nowSelected) {
            // reset to nothing, could use `splice` to preserve non-angular references
            //$scope.RondaSistemaServicio.ServiciosAsignados = [];
            
            if (!nowSelected) {
                // sometimes selected is null or undefined
                return;
            }
            
            // here's the magic
            angular.forEach(nowSelected, function (val) {
                for (var k in $scope.RondaSistemaServicio.UsuariosAsignados) {
                    if ($scope.RondaSistemaServicio.UsuariosAsignados[k].UsuarioId == val) {
                        $scope.RondaSistemaServicio.UsuariosAsignados.splice(k, 1);
                        $scope.UsuariosAsignados.splice(k, 1);
                    }
                }
            });
            $scope.Usuarios = angular.copy($scope.UsuariosBackup);
            RevisarUsuario_seleccionados();
        });
        //</editor-fold>

        
        function RevisarServicio_seleccionados() {
            $scope.Servicios
            for (var i in $scope.RondaSistemaServicio.ServiciosAsignados) {
                for (var k in $scope.Servicios) {
                    if ($scope.RondaSistemaServicio.ServiciosAsignados[i].ServicioId === $scope.Servicios[k].ServicioId) {
                        $scope.Servicios.splice(k, 1);
                    }
                }
            }
        }
        
        function RevisarUsuario_seleccionados() {
            for (var i in $scope.RondaSistemaServicio.UsuariosAsignados) {
                for (var k in $scope.Usuarios) {
                    if ($scope.RondaSistemaServicio.UsuariosAsignados[i].UsuarioId === $scope.Usuarios[k].UsuarioId) {
                        $scope.Usuarios.splice(k, 1);
                    }
                }
            }
        }
    }]);


