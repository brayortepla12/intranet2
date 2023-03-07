app.controller('ActaSistemasCtrl', [
  '$scope',
  '$rootScope',
  '$filter',
  'SedeService',
  'ServicioService',
  'HojaVidaSistemaService',
  'EncabezadoService',
  'UsuarioService',
  'ActaSistemasService',
  'EmpresaService',
  function (
    $scope,
    $rootScope,
    $filter,
    SedeService,
    ServicioService,
    HojaVidaSistemaService,
    EncabezadoService,
    UsuarioService,
    ActaSistemasService,
    EmpresaService
  ) {
    //<editor-fold defaultstate="collapsed" desc="Variables">
    $scope.simpleTableOptions = {}
    $scope.cargado = false
    $scope.Usuarios = []
    $scope.HojaVidaSeleccionada = null
    $scope.labels = {
      itemsSelected: 'Seleccionados',
      selectAll: 'Seleccionar Todo',
      unselectAll: 'Deseleccionar Todo',
      search: 'Buscar',
      select: 'Seleccionar'
    }
    $scope.Acta = {
      Fecha: moment().format('YYYY-MM-DD'),
      MensajeIntroductorio: '',
      Motivo: '',
      Descripcion: '',
      Nota: '',
      RecibeN: '',
      RecibeC: '',
      Destino: '',
      Area: '',
      ServicioId: '',
      RecibeId: '',
      Detalles: [],
      ResponsableId: $rootScope.username.UserId,
      TipoActa: '',
      CreatedBy: $rootScope.username.NombreCompleto
    }
    $scope.SedeId = ''
    $scope.ServicioId = ''
    $scope.Sedes = []
    $scope.Servicios = []
    $scope.Equipos = []
    $scope.Encabezado = []
    $scope.NewActaBn = false
    $scope.VerActaBn = false
    $scope.cargado = false
    $scope.simpleTableOptions = {}
    $scope.UsuarioSeleccionado = {}
    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="Eventos">
    $scope.ChangeDestino = () => {
      $scope.Acta.Descripcion = `El cual será entregada a ${$scope.Acta.Destino}.`
      $scope.Acta.Motivo = `TRASLADO EN CALIDAD DE PRESTAMO PARA ${$scope.Acta.Destino}`
    }
    $scope.Imprimir = () => {
      ImprimirDiv()
    }
    $scope.ChangeSede = function () {
      GetServicio()
    }
    $scope.ChangeServicio = function () {
      GetEquipos()
    }
    $scope.Atras = () => {
      $scope.NewActaBn = false
      $scope.VerActaBn = false
      $scope.Acta = {
        Fecha: moment().format('YYYY-MM-DD'),
        MensajeIntroductorio: '',
        Motivo: '',
        Descripcion: '',
        Nota: '',
        RecibeN: '',
        RecibeC: '',
        Destino: '',
        Area: '',
        ServicioId: '',
        RecibeId: '',
        Detalles: [],
        ResponsableId: $rootScope.username.UserId,
        TipoActa: '',
        CreatedBy: $rootScope.username.NombreCompleto
      }
      $scope.UsuarioSeleccionado = {}
      GetActas()
    }
    $scope.VerActa = i => {
      $scope.VActa = $scope.simpleTableOptions.data[i]
      GetDetallesByActaId()
      $scope.VerActaBn = true
    }
    $scope.GuardarActa = () => {
      $scope.Acta.CreatedBy = $rootScope.username.NombreCompleto
      if ($scope.item.Elemento != '' && $scope.item.Elemento.length > 3) {
        $scope.AddItem()
      }
      if ($scope.Acta.TipoActa == 'Salida') {
        $scope.Acta.ServicioId = null
        if ($scope.Acta.RecibeN.length <= 3) {
          swal('Error', 'Debes añadir el nombre de quien recibe.', 'error')
          return ''
        }
        if ($scope.Acta.Destino.length <= 3) {
          swal('Error', 'Debes añadir un destino.', 'error')
          return ''
        }
      } else if ($scope.Acta.TipoActa == 'Entregar') {
        $scope.Acta.ServicioId = $scope.ServicioId
        if ($scope.Acta.RecibeId.length === 1) {
          swal('Error', 'Debes seleccionar un usuario que recibe.', 'error')
          return ''
        }
      } else if ($scope.Acta.TipoActa == 'Baja') {
        $scope.Acta.ServicioId = $scope.ServicioId
      }

      if ($scope.Acta.Detalles.length === 0) {
        swal('Error', 'Debes añadir un item.', 'error')
        return ''
      }
      if ($scope.Acta.TipoActa == 'No Mantenimiento') {
        $scope.Acta.ServicioId = $scope.ServicioId
      }
      let obj = {
        ActaSistemas: JSON.stringify($scope.Acta)
      }
      ActaSistemasService.postActaSistemas(obj).then(d => {
        if (typeof d.data != 'string') {
          $scope.Atras()
          swal(
            'Enhorabuena!',
            'Se han guardado los datos satisfactoriamente',
            'success'
          )
        } else {
          swal('Error', d.data, 'error')
        }
      })
    }
    $scope.NewActa = () => {
      $scope.NewActaBn = true
      $scope.Acta = {
        Fecha: moment().format('YYYY-MM-DD'),
        MensajeIntroductorio: '',
        Motivo: '',
        Descripcion: '',
        Nota: '',
        RecibeN: '',
        RecibeC: '',
        Destino: '',
        Area: '',
        ServicioId: '',
        RecibeId: '',
        Detalles: [],
        ResponsableId: $rootScope.username.UserId,
        TipoActa: '',
        CreatedBy: $rootScope.username.NombreCompleto
      }
      $scope.item = {
        Cantidad: 1,
        Elemento: '',
        Marca: '',
        Modelo: '',
        Serial: '',
        HojaVidaId: null
      }
    }
    $scope.AddItem = () => {
      if ($scope.item.Elemento != '' && $scope.item.Elemento.length >= 1) {
        $scope.Acta.Detalles.push($scope.item)
        $scope.item = {
          Cantidad: 1,
          Elemento: '',
          Marca: '',
          Modelo: '',
          Serial: '',
          HojaVidaId: null
        }
      }

      $scope.HojaVidaSeleccionada = {}
    }
    $scope.DeleteDetalle = i => {
      $scope.Acta.Detalles.splice(i, 1)
    }
    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="Watchers">
    $scope.$watch('HojaVidaSeleccionada', function (newValue, oldValue) {
      if (newValue) {
        if (newValue[0]) {
          $scope.item = {
            Cantidad: 1,
            Elemento: `${newValue[0].TipoArticulo} ${newValue[0].Equipo}`,
            Marca: newValue[0].Fabricante,
            Modelo: newValue[0].Modelo,
            Serial: newValue[0].NSerial,
            HojaVidaId: newValue[0].HojaVidaId
          }
        }
      }
    })

    $scope.$watch('Acta.TipoActa', function (newValue, oldValue) {
      if (newValue) {
        VerificarTipoActa(newValue)
      }
    })

    $scope.$watch('UsuarioSeleccionado', function (newValue, oldValue) {
      if (newValue) {
        if (newValue[0]) {
          $scope.Acta.RecibeId = newValue[0].UsuarioId
        }
      }
    })
    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="CONSULTAS">
    function GetActas() {
      $scope.cargado = false
      $scope.simpleTableOptions = {}
      ActaSistemasService.GetAll().then(d => {
        $scope.simpleTableOptions = {
          data: SetFormat(d.data),
          aoColumns: [
            { mData: 'ActaId' },
            { mData: 'NumeroActa' },
            { mData: 'Fecha' },
            { mData: 'Responsable' },
            { mData: 'TipoActa' },
            { mData: 'Servicio' },
            { mData: 'Recibe' },
            { mData: 'Opciones' }
          ],
          searching: true,
          iDisplayLength: 25,
          language: {
            lengthMenu: 'Mostrar _MENU_ registros por página',
            zeroRecords: ' No hay Items Registrados ',
            infoFiltered: '(filtro de _MAX_ registros totales)',
            search: ' Filtrar : ',
            oPaginate: {
              sPrevious: 'Anterior',
              sNext: 'Siguiente'
            }
          },
          aaSorting: []
        }
        $scope.cargado = true
      })
    }
    function GetSede() {
      SedeService.getAllSedeByUserId($rootScope.username.UserId).then(function (
        c
      ) {
        $scope.Sedes = $filter('orderBy')(c.data, 'Nombre')
        $scope.SedeId = $scope.Sedes[0].SedeId
        GetServicio()
      })
    }
    function GetServicio() {
      ServicioService.getServicioBySedeAndUserId(
        $scope.SedeId,
        $rootScope.username.UserId
      ).then(function (c) {
        $scope.Servicios = $filter('orderBy')(c.data, 'Nombre')
        $scope.ServicioId = $scope.Servicios[0].ServicioId
        GetEquipos()
      })
    }
    function GetEquipos() {
      HojaVidaSistemaService.GetHojaVida($scope.ServicioId).then(function (e) {
        $scope.Equipos = $filter('orderBy')(e.data, 'Ubicacion')
        GetUsuarios()
      })
    }
    function GetEncabezado() {
      EncabezadoService.getEncabezado().then(function (e) {
        $scope.Encabezado = e.data
      })
    }
    function GetUsuarios() {
      UsuarioService.GetALLusuariosByServicio($scope.ServicioId).then(function (
        u
      ) {
        $scope.Usuarios = $filter('orderBy')(u.data, 'NombreCompleto')
      })
    }
    function GetDetallesByActaId() {
      ActaSistemasService.GetDetallesByActaId($scope.VActa.ActaId).then(d => {
        $scope.VActa.Detalles = d.data
      })
    }
    function GetEmpresa() {
      EmpresaService.getEmpresa().then(function (e) {
        $scope.Empresa = e.data
      })
    }
    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="Helpers">
    function ImprimirDiv() {
      $('#ImprimeActa').print({
        globalStyles: true,
        mediaPrint: false,
        stylesheet: null,
        noPrintSelector: '.no-print',
        iframe: true,
        append: null,
        prepend: null,
        manuallyCopyFormValues: true,
        deferred: $.Deferred(),
        timeout: 2500,
        title: null,
        doctype: '<!doctype html>'
      })

      setTimeout(function () {
        $scope.ToPrint = false
        $scope.$apply()
      }, 1000)
    }
    function SetFormat(lst) {
      for (var i in lst) {
        lst[i].Opciones =
          '<a class="btn  btn-primary btn-xs icon-only white" onclick="angular.element(this).scope().VerActa(' +
          i +
          ')"><i class="fa fa-info"></i></a>'
      }
      return lst
    }
    function VerificarTipoActa(newValue) {
      if (newValue == 'Entrega') {
        $scope.Acta.MensajeIntroductorio =
          'En el presente oficio queda plasmada la entrega del siguiente elemento:'
        for (let s in $scope.Servicios) {
          if ($scope.Servicios[s].ServicioId == $scope.ServicioId) {
            $scope.Acta.Descripcion = `Este articulo ha sido entregado según la necesidad del Área de ${$scope.Servicios[s].Nombre}`
            break
          }
        }
        $scope.Acta.Nota =
          'NOTA: Se le informa que el daño del elemento aqui relacionado será cobrado a la persona responsable de su manipulación.'
        $scope.Acta.Motivo = ''
      } else if (newValue == 'Salida') {
        $scope.Acta.MensajeIntroductorio =
          'El departamento de Sistemas autoriza la salida de:'
        for (let s in $scope.Sedes) {
          if ($scope.Sedes[s].SedeId == $scope.SedeId) {
            $scope.Acta.Descripcion = `El cual será entregada a ${$scope.Sedes[s].Nombre}.`
            $scope.Acta.Motivo = `TRASLADO EN CALIDAD DE PRESTAMO PARA ${$scope.Sedes[s].Nombre}`
            break
          }
        }
        $scope.Acta.Nota = ''
      } else if (newValue == 'Baja') {
        $scope.Acta.MensajeIntroductorio =
          'El departamento de Sistemas hace acta de baja de:'
        for (let s in $scope.Sedes) {
          if ($scope.Servicios[s].ServicioId == $scope.ServicioId) {
            $scope.Acta.Descripcion = `que pertenece al área de ${$scope.Servicios[s].Nombre}.`
            break
          }
        }
        $scope.Acta.Nota = ''
        $scope.Acta.Motivo = ''
      } else if (newValue == 'No Mantenimiento') {
        $scope.Acta.MensajeIntroductorio =
          'El departamento de Sistemas hace acta de no mantenimiento de:'
        for (let s in $scope.Sedes) {
          if ($scope.Servicios[s].ServicioId == $scope.ServicioId) {
            $scope.Acta.Descripcion = `que pertenece al área de ${$scope.Servicios[s].Nombre}.`
            break
          }
        }
        $scope.Acta.Nota = ''
        $scope.Acta.Motivo = ''
      }
    }
    //</editor-fold>
    function _init() {
      GetSede()
      GetEncabezado()
      GetActas()
      GetEmpresa()
    }
    _init()
  }
])
