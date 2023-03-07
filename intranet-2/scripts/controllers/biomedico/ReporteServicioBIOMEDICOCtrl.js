app.controller('ReporteServicioBIOMEDICOCtrl', ["$scope", "$rootScope", "$state", "HojaVidaServiceBiomedico", "SedeService",
  "ServicioService", "SesionService", 'UsuarioBiomedicoService', 'ReporteBiomedicoService',
  'EmpresaService', '$filter', 'EncabezadoBiomedicoService', '$stateParams', 'SOLFactory',
  function ($scope, $rootScope, $state, HojaVidaServiceBiomedico, SedeService, ServicioService, SesionService,
    UsuarioService, ReporteService, EmpresaService, $filter,
    EncabezadoService, $stateParams, SOLFactory) {
    //<editor-fold defaultstate="collapsed" desc="Variables">
    let vm = $scope
    vm.r = '--'
    vm.ProcesandoInformacion = false;
    vm.ReporteId = null;
    vm.sol = "--";
    vm.Empresa = null;
    vm.Editar = false;
    vm.Servicios = [];
    vm.FallaDetectada = '';
    vm.Sedes = [];
    vm.Equipos = [];
    vm.Usuarios = [];
    vm.NumeroReporte = "";
    var fechas = new Date();
    vm.CANVOP = SOLFactory.data.ReporteId ? false : true // Ver opciones
    vm.reporte = {
      NumeroReporte: "",
      SolicitudId: null,
      SedeId: $rootScope.HojaVidaRapida ? $rootScope.HojaVidaRapida.SedeId : SOLFactory.data.Sol.SedeId ? SOLFactory.data.Sol.SedeId : "--",
      ServicioId: $rootScope.HojaVidaRapida ? $rootScope.HojaVidaRapida.ServicioId : SOLFactory.data.Sol.ServicioId ? SOLFactory.data.Sol.ServicioId : "--",
      Solicitante: "",
      Ubicacion: "",
      Responsable: "",
      TipoServicio: "",
      EquipoId: $rootScope.HojaVidaRapida ? $rootScope.HojaVidaRapida.HojaVidaId : "",
      FallaReportada: "",
      FallaDetectada: [],
      ProcedimientoRealizado: "",
      MedidasAplicadas: [],
      Repuestos: "",
      TotalRepuesto: "",
      Observaciones: "",
      EstadoFinal: [],
      ResponsableNombre: $rootScope.username.NombreCompleto,
      ResponsableCargo: $rootScope.username.Cargo,
      //            ResponsableFirma: $rootScope.username.FirmaJefe,
      ResponsableId: $rootScope.username.UserId,
      RecibeFecha: fechas.getFullYear() + "-" + (fechas.getMonth() < 10 ? '0' + (fechas.getMonth() + 1) : fechas.getMonth() + 1) + "-" + fechas.getDate(),
      RecibeHora: new Date(),
      RecibeNombre: "",
      RecibeCargo: "",
      RecibeEmail: "",
      Fecha: fechas.getFullYear() + "-" + (fechas.getMonth() < 10 ? '0' + (fechas.getMonth() + 1) : fechas.getMonth() + 1) + "-" + fechas.getDate(),
      Repuestos: [],
      TotalRepuesto: 0,
      TipoReporte: "Manual",
      ReporteArchivo: null,
      CreatedBy: $rootScope.username.NombreUsuario
    };
    vm.repuesto = {
      Codigo: "",
      Descripcion: "",
      Cantidad: 1,
      Valor: 0,
      EditMode: false
    };
    vm.EditRepuesto = {
      Codigo: "",
      Descripcion: "",
      Cantidad: 1,
      Valor: 0
    };
    const UsuarioId = 
    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="Botones">
    vm.GetReporteById = function () {
      if (vm.ReporteId) {
        ReporteService.GetReporteById(vm.ReporteId).then(function (d) {
          if (typeof d.data !== "string") {
            console.log(d.data[0]);
            vm.reporte = d.data[0];
            GetServicio();
            GetUsuarios();
            GetEquipos();
            vm.reporte.EquipoId = d.data[0].EquipoId;
            vm.reporte.FallaDetectada = typeof d.data[0].FallaDetectada === 'string' ? JSON.parse(d.data[0].FallaDetectada) : null;
            vm.reporte.MedidasAplicadas = typeof d.data[0].MedidasAplicadas === 'string' ? JSON.parse(d.data[0].MedidasAplicadas) : null;
            vm.reporte.Repuestos = typeof d.data[0].Repuestos === 'string' ? JSON.parse(d.data[0].Repuestos) : null;
            vm.reporte.EstadoFinal = typeof d.data[0].EstadoFinal === 'string' ? JSON.parse(d.data[0].EstadoFinal) : null;
            vm.NumeroReporte = 'N°         ' + lpad(d.data[0].ReporteId.toString(), '0', 4);
            vm.reporte.ModifiedBy = $rootScope.username.NombreUsuario;
            vm.reporte.TipoReporte = "Manual";
            vm.reporte.RecibeFirma = d.data[0].RecibeFirma;
          } else {
            swal("Error", "No existe esta hoja de vida", "error");
          }
        });
      }
    };
    vm.Guardar = function () {
      //            console.log(vm.ficha.ProveedorId.originalObject )
      if (!vm.Datos.$valid) {
        angular.element("[name='" + vm.Datos.$name + "']").find('.ng-invalid:visible:first').focus();
      } else if (vm.reporte.TipoServicio === "") {
        swal("Error", 'Debe seleccionar un tipo de servicio.', "error");
      } else if (vm.reporte.EquipoId === "") {
        swal("Error", 'Debe seleccionar un equipo.', "error");
      } else if (vm.reporte.FallaDetectada.length == 0) {
        swal("Error", 'Debe reportar como minimo una falla.', "error");
      } else if (vm.reporte.MedidasAplicadas.length == 0) {
        swal("Error", 'Debe reportar como minimo una medida aplicada.', "error");
      } else if (vm.reporte.EstadoFinal.length == 0) {
        swal("Error", 'Debe reportar como minimo un estado final del equipo.', "error");
      } else if (vm.reporte.RecibeCargo === "" || vm.reporte.RecibeEmail === "" || vm.reporte.RecibeNombre === "") {
        swal("Error", 'Debe seleccionar un usuario que recibira el reporte.', "error");
      } else {
        let reporte = angular.copy(vm.reporte);
        reporte.FallaDetectada = JSON.stringify(vm.reporte.FallaDetectada);
        reporte.MedidasAplicadas = JSON.stringify(vm.reporte.MedidasAplicadas);
        reporte.EstadoFinal = JSON.stringify(vm.reporte.EstadoFinal);
        reporte.Repuestos = JSON.stringify(vm.reporte.Repuestos);
        var obj = {
          Reporte: JSON.stringify([reporte]),
          UserId: $rootScope.username.NombreUsuario
        };
        vm.ProcesandoInformacion = true;
        ReporteService.postReporte(obj).then(function (d) {
          console.log(d.data);
          vm.ProcesandoInformacion = false;
          if (typeof d.data !== "string") {
            $rootScope.$broadcast('saved-reporte', {
              ReporteId: d.data[0]
            })
            swal("Enhorabuena!", 'Se hanguardado los datos satisfactoriamente', "success");
            vm.Reset();
            $rootScope.ReporteId = d.data;
            $state.go("mantenimiento.ver_reporte", {
              reporte_id: $rootScope.ReporteId[0]
            });
          } else {
            swal("Error", d.data, "error");
          }
        }, function (e) {
          vm.ProcesandoInformacion = false;
          swal("Error", e, "error");
        });
      }
    };

    vm.Actualizar = function () {
      if (!vm.Datos.$valid) {
        angular.element("[name='" + vm.Datos.$name + "']").find('.ng-invalid:visible:first').focus();
      } else if (vm.reporte.TipoServicio === "") {
        swal("Error", 'Debe seleccionar un tipo de servicio.', "error");
      } else if (vm.reporte.EquipoId === "") {
        swal("Error", 'Debe seleccionar un equipo.', "error");
      } else if (vm.reporte.FallaDetectada.length == 0) {
        swal("Error", 'Debe reportar como minimo una falla.', "error");
      } else if (vm.reporte.MedidasAplicadas.length == 0) {
        swal("Error", 'Debe reportar como minimo una medida aplicada.', "error");
      } else if (vm.reporte.EstadoFinal.length == 0) {
        swal("Error", 'Debe reportar como minimo un estado final del equipo.', "error");
      } else if (vm.reporte.RecibeCargo === "" || vm.reporte.RecibeEmail === "" || vm.reporte.RecibeNombre === "") {
        swal("Error", 'Debe seleccionar un usuario que recibira el reporte.', "error");
      } else {

        vm.reporte.FallaDetectada = JSON.stringify(vm.reporte.FallaDetectada);
        vm.reporte.MedidasAplicadas = JSON.stringify(vm.reporte.MedidasAplicadas);
        vm.reporte.EstadoFinal = JSON.stringify(vm.reporte.EstadoFinal);
        vm.reporte.Repuestos = JSON.stringify(vm.reporte.Repuestos);
        var obj = {
          Reporte: JSON.stringify([vm.reporte]),
          UserId3: $rootScope.username.NombreUsuario
        };
        vm.ProcesandoInformacion = true;
        ReporteService.putReporte(obj).then(function (d) {
          console.log(d.data);
          vm.ProcesandoInformacion = false;
          if (typeof d.data !== "string") {
            swal("Enhorabuena!", 'Se hanguardado los datos satisfactoriamente', "success");
            vm.Reset();
            $rootScope.ReporteId = d.data;
          } else {
            vm.reporte.FallaDetectada = JSON.parse(vm.reporte.FallaDetectada);
            vm.reporte.MedidasAplicadas = JSON.parse(vm.reporte.MedidasAplicadas);
            vm.reporte.EstadoFinal = JSON.parse(vm.reporte.EstadoFinal);
            vm.reporte.Repuestos = JSON.parse(vm.reporte.Repuestos);
            swal("Error", d.data, "error");
          }
        }, function (e) {
          vm.ProcesandoInformacion = false;
          vm.reporte.FallaDetectada = JSON.parse(vm.reporte.FallaDetectada);
          vm.reporte.MedidasAplicadas = JSON.parse(vm.reporte.MedidasAplicadas);
          vm.reporte.EstadoFinal = JSON.parse(vm.reporte.EstadoFinal);
          vm.reporte.Repuestos = JSON.parse(vm.reporte.Repuestos);
          swal("Error", e, "error");
        });
      }
    };
    vm.AddRepuesto = function () {
      if (vm.repuesto.Cantidad > 0 && vm.repuesto.Descripcion !== "") {
        vm.reporte.Repuestos.push(vm.repuesto);
        vm.repuesto = {
          Codigo: "",
          Descripcion: "",
          Cantidad: 1,
          Valor: 0,
          EditMode: false
        };
        GetTotal();
      } else {
        swal("Error", "La cantidad no puede ser CERO y la descripción no debe estar vacia", "error");
      }
    };

    vm.DeleteRepuesto = function (i) {
      vm.reporte.Repuestos.splice(i, 1);
    };
    vm.Edit = function (i) {
      vm.EditRepuesto = angular.copy(vm.reporte.Repuestos[i]);
      vm.reporte.Repuestos[i].EditMode = true;
    };
    vm.EditarRepuesto = function (i) {
      vm.reporte.Repuestos[i] = vm.EditRepuesto;
      vm.reporte.Repuestos[i].EditMode = false;
      GetTotal();
    };
    vm.Cancelar = function (i) {
      vm.reporte.Repuestos[i].EditMode = false;
      vm.EditRepuesto = {
        Codigo: "",
        Descripcion: "",
        Cantidad: 1,
        Valor: 0
      };
    };
    vm.AddFallas = function (txt) {
      var cont = 0;
      for (var i in vm.reporte.FallaDetectada) {
        if (vm.reporte.FallaDetectada[i] === txt) {
          cont++;
          vm.reporte.FallaDetectada.splice(i, 1);
        }
      }
      if (cont == 0 && txt != "") {
        vm.reporte.FallaDetectada.push(txt);
      }
    };
    vm.AddMedidas = function (txt) {
      var cont = 0;
      for (var i in vm.reporte.MedidasAplicadas) {
        if (vm.reporte.MedidasAplicadas[i] === txt) {
          cont++;
          vm.reporte.MedidasAplicadas.splice(i, 1);
        }
      }
      if (cont == 0 && txt != "") {
        vm.reporte.MedidasAplicadas.push(txt);
      }
    };
    vm.AddEstadoFinal = function (txt) {
      var cont = 0;
      for (var i in vm.reporte.EstadoFinal) {
        if (vm.reporte.EstadoFinal[i] === txt) {
          cont++;
          vm.reporte.EstadoFinal.splice(i, 1);
        }
      }
      if (cont == 0 && txt != "") {
        vm.reporte.EstadoFinal.push(txt);
      }
    };
    vm.ResetParcial = function () {
      vm.sol = "--"
      vm.reporte.Marca = "";
      vm.reporte.Serie = "";
      vm.reporte.Modelo = "";
      vm.reporte.Inventario = "";
      vm.reporte.EquipoId = "";
    };
    vm.Reset = function () {
      vm.sol = "--";
      $('#configform')[0].reset();
      vm.Usuarios = [];
      vm.Equipos = [];
      vm.reporte = {
        NumeroReporte: "",
        SolicitudId: null,
        SedeId: "--",
        Fecha: "",
        ServicioId: "--",
        Solicitante: "",
        Ubicacion: "",
        Responsable: "",
        TipoServicio: "",
        EquipoId: "",
        FallaReportada: "",
        FallaDetectada: [],
        ProcedimientoRealizado: "",
        MedidasAplicadas: [],
        Repuestos: "",
        TotalRepuesto: "",
        Observaciones: "",
        EstadoFinal: [],
        ResponsableNombre: $rootScope.username.NombreCompleto,
        ResponsableCargo: $rootScope.username.Cargo,
        //            ResponsableFirma: $rootScope.username.FirmaJefe,
        ResponsableId: $rootScope.username.UserId,
        RecibeFecha: new Date(),
        RecibeHora: new Date(),
        RecibeNombre: "",
        RecibeCargo: "",
        RecibeEmail: "",
        Fecha: new Date(),
        Repuestos: [],
        TotalRepuesto: 0,
        TipoReporte: "Manual",
        ReporteArchivo: null,
        CreatedBy: $rootScope.username.NombreUsuario
      };
      vm.repuesto = {
        Codigo: "",
        Descripcion: "",
        Cantidad: 1,
        Valor: 0,
        EditMode: false
      };
      vm.EditRepuesto = {
        Codigo: "",
        Descripcion: "",
        Cantidad: 1,
        Valor: 0
      };
      $("#ex2_value").val('');
      vm.FallaDetectada = '';
      $('input[type=checkbox]:checked').removeAttr('checked');
      _init();
    };
    vm.Recibe = function (i) {
      vm.reporte.RecibeCargo = vm.Usuarios[i].Cargo;
      vm.reporte.RecibeId = vm.Usuarios[i].UsuarioId;
      vm.reporte.RecibeNombre = vm.Usuarios[i].NombreCompleto;
      vm.reporte.Solicitante = vm.Usuarios[i].NombreCompleto;
      vm.reporte.RecibeEmail = vm.Usuarios[i].Email;
    };
    vm.ChangeEquipo = function (i) {
      vm.reporte.Marca = vm.Equipos[i].Marca;
      vm.reporte.Serie = vm.Equipos[i].Serie;
      vm.reporte.Modelo = vm.Equipos[i].Modelo;
      vm.reporte.Inventario = vm.Equipos[i].Inventario;
      vm.reporte.EquipoId = vm.Equipos[i].HojaVidaId;
      vm.reporte.Ubicacion = vm.Equipos[i].Ubicacion;
    };
    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="Helpers">
    function GetTotal() {
      vm.reporte.TotalRepuesto = 0;
      for (var i in vm.reporte.Repuestos) {
        vm.reporte.TotalRepuesto += parseFloat(vm.reporte.Repuestos[i].Valor);
      }
    }

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
    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="Consultas">
    vm.ChangeServicios = function () {
      GetServicio();
      vm.ResetParcial();
    };
    vm.ChangeEquipos = function () {
      GetEquipos();
      GetUsuarios();
      vm.ResetParcial();
    };

    function GetServicio() {
      ServicioService.getServicioBySedeWithTA(vm.reporte.SedeId, $rootScope.username.UserId, SOLFactory.data.PREFIJO).then(function (c) {
        vm.Servicios = $filter("orderBy")(c.data, "Nombre");
      });
    }

    function GetSede() {
      SedeService.getAllSedeByUserId_TA($rootScope.username.UserId, SOLFactory.data.PREFIJO).then(function (c) {
        vm.Sedes = $filter("orderBy")(c.data, "Nombre");
        if (vm.Sedes.length == 1) {
          vm.reporte.SedeId = vm.Sedes[0].SedeId;
          GetServicio();
        }
      });
    }

    function GetNReporte() {

      ReporteService.GetNReporte().then(function (d) {
        if (d.data.length > 0) {
          vm.NumeroReporte = 'N°         ' + lpad((parseInt(d.data[0].ReporteId) + 1).toString(), '0', 4);
        } else {
          vm.NumeroReporte = 'N°         ' + lpad('1', '0', 4);
        }
      });
    }

    function GetEquipos() {
      HojaVidaServiceBiomedico.GetHojaVida(vm.reporte.ServicioId).then(function (e) {
        vm.Equipos = $filter("orderBy")(e.data, "Equipo");
      });
    }

    function GetUsuarios() {
      UsuarioService.GetALLusuariosByServicio(vm.reporte.ServicioId).then(function (u) {
        vm.Usuarios = $filter("orderBy")(u.data, "NombreCompleto");
        if (SesionService.get("UserData_Polivalente").UsuarioBiomedicoId) {
          vm.r = vm.Usuarios.findIndex(x => x.UsuarioId === SesionService.get("UserData_Polivalente").UsuarioBiomedicoId)
          vm.Recibe(vm.r)
        }
      });
    }

    function GetEncabezado() {
      EncabezadoService.getEncabezado().then(function (e) {
        vm.Encabezado = e.data;
      });
    }

    function _init() {
      //            GetServicio();
      GetSede();
      if ($rootScope.HojaVidaRapida) {
        GetServicio();
        GetEquipos();
        GetUsuarios();
        vm.reporte.Marca = $rootScope.HojaVidaRapida.Marca;
        vm.reporte.Modelo = $rootScope.HojaVidaRapida.Modelo;
        vm.reporte.Serie = $rootScope.HojaVidaRapida.Serie;
        vm.reporte.Inventario = $rootScope.HojaVidaRapida.Inventario;
        vm.reporte.Ubicacion = $rootScope.HojaVidaRapida.Ubicacion;
      } else if (SOLFactory.data.Sol.SedeId) {
        // Cargamos el formulario con la data de la solicitud
        vm.reporte.SolicitudId = SOLFactory.data.Sol.SolicitudId
        vm.reporte.RecibeNombre = SOLFactory.data.Sol.NombreUsuarioSolicita
        GetServicio()
        GetEquipos()
        GetUsuarios()
      }
      GetNReporte();
      GetEmpresa();
      GetEncabezado();
      if ($stateParams.Reporte_Id || SOLFactory.data.ReporteId) {
        vm.ReporteId = $stateParams.Reporte_Id || SOLFactory.data.ReporteId
        vm.GetReporteById();
      }
    }

    function lpad(str, padString, length) {
      while (str.length < length)
        str = padString + str;
      return str;
    }

    function GetEmpresa() {
      EmpresaService.getEmpresa().then(function (e) {
        vm.Empresa = e.data;
      });
    }
    vm.isInArray = function (list, item) {
      for (var i in list) {
        if (list[i]) {
          if (list[i] === item) {
            return true;
          }
        }
      }
      return false;
    };
    //</editor-fold>
    //        printDiv();
    _init();
  }
]);