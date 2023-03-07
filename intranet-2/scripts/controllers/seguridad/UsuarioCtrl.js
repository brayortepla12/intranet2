'use strict';
app.controller('UsuariosCtrl', ["$scope", "SesionService", "$rootScope", "$state", "$crypto", "UsuarioService", "PermisoService",
  "ModuloService", "ServicioService", "SedeService", "RolService", "$filter", "PersonaService", "SesionFactory",
  function ($scope, SesionService, $rootScope, $state, $crypto, UsuarioService, PermisoService,
    ModuloService, ServicioService, SedeService, RolService, $filter, PersonaService, SesionFactory) {
    //<editor-fold defaultstate="collapsed" desc="inicializar variables">
    let vm = $scope;
    vm.Estado = 'Activo'; // Consultar Personas
    vm.Personas = [];
    vm.simpleTableOptions = {};
    vm.cargado = false;
    vm.ModuloId = null;
    vm.SedeId = null;
    vm.Relacion = [];
    vm.SelectServicio = [];
    vm.Permisos = [];
    vm.Permisos2 = [];
    vm.Modulos = [];
    vm.Sedes = [];
    vm.Servicios = [];
    vm.Roles = [];
    vm.Hoy = new Date();
    const USER = SesionService.get("UserData_Polivalente")
    vm.Usuario = {
      NombreCompleto: "",
      NombreUsuario: "",
      Email: "",
      Contrasena: "",
      FechaVecimiento: "",
      Cargo: "",
      Firma: "",
      CreatedBy: USER.NombreCompleto,
    };
    vm.ExisteEnDB = false;
    vm.Usuarios = [];
    vm.selected = {};
    vm.select = {};
    vm.UpdateUsuario = {};
    vm.RUsuario = {};
    vm.DataImgUpdate = null;
    vm.image = null;
    vm.Persona = null;
    vm.UPersona = null;
    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="Consultas">
    function GetPersonas() {
      if (typeof vm.Personas === "object" && vm.Personas.length === 0) {
        PersonaService.getPersonasLite(vm.Estado).then((d) => {
          vm.Personas = d.data;
        });
      }
    }

    function GetUsuario() {
      vm.simpleTableOptions = {};
      vm.cargado = false;
      vm.Usuario = {
        NombreCompleto: "",
        NombreUsuario: "",
        Email: "",
        Contrasena: "",
        FechaVecimiento: "",
        Cargo: "",
        Firma: "",
        CreatedBy: USER.NombreCompleto,
      };
      vm.Persona = {};
      UsuarioService.GetALLusuarios(USER.key, USER.Email).then(function (d) {
        vm.simpleTableOptions = {
          data: [],
          aoColumns: [{
              mData: 'UsuarioId'
            },
            {
              mData: 'NombreCompleto'
            },
            {
              mData: 'Email'
            },
            {
              mData: 'FechaVecimiento'
            },
            {
              mData: 'Cargo'
            },
            {
              mData: 'Estado2'
            },
            {
              mData: 'Opciones'
            },
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
        vm.simpleTableOptions.data = SetFormat(d.data);
        vm.cargado = true;
        //                vm.Usuarios = d.data;
      });
    }

    function GetPermisos() {
      if (typeof vm.Modulos === "object" && vm.Modulos.length === 0) {
        ModuloService.getAllModulo().then(function (d) {
          vm.Modulos = d.data;
        });
      }
    }

    function GetModulos() {
      if (typeof vm.Permisos === "object" && vm.Permisos.length === 0) {
        PermisoService.getAllPermiso().then(function (d) {
          vm.Permisos = d.data;
        });
      }
    }

    function GetUsuarioModulo(id) {
      ModuloService.getAllModuloByUserId(id).then(function (m) {
        PermisoService.getAllPermisoByUserId(id).then(function (p) {
          vm.Permisos2 = p.data;
          Logica(m.data, p.data);
        });
      });
    }

    function GetUsuarioServicio(id) {
      ServicioService.getServicioByUserId(id).then(function (s) {
        LogicaServicios(s.data);
      });
    }

    function GetSedes() {
      if (typeof vm.Sedes === "object" && vm.Sedes.length === 0) {
        SedeService.getAllSede().then(function (c) {
          vm.Sedes = c.data;
        });
      }
    }

    function GetRoles(UsuarioId) {
      RolService.getRoles(UsuarioId).then(function (c) {
        vm.Roles = c.data.data;
      });
    }

    function GetServicios() {
      if (typeof vm.Servicios === "object" && vm.Servicios.length === 0) {
        ServicioService.getAllServicio().then(function (c) {
          vm.Servicios = c.data;
        });
      }
    }
    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="Botones">
    vm.UpdatePassUsuario = () => {
      if (!vm.Datosr.$valid) {
        angular.element("[name='" + vm.Datosr.$name + "']").find('.ng-invalid:visible:first').focus();
      } else if (vm.RUsuario.Contrasena !== vm.RUsuario.Contrasena2) {
        swal("Error", 'Las contraseñas deben ser iguales.', "error");
      } else {
        var obj = {
          RUsuario: JSON.stringify([vm.RUsuario])
        };
        UsuarioService.PutUsuario(obj).then(function (u) {
          if (typeof u.data != "string") {
            swal("Enhorabuena!", 'Se han guardado los datos satisfactoriamente', "success");
            $('#RUsuarioModal').modal('hide');
            _init();
          } else {
            swal("Error", u.data, "error");
          }
        }, function (e) {
          swal("Error", e, "error");
        });
      }
    };
    vm.ResetPassword = (i) => {
      $('#RUsuarioModal').modal('show');
      vm.RUsuario = vm.simpleTableOptions.data[i];
      vm.$apply();
    };

    vm.EditarUsuario = (i) => {
      $scope.$broadcast('angucomplete-alt:clearInput');
      GetPersonas();
      vm.image = null;
      UsuarioService.GetUsuarioById(vm.simpleTableOptions.data[i].UsuarioId).then((d) => {
        if (typeof d.data == 'object') {
          let Persona = vm.Personas.find((o) => {
            return o.PersonaId == d.data[0].PersonaId;
          });
          if (Persona) {
            $scope.$broadcast('angucomplete-alt:changeInput', 'update-persona', Persona.Nombres);
          }
          vm.UPersona = Persona;
          vm.UpdateUsuario = d.data[0];
          $('#UpdateUsuarioModal').modal('show');
        }
      });
    };
    vm.OpenModalCrear = () => {
      $scope.$broadcast('angucomplete-alt:clearInput');
      GetPersonas();
      vm.image = null;
      $('#UsuarioModal').modal('show');
    };
    vm.AutoLogin = (i) => {
      let obj = JSON.stringify({
        UsuarioId: vm.simpleTableOptions.data[i].UsuarioId,
        FechaCreated: moment().add(5, 'm').format()
      });
      $rootScope.username = null;
      SesionFactory.Menu = [];
      SesionService.remove("UserData_Polivalente");
      SesionService.remove("MenuAPP_Polivalente");
      let encrypted = $crypto.encrypt(obj, 'Franklin Ospino');
      $state.go("login", {
        UsuarioId: encrypted
      });
    };
    vm.MarcarSedes = (SedeId, All) => {
      let obj = {
        SedeId_all: SedeId,
        UsuarioId_all: vm.select.UsuarioId,
        All: All
      };
      UsuarioService.PutUsuario(obj).then((d) => {
        if (typeof d.data != "string") {
          for (var i = 0; i < vm.SelectServicio.length; i++) {
            vm.SelectServicio[i].isSelected = All ? 1 : 0;
          }
        } else {
          swal("Error", d.data, "error");
        }
      });
    };
    vm.MarcarPermisos = (ModuloId, All) => {
      let obj = {
        ModuloId_allp: ModuloId,
        UsuarioId_allp: vm.select.UsuarioId,
        Allp: All
      };
      UsuarioService.PutUsuario(obj).then((d) => {
        if (typeof d.data != "string") {
          for (var i = 0; i < vm.SelectModule.length; i++) {
            vm.SelectModule[i].isSelected = All ? 1 : 0;
          }
        } else {
          swal("Error", d.data, "error");
        }
      });
    };
    vm.ViewPermisos = function (i) {
      GetPermisos();
      GetModulos();
      vm.select = vm.simpleTableOptions.data[i];
      GetUsuarioModulo(vm.simpleTableOptions.data[i].UsuarioId);
      $('#UsuarioPermisoModal').modal('show');
    };
    vm.ViewServicios = function (i) {
      GetSedes();
      GetServicios();
      vm.SelectServicio = [];
      vm.select = vm.simpleTableOptions.data[i];
      GetUsuarioServicio(vm.simpleTableOptions.data[i].UsuarioId);
      $('#UsuarioServicioModal').modal('show');
    };
    vm.ViewRoles = function (i) {
      vm.select = vm.simpleTableOptions.data[i];
      GetRoles(vm.simpleTableOptions.data[i].UsuarioId);
      $('#UsuarioRolModal').modal('show');
    };
    vm.ChangeEmail = function () {
      UsuarioService.IsInDB(vm.Usuario.Email).then(function (e) {
        console.log(e.data)
        vm.ExisteEnDB = e.data == 1 ? true : false;
      });
    };
    vm.ChangeEstado = function () {
      vm.select.Estado = vm.select.Estado === 1 ? 0 : 1;
    };
    vm.GuardarUsuario = function () {
      if (!vm.c.$valid) {
        angular.element("[name='" + vm.c.$name + "']").find('.ng-invalid:visible:first').focus();
      } else if (vm.Usuario.Contrasena !== vm.Usuario.Contrasena2) {
        swal("Error", 'Las contraseñas deben ser iguales.', "error");
      } else if (vm.ExisteEnDB) {
        swal("Error", 'Este correo ya se encuentra registrado.', "error");
      } else if (!vm.Persona) {
        swal("Error", 'Debes seleccionar una Persona', "error");
      } else if (validateEmail(vm.Usuario.Email)) {
        //                if (vm.DataImg) {
        //                    if (vm.DataImg.compressed) {
        //                        vm.Usuario.Firma = vm.DataImg.compressed.dataURL;
        vm.Usuario.NombreCompleto = vm.Persona.originalObject.Nombres;
        vm.Usuario.PersonaId = vm.Persona.originalObject.PersonaId;
        var obj = {
          Usuario: JSON.stringify([vm.Usuario])
        };
        UsuarioService.PostUsuario(obj).then(function (u) {
          if (typeof u.data != "string") {
            swal("Enhorabuena!", 'Se han guardado los datos satisfactoriamente', "success");
            $scope.$broadcast('angucomplete-alt:clearInput');
            _init();
          } else {
            swal("Error", u.data, "error");
          }
        }, function (e) {
          swal("Error", e, "error");
        });
        //                    }
        //                } else {
        //                    swal("Error", 'Debe añadir una Firma.', "error");
        //                }
      } else {
        swal("Error", 'Debe añadir un email valido', "error");
      }
    };
    vm.ActualizarUsuario = () => {
      if (!vm.Datos.$valid) {
        angular.element("[name='" + vm.Datos.$name + "']").find('.ng-invalid:visible:first').focus();
      } else if (!vm.UPersona) {
        swal("Error", 'Debes seleccionar una Persona', "error");
      } else {
        if (vm.DataImgUpdate != null) {
          if (vm.DataImgUpdate.compressed) {
            vm.UpdateUsuario.Firma = vm.DataImgUpdate.compressed.dataURL;
          }
        }
        vm.UpdateUsuario.NombreCompleto = vm.UPersona.originalObject ? vm.UPersona.originalObject.Nombres : vm.UPersona.Nombres;
        vm.UpdateUsuario.PersonaId = vm.UPersona.originalObject ? vm.UPersona.originalObject.PersonaId : vm.UPersona.PersonaId;
        let obj = {
          UpdateUsuario: JSON.stringify([vm.UpdateUsuario])
        };
        UsuarioService.PutUsuario(obj).then(function (u) {
          if (typeof u.data != "string") {
            swal("Enhorabuena!", 'Se han actualizado los datos satisfactoriamente', "success");
            $scope.$broadcast('angucomplete-alt:clearInput');
            document.getElementById("firma-update").value = "";
            vm.DataImgUpdate = null;
            $('#UpdateUsuarioModal').modal('hide');
            _init();
          } else {
            swal("Error", u.data, "error");
          }
        }, function (e) {
          swal("Error", e, "error");
        });
      }
    };
    vm.SeleccionarModulo = function (m, index) {
      for (var i = 0, max = vm.Modulos.length; i < max; i++) {
        $scope["modulo" + i] = false;
      }
      $scope["modulo" + index] = true;
      vm.ModuloId = m.ModuloId;
      vm.SelectModule = m.Permisos;
    };
    vm.SeleccionarServicio = function (s, index) {
      for (var i = 0, max = vm.Servicios.length; i < max; i++) {
        $scope["servicio" + i] = false;
      }
      $scope["servicio" + index] = true;
      vm.SedeId = s.SedeId;
      vm.SelectServicio = $filter('orderBy')(s.Servicios, 'Nombre');;
    };
    vm.SelectPermiso = function (o, i) {
      var data = {
        UsuarioPermiso: JSON.stringify([{
          PermisoId: o.PermisoId,
          ModuloId: o.ModuloId,
          UsuarioId: vm.select.UsuarioId,
          CreatedBy: $rootScope.username.NombreUsuario,
          State: o.State
        }])
      };
      PermisoService.PostPermiso(data).then(function (d) {
        vm.SelectModule[i].isSelected = vm.SelectModule[i].isSelected ? false : true;
      }, function (e) {
        swal("Hubo un Error", e, "error");
      });
    };
    vm.SelectARol = function (o, i) {
      var data = {
        RolUsuario: JSON.stringify({
          RolId: o.RolId,
          UsuarioId: vm.select.UsuarioId,
        })
      };
      UsuarioService.PutUsuario(data).then(function (d) {
        if (d.data.data) {
          vm.Roles[i].IsSelected = vm.Roles[i].IsSelected ? false : true;
        }
      }, function (e) {
        swal("Hubo un Error", e, "error");
      });
    };
    vm.SelectAServicio = function (o, i) {
      var data = {
        ServicioUsuario: JSON.stringify([{
          ServicioId: o.ServicioId,
          SedeId: o.SedeId,
          UsuarioId: vm.select.UsuarioId,
        }])
      };
      ServicioService.PostServicio(data).then(function (d) {
        vm.SelectServicio[i].isSelected = vm.SelectServicio[i].isSelected ? false : true;
      }, function (e) {
        swal("Hubo un Error", e, "error");
      });
    };
    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="Helper">
    function SetFormat(lst) {
      for (var i in lst) {
        lst[i].Estado2 = lst[i].Estado == 1 ? 'Activo' : 'Desactivado';
        lst[i].Opciones = '';
        lst[i].Opciones += '<a class="btn  btn-info btn-xs icon-only white" data-toggle="tooltip" title="Ver Permisos" onclick=\"angular.element(this).scope().ViewPermisos(' + i + ')\"><i class="fa fa-th-list"></i></a>' +
          ' <a class="btn  btn-primary btn-xs icon-only white" data-toggle="tooltip" title="Ver Servicios" onclick=\"angular.element(this).scope().ViewServicios(' + i + ')\"><i class="fas fa-hospital-o"></i></a>' +
          ' <a class="btn  btn-warning btn-xs icon-only white" data-toggle="tooltip" title="Ver Roles" onclick=\"angular.element(this).scope().ViewRoles(' + i + ')\"><i class="fa fa-user-plus"></i></a>' +
          ' <a class="btn  btn-success btn-xs icon-only white" data-toggle="tooltip" title="Editar Usuario" onclick=\"angular.element(this).scope().EditarUsuario(' + i + ')\"><i class="fa fa-pencil"></i></a>' +
          ' <a class="btn  btn-default btn-xs icon-only white" data-toggle="tooltip" title="Restableber contraseña" onclick=\"angular.element(this).scope().ResetPassword(' + i + ')\"><i class="fa fa-lock"></i></a>' +
          ' <a class="btn  btn-danger btn-xs icon-only white" data-toggle="tooltip" title="Iniciar sesión" onclick=\"angular.element(this).scope().AutoLogin(' + i + ')\"><i class="fa fa-user-circle-o"></i></a>';
      }
      return lst;
    }

    function LogicaServicios(lst) {
      vm.RelacionServicio = [];
      for (var i = 0, max = vm.Sedes.length; i < max; i++) {
        $scope["servicio" + i] = false;
      }
      var ss = angular.copy(vm.Sedes);
      for (var i in ss) {
        ss[i].Servicios = VerificarServicios(ss[i].SedeId, lst);
        vm.RelacionServicio.push(ss[i]);
      }
      console.log(vm.RelacionServicio);
    }

    function VerificarServicios(SedeId, lst) {
      var Servicios = [];
      var ser = angular.copy(vm.Servicios);
      for (var j in ser) {
        for (var k in lst) {
          if (ser[j].ServicioId === lst[k].ServicioId) {
            ser[j].isSelected = true;
          }
        }
        if (SedeId === ser[j].SedeId) {
          Servicios.push(ser[j]);
        }
      }
      return Servicios;
    }

    function Logica(mlst, plst) {
      vm.Relacion = [];
      vm.SelectModule = [];
      for (var i = 0, max = vm.Modulos.length; i < max; i++) {
        $scope["modulo" + i] = false;
      }
      var mm = angular.copy(vm.Modulos);
      for (var i in mm) {
        for (var j in mlst) {
          if (mm[i].ModuloId === mlst[j].ModuloId) {
            mm[i].isSelected = true;
            break;
          }
        }
        mm[i].Permisos = VerificarPermisos(mm[i].ModuloId, plst);
        vm.Relacion.push(mm[i]);
      }
      console.log(vm.Relacion);
    }

    function VerificarPermisos(ModuloId, plst) {
      var permisos = [];
      var pp = angular.copy(vm.Permisos);
      for (var k in pp) {
        for (var l in plst) {
          if (pp[k].PermisoId === plst[l].PermisoId) {
            pp[k].isSelected = true;
          }
        }
        if (pp[k].ModuloId === ModuloId) {
          permisos.push(pp[k]);
        }

      }
      return permisos;
    }

    function validateEmail(email) {
      var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
      return re.test(email);
    }
    //</editor-fold>
    function _init() {
      GetUsuario();
    }
    _init();
    //<editor-fold defaultstate="collapsed" desc="Codigo para el editar en linea">
    vm.getTemplate = function (item) {
      if (item.UsuarioId === vm.selected.UsuarioId) {
        return 'edit';
      } else
        return '/polivalente/usuario.display.html';
    };
    vm.editObj = function (item) {


      vm.ItemA = "";
      vm.select = item;
      vm.selected = angular.copy(item);

    };
    vm.reset = function () {
      vm.selected = {};
    };
    vm.Actualizar = function () {
      vm.select.ModifiedBy = USER.NombreCompleto;
      var obj = {
        Usuario: JSON.stringify([vm.select]),
        Key: USER.key
      };
      UsuarioService.PutUsuario(obj).then(function (d) {
        console.log(d.data)
        if (typeof d.data === "string") {
          swal("Hubo un Error", d.data, "error");
        } else {
          swal("Enhorabuena!!", "Se han guardado los datos satisfactoriamente", "success");
          vm.select = {};
          vm.selected = {};
          GetUsuario();
        }
      }, function (e) {
        swal("Hubo un Error", e, "error");
      });
    };
    //</editor-fold>
  }
]);