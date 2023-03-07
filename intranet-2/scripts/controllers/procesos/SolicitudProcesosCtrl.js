app.controller('SolicitudProcesosCtrl', ["$scope", "$rootScope", "ProcesosService", "ProtocoloService",
  "$builder", "$validator", "SedeService", "ServicioService", "$filter",
  function ($scope, $rootScope, ProcesosService, ProtocoloService, $builder,
    $validator, SedeService, ServicioService, $filter) {
    let vm = $scope
    vm.simpleTableOptions = null
    vm.CargarForm = false
    vm.CargarForm2 = false
    vm.Protocolos = []
    vm.Sedes = []
    vm.Servicios = []
    vm.IsLoading = false
    $builder.forms['JSON'] = null
    vm.Orden = 0
    vm.Seguimientos = []
    vm.Proceso = {
      ProtocoloId: "",
      DatosFormulario: "",
      Nombre: "",
      ServicioId: "",
      SedeId: "",
      SolicitanteId: $rootScope.username.UserId,
      CreatedBy: $rootScope.username.NombreCompleto
    }
    vm.UProceso = {
      ProtocoloId: "",
      DatosFormulario: "",
      Nombre: "",
      ServicioId: "",
      SedeId: "",
      SolicitanteId: $rootScope.username.UserId,
      ModifiedBy: $rootScope.username.NombreCompleto
    }
    vm.input = []
    vm.inputEdit = []
    _init()

    // var conn = new WebSocket('ws://190.131.221.26:8090/protocolo_proceso')
    // conn.onopen = function (e) {
    //     console.log("Connection established!")
    //     conn.send(JSON.stringify({event: 'connect', is_admin: false, UsuarioId: $rootScope.username.UserId, msg: ""}))

    // }

    // conn.onmessage = function (e) {
    //     var event = JSON.parse(e.data)
    //     if (event.event !== 'connect' && event.event !== 'connected') {
    //         vm.cargado = false
    //         vm.simpleTableOptions = null
    //         _init()
    //         vm.$apply()
    //         toastr.success(event.msg, event.Nombres + " dice: ")
    //     }
    //     console.log(e.data)
    // }
    //<editor-fold defaultstate="collapsed" desc="Botones">
    vm.Notificar = () => {
      swal("Atencion!", "DEBES HACER UNA SOLICITUD AL AREA ENCARGADA", "warning")
    }
    vm.OpenURL = function (url, type) {
      var blob = b64toBlob(url.split("base64,")[1], type)
      var blobUrl = window.URL.createObjectURL(blob)
      var w1 = window.open("", "_blank")
      w1.location = blobUrl
      swal("Atención!", "Si tienes problemas para visualizar el archivo, por favor deshabilita el Adblock", "warning")
      swal({
        title: "¿Tienes problemas para visualizar este archivo?",
        text: "Nota: Si tienes problemas para visualizar el archivo, por favor deshabilita el Adblock.",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-primary",
        confirmButtonText: "SI!",
        cancelButtonText: "NO!",
        closeOnConfirm: true,
        closeOnCancel: true
      }, function (isConfirm) {
        if (isConfirm) {
          window.location = blobUrl
        }
      })
    }
    vm.ReanudarSolicitud = function () {
      vm.UProceso.ModifiedBy = $rootScope.username.NombreCompleto
      vm.UProceso.Estado = 'Activo'
      var obj = {
        ReanudarProceso: angular.toJson([vm.UProceso])
      }
      ProcesosService.putProcesos(obj).then(function (d) {
        console.log(d.data)
        if (typeof d.data === "string") {
          swal("Error!", d.data, 'error')
        } else {
          // conn.send(JSON.stringify({
          //     event: 'foradmin',
          //     is_admin: false,
          //     UsuarioId: $rootScope.username.UserId,
          //     Envia: $rootScope.username.NombreCompleto,
          //     msg: "Se ha Reanudado el proceso N° " + vm.UProceso.ProcesoId
          // }))
          swal("Enhorabuena!", "Se han guardado los datos satisfactoriamente", "success")
          _init()
          $('#ViewProcesoModal').modal('hide')
        }
      })
    }
    vm.ChangeProtocolo = function () {
      vm.CargarForm = false
      for (var k in vm.Protocolos) {
        if (vm.Protocolos[k].ProtocoloId == vm.Proceso.ProtocoloId) {
          $builder.forms['JSON'] = JSON.parse(vm.Protocolos[k].Formulario)
          //                    vm.$apply()
          vm.Proceso.DatosFormulario = vm.Protocolos[k].Formulario
          vm.CargarForm = true
        }
      }
    }

    vm.ShowProceso = function (i) {
      vm.Seguimientos = []
      ProcesosService.GetProcesosById(vm.simpleTableOptions.data[i].ProcesoId).then(function (p) {
        console.log(p.data)
        vm.UProceso = angular.copy(p.data)
        vm.Orden = parseInt(vm.UProceso.OrdenEnCurso)
        ProcesosService.GetProcesoData(vm.UProceso.ProcesoId).then(function (d) {
          console.log(d.data)
          vm.UProceso.FlujoTrabajo = d.data
          for (var i in vm.UProceso.FlujoTrabajo) {
            for (var k in vm.UProceso.FlujoTrabajo[i].Seguimiento) {
              vm.Seguimientos.push(vm.UProceso.FlujoTrabajo[i].Seguimiento[k])
            }
          }
          vm.UProceso.DatosFormulario = JSON.parse(vm.UProceso.DatosFormulario)
          $('#ViewProcesoModal').modal('show')
          //                vm.$apply()
        })
      })

    }

    vm.changeView = function (o) {
      vm.Orden = o
    }

    vm.EditProceso = function (i) {
      ProcesosService.GetProcesosById(vm.simpleTableOptions.data[i].ProcesoId).then(function (p) {
        vm.UProceso = angular.copy(p.data)
        vm.UProceso.DatosFormulario = JSON.parse(vm.UProceso.DatosFormulario)
        for (var k in vm.Protocolos) {
          if (vm.Protocolos[k].ProtocoloId == vm.UProceso.ProtocoloId) {
            vm.UProceso.Formulario = JSON.parse(vm.Protocolos[k].Formulario)
            vm.UProceso.DatosFormulario = vm.UProceso.DatosFormulario
            vm.CargarForm2 = true
            var count = 0
          }
        }
        $('#EditProcesoModal').modal('show')
        for (var i = 0; i < vm.UProceso.DatosFormulario.length; i++) {
          var x = vm.UProceso.DatosFormulario[i].label
          console.log(vm.UProceso.DatosFormulario[i])
          if (document.getElementById(i) != null) {
            $(document.getElementById(i)).val(vm.UProceso.DatosFormulario[i].value)
          }
        }
        vm.input = vm.UProceso.DatosFormulario
      })

    }

    vm.ActualizarSolicitud = function () {
      vm.UProceso.ModifiedBy = $rootScope.username.NombreCompleto
      $validator.validate($scope, 'EditJSON').success(function () {
        vm.UProceso.DatosFormulario = angular.toJson(vm.input)
        var obj = {
          Proceso: angular.toJson([vm.UProceso]),
          ID: vm.UProceso.ProcesoId
        }
        ProcesosService.putProcesos(obj).then(function (d) {
          console.log(d.data)
          if (typeof d.data === "string") {
            swal("Error!", d.data, 'error')
          } else {
            // conn.send(JSON.stringify({
            //     event: 'foradmin',
            //     is_admin: false,
            //     UsuarioId: $rootScope.username.UserId,
            //     Envia: $rootScope.username.NombreCompleto,
            //     msg: "Se ha Actualizado el proceso N° " + vm.UProceso.ProcesoId
            // }))
            swal("Enhorabuena!", "Se han guardado los datos satisfactoriamente", "success")
            _init()
            $('#EditProcesoModal').modal('hide')
          }
        })
      }).error(function () {
        swal("Error!", 'Faltan por llenar campos obligatorios. ', 'error')
      })
    }

    vm.EnviarSolicitud = function () {
      if (vm.IsLoading) {
        return false
      }
      if (vm.Datos.$valid) {
        var count = 0
        for (var i = 0; i < vm.input.length; i++) {
          var x = vm.input[i].label
          if (document.getElementById(vm.input[i].label) != null) {
            var requerido = $(document.getElementById(vm.input[i].label)).attr("validator-required")
            if (requerido === "true") {
              count++
            }
          }
        }
        $validator.validate($scope, 'JSON').success(function () {
          if (count != 0) {
            swal("Error!", 'Faltan por llenar campos obligatorios. ', 'error')
          } else {
            vm.Proceso.DatosFormulario = angular.toJson(vm.input)
            var obj = {
              Proceso: angular.toJson([vm.Proceso])
            }
            vm.IsLoading = true
            ProcesosService.postProcesos(obj).then(function (d) {
              vm.IsLoading = false
              if (typeof d.data === "string") {
                swal("Error!", d.data, 'error')
              } else {
                $rootScope.$broadcast('ProcesoId_new', {
                  ProcesoId: d.data[0]
                })
                // conn.send(JSON.stringify({
                //     event: 'foradmin',
                //     is_admin: false,
                //     UsuarioId: $rootScope.username.UserId,
                //     Envia: $rootScope.username.NombreCompleto,
                //     msg: "Se ha creado un nuevo proceso "
                // }))
                swal("Enhorabuena!", "Se han guardado los datos satisfactoriamente", "success")
                _init()
                $('#ProcesoModal').modal('hide')
              }
            }).catch(error => {
              vm.IsLoading = false
            })
          }
        }).error(function () {
          swal("Error!", 'Faltan por llenar campos obligatorios. ', 'error')
        })
      }
    }
    vm.ChangeSede = function () {
      GetServicio()
    }
    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="Consulta">
    function GetProcesos() {
      vm.cargado = false
      ProcesosService.GetAllProcesos($rootScope.username.UserId).then(function (d) {
        console.log(d.data)
        vm.simpleTableOptions = {
          data: [],
          aoColumns: [{
              mData: 'ProcesoId'
            },
            {
              mData: 'Sede'
            },
            {
              mData: 'Servicio'
            },
            {
              mData: 'Protocolo'
            },
            {
              mData: 'Nombre'
            },
            {
              mData: 'CreatedAt'
            },
            {
              mData: 'Estado'
            },
            {
              mData: 'Opciones'
            },
          ],
          "searching": true,
          //                "sDom": "Tflt<'row DTTTFooter'<'col-sm-6'i><'col-sm-6'p>>",
          //                "oTableTools": {
          //                    "aButtons": [
          //                        "xls", "pdf"
          //                    ],
          //                    "sSwfPath": "assets/swf/copy_csv_xls_pdf.swf"
          //                },
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
        }
        vm.simpleTableOptions.data = SetFormat(d.data)
        vm.cargado = true
      })
    }

    function GetProtocolos() {
      ProtocoloService.GetAllProtocolo($rootScope.username.UserId).then(function (d) {
        console.log(d.data)
        vm.Protocolos = d.data
      })
    }

    function GetSede() {
      SedeService.getAllSedeByUserId($rootScope.username.UserId).then(function (c) {
        vm.Sedes = c.data
        vm.Proceso.SedeId = c.data[0].SedeId
        GetServicio()
      })
    }

    function GetServicio() {
      ServicioService.getServicioByUserId($rootScope.username.UserId).then(function (c) {
        vm.Servicios = $filter("orderBy")($filter('filter')(c.data, {
          SedeId: vm.Proceso.SedeId
        }), "Nombre")
        for (var k in vm.Servicios) {
          if (vm.Servicios[k].IsVisible && vm.Proceso.ServicioId === "--") {
            vm.Proceso.ServicioId = vm.Servicios[k].ServicioId
            break
          }
        }
      })
    }

    function _init() {
      GetProcesos()
      GetProtocolos()
      GetSede()
    }
    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="Helpers">
    function SetFormat(lst) {
      for (var i in lst) {
        lst[i].Opciones = ""
        if (lst[i].Estado === 'Rechazado') {
          lst[i].Opciones += '<a class="btn  btn-info btn-xs icon-only white" onclick=\"angular.element(this).scope().EditProceso(' + i + ')\"><i class="fa fa-pencil"></i></a>'
        }
        lst[i].Opciones += '<a class="btn  btn-primary btn-xs icon-only white" onclick=\"angular.element(this).scope().ShowProceso(' + i + ')\"><i class="fa fa-info"></i></a>'

      }
      return lst
    }

    function b64toBlob(b64Data, contentType, sliceSize) {
      contentType = contentType || ''
      sliceSize = sliceSize || 512

      var byteCharacters = atob(b64Data)
      var byteArrays = []

      for (var offset = 0; offset < byteCharacters.length; offset += sliceSize) {
        var slice = byteCharacters.slice(offset, offset + sliceSize)

        var byteNumbers = new Array(slice.length)
        for (var i = 0; i < slice.length; i++) {
          byteNumbers[i] = slice.charCodeAt(i)
        }

        var byteArray = new Uint8Array(byteNumbers)

        byteArrays.push(byteArray)
      }

      var blob = new Blob(byteArrays, {
        type: contentType
      })
      return blob
    }
    //</editor-fold>
  }
])