app.controller('CreateOrdenCompraCtrl', ["$scope", "$rootScope", "ProcesosService", "SesionService",
  function ($scope, $rootScope, ProcesosService, SesionService) {
    // VARIABLES
    let vm = $scope
    const u = SesionService.get("UserData_Polivalente")
    if (u != undefined) {
      $rootScope.username = u
    }
    vm.ProcesosVerificacion = []
    vm.IsLoading = false
    vm.Orden = {
      Fecha: moment().format('YYYY-MM-DD'),
      ProcesoId: window.ProcesoId,
      ProcesoVerificacionId: null,
      NumeroCotizacion: '',
      VendedorId: null,
      NombreEmpresa: '',
      NitEmpresa: '',
      DireccionEmpresa: '',
      TelefonoEmpresa: '',
      EnvNombre: 'Saray Lorena Pacheco Meriño',
      EnvEmpresa: 'Clinica Integral de Emergencias Laura Daniela',
      EnvDireccion: 'Carrera 19 # 14-47 Barrio San Vicente',
      EnvCiudad: 'Valledupar - Cesar',
      EnvTel: '3155262011',
      SedeId: window.SedeId,
      ServicioId: window.ServicioId,
      Sede: window.Sede,
      Servicio: window.Servicio,
      NombreElaborado: u.NombreCompleto,
      CargoElaborado: u.Cargo,
      ElaboradoId: u.UserId,
      FormaPago: '',
      Observacion: '',
      SubTotal: null,
      Total: null,
      Iva: null,
      Envio: null,
      Otro: null,
      Detalles: [
        {
          Item: '',
          Descripcion: '',
          Cantidad: '',
          PrecioUnitario: '',
          Total: ''
        }
      ]
    }
    // EVENTOS
    vm.inputChanged = (str) => {
      vm.Orden.NombreEmpresa = str
      vm.DISABLECAMPOS = false
    }
    vm.LoadVendedor = (vendedor) => {
      vm.Orden.VendedorId = vendedor.originalObject.VendedorId
      vm.Orden.NombreEmpresa = vendedor.originalObject.Nombre
      vm.Orden.NitEmpresa = vendedor.originalObject.Nit
      vm.Orden.DireccionEmpresa = vendedor.originalObject.Direccion
      vm.Orden.TelefonoEmpresa = vendedor.originalObject.Telefono
      vm.DISABLECAMPOS = true
    }
    vm.CrearOrdenCompra = () => {
      if (!vm.Datos.$valid) {
        angular.element("[name='" + vm.Datos.$name + "']").find('.ng-invalid:visible:first').focus()
        return false
      } else if (vm.Orden.Detalles.lenght === 0) {
        swal("Error!",'Debes añadir un detalle como minimo.', 'error')
        return false
      } else if (vm.Orden.NombreEmpresa === '') {
        swal("Error!",'Debes añadir el nombre de la empresa.', 'error')
        return false
      }
      if (vm.IsLoading) {
        return false
      } else {
        // valores 
        vm.Orden.OrdenEnCurso = 0
        vm.Orden.CreatedBy = 0
        const obj = {
          OrdenCompra: JSON.stringify(vm.Orden)
        }
        vm.IsLoading = true
        ProcesosService.postProcesos(obj).then(r => {
          vm.IsLoading = false
          if (r.data.data) {
            window.opener.notify(r.data.data)
            window.close()
          } else {
            swal("Error!", r.data.error, 'error')
          }
        }).catch(err => { vm.IsLoading = false })
      }
    }
    vm.EliminarDetalle = (i) => {
      vm.Orden.Detalles.splice(i, 1)
    }
    vm.AddDetalle = () => {
      vm.Orden.Detalles.push(
        {
          Item: '',
          Descripcion: '',
          Cantidad: '',
          PrecioUnitario: '',
          Total: ''
        }
      )
    }
    // CONSULTAS
    function getProcesosOrdenCompra () {
      ProcesosService.getProcesoOrdenCompra().then(r => {
        vm.ProcesosVerificacion = r.data.data || []
      })
    }
    // HELPERS
    vm.$on('$destroy', function () {
      vm = null
      $scope = null
    })

    function _init_() {
      if (Number.isInteger(window.ProcesoId)) {
        vm.Orden = {
          Fecha: moment().format('YYYY-MM-DD'),
          ProcesoId: window.ProcesoId,
          NumeroCotizacion: '',
          NombreEmpresa: '',
          NitEmpresa: '',
          DireccionEmpresa: '',
          TelefonoEmpresa: '',
          EnvNombre: 'Saray Lorena Pacheco Meriño',
          EnvEmpresa: 'Clinica Integral de Emergencias Laura Daniela',
          EnvDireccion: 'Carrera 19 # 14-47 Barrio San Vicente',
          EnvCiudad: 'Valledupar - Cesar',
          EnvTel: '3155262011',
          SedeId: window.SedeId,
          ServicioId: window.ServicioId,
          Sede: window.Sede,
          Servicio: window.Servicio,
          NombreElaborado: u.NombreCompleto,
          CargoElaborado: u.Cargo,
          ElaboradoId: u.UserId,
          FormaPago: '',
          Observacion: '',
          SubTotal: 0,
          Total: 0,
          Iva: 0,
          Envio: 0,
          Otro: 0,
          Detalles: [
            {
              Item: '',
              Descripcion: '',
              Cantidad: '',
              PrecioUnitario: '',
              Total: ''
            }
          ]
        }
      } else {
        window.close()
      }
      getProcesosOrdenCompra()
    }
    _init_()
  }
])