app.controller('ReporteServicioSistemaCtrl', ["$scope", "$rootScope", "$state", "HojaVidaSistemaService", "SedeService", "ServicioService", "$stateParams", 'UsuarioService',
  'ReporteSistemaService', 'EmpresaService', '$filter', 'EncabezadoService', 'SOLFactory',
  function ($scope, $rootScope, $state, HojaVidaSistemaService, SedeService, ServicioService, $stateParams, UsuarioService, ReporteSistemaService,
    EmpresaService, $filter, EncabezadoService, SOLFactory) {
    let vm = $scope
    vm.r = '--'
    //<editor-fold defaultstate="collapsed" desc="Variables">
    vm.ImprimirReporte = false;
    vm.IsAdminSistemas = $rootScope.username.IsAdminSistemas;
    vm.ProcesandoPeticion = false;
    vm.ReporteDiario = false;
    vm.sol = "--";
    vm.Empresa = null;
    vm.Editar = false;
    vm.Servicios = [];
    vm.FallaDetectada = '';
    vm.Sedes = [];
    vm.Equipos = [];
    vm.Usuarios = [];
    vm.NumeroReporte = "";
    vm.CANVOP = SOLFactory.data.ReporteId ? false : true // Ver opciones
    vm.reporte = {
      NumeroReporte: "",
      SolicitudId: null,
      SedeId: $rootScope.HojaVidaRapida ? $rootScope.HojaVidaRapida.SedeId : SOLFactory.data.Sol.SedeId ? SOLFactory.data.Sol.SedeId : "",
      ServicioId: $rootScope.HojaVidaRapida ? $rootScope.HojaVidaRapida.ServicioId : SOLFactory.data.Sol.ServicioId ? SOLFactory.data.Sol.ServicioId : "",
      Solicitante: "",
      Ubicacion: "",
      Responsable: "",
      TipoServicio: "",
      Fotos: "",
      EquipoId: $rootScope.HojaVidaRapida ? $rootScope.HojaVidaRapida.HojaVidaId : "",
      FallaReportada: "",
      FallaDetectada: [],
      ProcedimientoRealizado: "",
      Observaciones: "",
      Contador: $rootScope.HojaVidaRapida ? $rootScope.HojaVidaRapida.TipoArticulo === 'Impresora' ? "" : "N/A" : "N/A",
      TipoArticulo: "",
      EstadoFinal: [],
      ResponsableNombre: $rootScope.username.NombreCompleto,
      ResponsableCargo: $rootScope.username.Cargo,
      ResponsableFirma: $rootScope.username.Firma,
      RecibeFecha: moment().format("YYYY-MM-DD"),
      RecibeHora: moment().format("HH:mm"),
      RecibeNombre: "",
      RecibeCargo: "",
      RecibeEmail: "",
      Fecha: moment().format("YYYY-MM-DD"),
      TipoReporte: "Manual",
      ReporteArchivo: null,
      ResponsableId: $rootScope.username.PersonaId,
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
    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="Botones">

    vm.GetReporteById = function () {
      if ($scope.ReporteId) {
        ReporteSistemaService.GetReporteById($scope.ReporteId).then(function (d) {
          console.log(d.data)
          if (typeof d.data !== "string" && d.data.length > 0) {
            vm.reporte = d.data[0];
            GetServicio();
            GetUsuarios();
            GetEquipos();
            if (d.data[0].Servicio === "PLANTAS ELÉCTRICAS") {

              vm.ReporteDiario = true;
            }
            vm.reporte.EquipoId = d.data[0].EquipoId;
            vm.reporte.FallaDetectada = typeof d.data[0].FallaDetectada === 'string' ? JSON.parse(d.data[0].FallaDetectada) : null;
            vm.reporte.EstadoFinal = typeof d.data[0].EstadoFinal === 'string' ? JSON.parse(d.data[0].EstadoFinal) : null;
            vm.reporte.Fotos = typeof d.data[0].Fotos === 'string' ? JSON.parse(d.data[0].Fotos) : null;
            vm.NumeroReporte = 'N°         ' + lpad(d.data[0].ReporteId.toString(), '0', 4);
            vm.reporte.ModifiedBy = $rootScope.username.NombreUsuario;
            vm.reporte.TipoReporte = "Manual";
            vm.reporte.RecibeFirma = d.data[0].RecibeFirma;
            vm.reporte.RecibeFecha = d.data[0].RecibeFecha;
          } else {
            swal("Error", d.data, "error");
          }
        });
      }
    };
    vm.Guardar = function () {
      //            console.log($scope.ficha.ProveedorId.originalObject )
      if (!$scope.Datos.$valid) {
        angular.element("[name='" + vm.Datos.$name + "']").find('.ng-invalid:visible:first').focus();
      } else if ($scope.reporte.TipoServicio === "") {
        swal("Error", 'Debe seleccionar un tipo de servicio.', "error");
      } else if ($scope.reporte.EquipoId === "" && vm.reporte.TipoServicio != "REDES") {
        swal("Error", 'Debe seleccionar un equipo.', "error");
      } else if ($scope.reporte.FallaDetectada.length == 0 && vm.reporte.TipoServicio != "REDES") {
        swal("Error", 'Debe reportar como minimo una falla.', "error");
      } else if ($scope.reporte.EstadoFinal.length == 0 && vm.reporte.TipoServicio != "REDES") {
        swal("Error", 'Debe reportar como minimo un estado final del equipo.', "error");
      } else if ($scope.reporte.RecibeCargo === "" || vm.reporte.RecibeEmail === "" || vm.reporte.RecibeNombre === "") {
        swal("Error", 'Debe seleccionar un usuario que recibira el reporte.', "error");
      } else {

        vm.reporte.FallaDetectada = JSON.stringify($scope.reporte.FallaDetectada);
        vm.reporte.MedidasAplicadas = JSON.stringify($scope.reporte.MedidasAplicadas);
        vm.reporte.EstadoFinal = JSON.stringify($scope.reporte.EstadoFinal);
        vm.reporte.Repuestos = JSON.stringify($scope.reporte.Repuestos);
        vm.reporte.Fotos = JSON.stringify($scope.reporte.Fotos);
        var obj = {
          Reporte: JSON.stringify([$scope.reporte]),
          UserId: $rootScope.username.NombreUsuario
        };
        vm.ProcesandoPeticion = true;
        ReporteSistemaService.postReporte(obj).then(function (d) {
          vm.ProcesandoPeticion = false;
          if (typeof d.data != "string") {
            $rootScope.$broadcast('saved-reporte', {
              ReporteId: d.data[0]
            })
            swal("Enhorabuena!", 'Se hanguardado los datos satisfactoriamente', "success");
            if ($state.current.name === "sistemas.reporte_servicio_sistemas") {
              location.reload();
            }
            //                       vm.Reset();
            //                        $rootScope.ReporteId = d.data;
            //                        $state.go("mantenimiento.ver_reporte", {reporte_id: $rootScope.ReporteId[0]});
          } else {
            vm.reporte.FallaDetectada = JSON.parse($scope.reporte.FallaDetectada);
            vm.reporte.EstadoFinal = JSON.parse($scope.reporte.EstadoFinal);
            vm.reporte.Fotos = JSON.parse($scope.reporte.Fotos);
            swal("Error", d.data, "error");
          }
        }, function (e) {
          vm.ProcesandoPeticion = false;
          vm.reporte.FallaDetectada = JSON.parse($scope.reporte.FallaDetectada);
          vm.reporte.EstadoFinal = JSON.parse($scope.reporte.EstadoFinal);
          vm.reporte.Fotos = JSON.parse($scope.reporte.Fotos);
          swal("Error", e, "error");
        });
      }
    };
    vm.Actualizar = function () {
      if (!$scope.Datos.$valid) {
        angular.element("[name='" + vm.Datos.$name + "']").find('.ng-invalid:visible:first').focus();
      } else if ($scope.reporte.TipoServicio === "") {
        swal("Error", 'Debe seleccionar un tipo de servicio.', "error");
      } else if ($scope.reporte.EquipoId === "") {
        swal("Error", 'Debe seleccionar un equipo.', "error");
      } else if ($scope.reporte.FallaDetectada.length == 0 && vm.reporte.TipoServicio != "REDES") {
        swal("Error", 'Debe reportar como minimo una falla.', "error");
      } else if ($scope.reporte.EstadoFinal.length == 0 && vm.reporte.TipoServicio != "REDES") {
        swal("Error", 'Debe reportar como minimo un estado final del equipo.', "error");
      } else if ($scope.reporte.RecibeCargo === "" || vm.reporte.RecibeEmail === "" || vm.reporte.RecibeNombre === "") {
        swal("Error", 'Debe seleccionar un usuario que recibira el reporte.', "error");
      } else {

        vm.reporte.FallaDetectada = JSON.stringify($scope.reporte.FallaDetectada);
        vm.reporte.MedidasAplicadas = JSON.stringify($scope.reporte.MedidasAplicadas);
        vm.reporte.EstadoFinal = JSON.stringify($scope.reporte.EstadoFinal);
        vm.reporte.Repuestos = JSON.stringify($scope.reporte.Repuestos);
        vm.reporte.Fotos = JSON.stringify($scope.reporte.Fotos);
        vm.reporte.ResponsableId = $rootScope.username.UserId;
        vm.reporte.ResponsableNombre = $rootScope.username.NombreCompleto;
        vm.reporte.ResponsableCargo = $rootScope.username.Cargo;
        var obj = {
          Reporte: JSON.stringify([$scope.reporte]),
          UserId: $rootScope.username.NombreUsuario
        };
        vm.ProcesandoPeticion = true;
        ReporteSistemaService.putReporte(obj).then(function (d) {
          console.log(d.data);
          vm.ProcesandoPeticion = false;
          if (typeof d.data !== "string") {
            swal("Enhorabuena!", 'Se hanguardado los datos satisfactoriamente', "success");
            vm.Reset();
            $rootScope.ReporteId = d.data;
          } else {
            vm.reporte.FallaDetectada = JSON.parse($scope.reporte.FallaDetectada);
            vm.reporte.MedidasAplicadas = JSON.parse($scope.reporte.MedidasAplicadas);
            vm.reporte.EstadoFinal = JSON.parse($scope.reporte.EstadoFinal);
            vm.reporte.Repuestos = JSON.parse($scope.reporte.Repuestos);
            vm.reporte.Fotos = JSON.parse($scope.reporte.Fotos);
            swal("Error", d.data, "error");
          }
        }, function (e) {
          vm.ProcesandoPeticion = false;
          vm.reporte.FallaDetectada = JSON.parse($scope.reporte.FallaDetectada);
          vm.reporte.MedidasAplicadas = JSON.parse($scope.reporte.MedidasAplicadas);
          vm.reporte.EstadoFinal = JSON.parse($scope.reporte.EstadoFinal);
          vm.reporte.Repuestos = JSON.parse($scope.reporte.Repuestos);
          vm.reporte.Fotos = JSON.parse($scope.reporte.Fotos);
          swal("Error", e, "error");
        });
      }
    };

    vm.AddRepuesto = function () {
      if ($scope.repuesto.Cantidad > 0 && vm.repuesto.Descripcion != "") {
        vm.reporte.Repuestos.push($scope.repuesto);
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
    vm.Imprimir = function () {
      vm.ImprimirReporte = true;
      printDiv();
    };
    vm.DeleteRepuesto = function (i) {
      vm.reporte.Repuestos.splice(i, 1);
    };
    vm.Edit = function (i) {
      vm.EditRepuesto = angular.copy($scope.reporte.Repuestos[i]);
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
        if ($scope.reporte.FallaDetectada[i] === txt) {
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
        if ($scope.reporte.MedidasAplicadas[i] === txt) {
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
        if ($scope.reporte.EstadoFinal[i] === txt) {
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
      vm.reporte.Proveedor = "";
      vm.reporte.NSerial = "";
      vm.reporte.Modelo = "";
      vm.reporte.SO = "";
      vm.reporte.EquipoId = "";
    };
    vm.Reset = function () {
      vm.ReporteDiario = false;
      vm.sol = "--";
      $('#configform')[0].reset();
      vm.Usuarios = [];
      vm.Equipos = [];
      vm.reporte = {
        NumeroReporte: "",
        SolicitudId: null,
        SedeId: $rootScope.HojaVidaRapida ? $rootScope.HojaVidaRapida.SedeId : $rootScope.solicitud ? $rootScope.solicitud.SedeId : "",
        ServicioId: $rootScope.HojaVidaRapida ? $rootScope.HojaVidaRapida.ServicioId : $rootScope.solicitud ? $rootScope.solicitud.ServicioId : "",
        Solicitante: "",
        Ubicacion: "",
        Responsable: "",
        TipoServicio: "",
        Contador: $rootScope.HojaVidaRapida ? $rootScope.HojaVidaRapida.TipoArticulo === 'Impresora' ? "" : "N/A" : "N/A",
        EquipoId: $rootScope.HojaVidaRapida ? $rootScope.HojaVidaRapida.HojaVidaId : "",
        FallaReportada: "",
        FallaDetectada: [],
        ProcedimientoRealizado: "",
        Observaciones: "",
        EstadoFinal: [],
        Fotos: "",
        ResponsableNombre: $rootScope.username.NombreCompleto,
        ResponsableCargo: $rootScope.username.Cargo,
        ResponsableFirma: $rootScope.username.Firma,
        RecibeFecha: moment().format("YYYY-MM-DD"),
        RecibeHora: moment().format("HH:mm"),
        RecibeNombre: "",
        RecibeCargo: "",
        RecibeEmail: "",
        Fecha: moment().format("YYYY-MM-DD"),
        TipoReporte: "Manual",
        ReporteArchivo: null,
        ResponsableId: $rootScope.username.PersonaId,
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
      //            _init();
    };
    vm.Recibe = function (i) {
      vm.reporte.RecibeCargo = vm.Usuarios[i].Cargo;
      vm.reporte.RecibeNombre = vm.Usuarios[i].NombreCompleto;
      vm.reporte.Solicitante = vm.Usuarios[i].NombreCompleto;
      vm.reporte.RecibeId = vm.Usuarios[i].PersonaId;
      vm.reporte.RecibeEmail = vm.Usuarios[i].Email;
    };
    vm.ChangeEquipo = function (i) {
      vm.reporte.Ubicacion = vm.Equipos[i].Ubicacion;
      vm.reporte.Proveedor = vm.Equipos[i].Proveedor;
      vm.reporte.NSerial = vm.Equipos[i].NSerial;
      vm.reporte.Modelo = vm.Equipos[i].Modelo;
      vm.reporte.SO = vm.Equipos[i].SO;
      vm.reporte.EquipoId = vm.Equipos[i].HojaVidaId;
      //           vm.reporte.Contador =vm.Equipos[i].Contador;
      vm.reporte.TipoArticulo = vm.Equipos[i].TipoArticulo;
      if ($scope.Equipos[i].TipoArticulo === 'Impresora') {
        vm.reporte.Contador = "";
      } else {
        vm.reporte.Contador = "N\A";
      }
    };
    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="Helpers">
    function GetTotal() {
      vm.reporte.TotalRepuesto = 0;
      for (var i in vm.reporte.Repuestos) {
        vm.reporte.TotalRepuesto += parseFloat($scope.reporte.Repuestos[i].Valor);
      }
    }

    function printDiv() {
      $("#ReporteSistema").print({
        globalStyles: true,
        mediaPrint: false,
        stylesheet: null,
        noPrintSelector: ".no-print",
        iframe: true,
        append: null,
        prepend: null,
        manuallyCopyFormValues: true,
        deferred: $.Deferred(),
        timeout: 2000,
        title: null,
        doctype: '<!doctype html>'
      });

      setTimeout(function () {
        vm.ImprimirReporte = false;
        vm.$apply();
      }, 1000);

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
      ServicioService.getServicioByUserId($rootScope.username.UserId).then(function (c) {
        var f = $filter('filter')(c.data, {
          SedeId: vm.reporte.SedeId
        });
        vm.Servicios = $filter("orderBy")(f, "Nombre");
      });
    }

    function GetSede() {
      SedeService.getAllSedeByUserId($rootScope.username.UserId).then(function (c) {
        vm.Sedes = $filter("orderBy")(c.data, "Nombre");
        if ($scope.Sedes.length == 1) {
          vm.reporte.SedeId = vm.Sedes[0].SedeId;
          GetServicio();
        }
      });
    }

    function GetNReporte() {

      ReporteSistemaService.GetNReporte().then(function (d) {
        if (d.data.length > 0) {
          vm.NumeroReporte = 'N°         ' + lpad((parseInt(d.data[0].ReporteId) + 1).toString(), '0', 4);
        } else {
          vm.NumeroReporte = 'N°         ' + lpad('1', '0', 4);
        }
      });
    }

    function GetEquipos() {
      HojaVidaSistemaService.GetHojaVida($scope.reporte.ServicioId).then(function (e) {
        //                console.log(e.data)
        vm.Equipos = $filter("orderBy")(e.data, "Ubicacion");
      });
    }

    function GetUsuarios() {
      UsuarioService.GetALLusuariosByServicio($scope.reporte.ServicioId).then(function (u) {
        vm.Usuarios = $filter("orderBy")(u.data, "NombreCompleto");
        if (SOLFactory.data.Sol.UsuarioSolicitaId) {
          vm.r = vm.Usuarios.findIndex(x => x.UsuarioId === SOLFactory.data.Sol.UsuarioSolicitaId)
          console.log(vm.r)
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
        vm.reporte.Proveedor = $rootScope.HojaVidaRapida.Proveedor;
        vm.reporte.Modelo = $rootScope.HojaVidaRapida.Modelo;
        vm.reporte.NSerial = $rootScope.HojaVidaRapida.NSerial;
        vm.reporte.SO = $rootScope.HojaVidaRapida.SO;
        vm.reporte.Ubicacion = $rootScope.HojaVidaRapida.Ubicacion;
        //               vm.reporte.Contador = $rootScope.HojaVidaRapida.Contador;                
        vm.reporte.TipoArticulo = $rootScope.HojaVidaRapida.TipoArticulo;
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
          if (list[i] == item) {
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