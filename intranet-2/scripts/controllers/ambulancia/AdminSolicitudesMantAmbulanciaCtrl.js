'use strict'
app.controller('AdminSolicitudesMantAmbulanciaCtrl', ["$scope", "$rootScope", "SolicitudAmbulanciaService", "EmpresaService",
    "EncabezadoService",
    function ($scope, $rootScope, SolicitudAmbulanciaService, EmpresaService, EncabezadoService) {
        //<editor-fold defaultstate="collapsed" desc="inicializar variables">
        let vm = $scope
        vm.Placa = ''
        vm.BnFREdit = false
        vm.BnFR = false
        vm.Mes = 'TODOS'
        vm.Year = moment().format('YYYY')
        vm.Estado = 'TODOS'
        vm.VerInformacion = ''
        vm.CargandoBandera = false
        vm.Informacion = ""
        vm.cargado = false
        vm.UpdateBandera = false
        vm.Encabezado = null
        vm.Empresa = null
        vm.simpleTableOptions = {}
        vm.labels = {
            "itemsSelected": "Seleccionados",
            "selectAll": "Seleccionar Todo",
            "unselectAll": "Deseleccionar Todo",
            "search": "Buscar",
            "select": "Seleccionar"
        }
        vm.Items = []
        vm.Solicituds = []
        vm.FechaActual = new Date()
        vm.Proveedores = []
        vm.Detalles = []
        vm.Detalle = {
            Precio: 0,
            Cantidad: 1,
            Item: null
        }
        vm.Item = {
            Nombre: "",
            CreatedBy: $rootScope.username.NombreCompleto
        }
        vm.Proveedor = {
            Nombre: "",
            CreatedBy: $rootScope.username.NombreCompleto
        }
        vm.DetallesSeleccionados = []
        vm.Factura = {
            SolicitudMantenimientoId: null,
            Proveedor: {},
            UrlArchivo: {},
            Detalles: [],
            CreatedBy: $rootScope.username.NombreCompleto
        }
        vm.Solicitud = {}
        // inicializamos las variables en _init()
        _init()
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Botones">
        vm.VerSolicitud = (i) => {
            vm.Solicitud = vm.simpleTableOptions.data[i]
            GetEmpresa()
            GetEncabezado()
            $("#VerSolicitudModal").modal('show')
            vm.$apply()
        }
        vm.GetFacturaToEdit = (i) => {
            vm.BnFREdit = true
            GetFacturaForEdit(i)
        }
        vm.RechazarSolicitud = (i) => {
            swal({
                title: "¿Deseas rechazar esta solictud?",
                text: "NOTA: este paso no se puede deshacer.",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Rechazar!",
                closeOnConfirm: false
            },
                    function () {
                        let sm = vm.simpleTableOptions.data[i]
                        var data = {
                            SolicitudMantenimientoId: sm.SolicitudMantenimientoId,
                            Estado: 'Rechazado'
                        }
                        SolicitudAmbulanciaService.PutSolicitud(data).then(function (d) {
                            if (typeof d.data === "string") {
                                swal("Hubo un Error", d.data, "error")
                            } else {
                                swal("Enhorabuena!!", "Se ha rechazado correctamente", "success")
                                GetSolicituds()
                            }
                        }, function (e) {
                            swal("Hubo un Error", e, "error")
                        })
                    })
        }
        vm.AddItem = () => {
            $("#ItemModal").modal('show')
        }
        vm.AddProveedor = () => {
            $("#ProveedorModal").modal('show')
        }
        vm.GuardarItem = () => {
            let item = {
                Item: JSON.stringify(vm.Item)
            }
            SolicitudAmbulanciaService.postSolicitud(item).then((d) => {
                if (typeof d.data != "string" && d.data.length > 0) {
                    vm.Item = {
                        Nombre: "",
                        CreatedBy: $rootScope.username.NombreCompleto
                    }
                    swal("Enhorabuena!", "Se ha añadido el item con exito.", "success")
                    GetItem()
                    $("#ItemModal").modal('hide')
                } else {
                    swal("Error!", d.data, "error")
                }
            })
        }

        vm.GuardarProveedor = () => {
            let Proveedor = {
                Proveedor: JSON.stringify(vm.Proveedor)
            }
            SolicitudAmbulanciaService.postSolicitud(Proveedor).then((d) => {
                if (typeof d.data != "string" && d.data.length > 0) {
                    vm.Proveedor = {
                        Nombre: "",
                        CreatedBy: $rootScope.username.NombreCompleto
                    }
                    swal("Enhorabuena!", "Se ha añadido el item con exito.", "success")
                    GetProveedor()
                    $("#ProveedorModal").modal('hide')
                } else {
                    swal("Error!", d.data, "error")
                }
            })
        }
        vm.DeleteDetalle = (i) => {
            vm.Detalles.splice(i, 1)
        }
        vm.AddDetalle = () => {
            if (vm.Detalle.Item) {
                if (vm.Detalle.Item.Nombre != "" && parseInt(vm.Detalle.Cantidad) > 0 && parseInt(vm.Detalle.Precio) > 0) {
                    vm.Detalles.push(vm.Detalle)
                    vm.Detalle = {
                        Precio: 0,
                        Cantidad: 1,
                        Item: null
                    }
                    $("#ex8_value").val('')
                }
            }
        }
        vm.GetFacturaWithReporte = (i) => {
            vm.BnFR = true
            GetFactura(i)
            GetReporteBySolicitudMant(i)
        }
        vm.GetFactura = (i) => {
            vm.BnFR = false
            GetFactura(i)
        }
        vm.AddFactura = (i) => {
            vm.UpdateBandera = false
            vm.Informacion = vm.simpleTableOptions.data[i].Descripcion
            vm.Factura = {
                SolicitudMantenimientoId: vm.simpleTableOptions.data[i].SolicitudMantenimientoId,
                TipoSolicitud: vm.simpleTableOptions.data[i].TipoSolicitud,
                Proveedor: {},
                UrlArchivo: {},
                Detalles: [],
                CreatedBy: $rootScope.username.NombreUsuario
            }
            vm.$apply()
            $("#FacturaModal").modal('show')
        }
        vm.BuscarSolicituds = () => {
            GetSolicituds()
        }
        vm.ActualizarFactura = () => {
            vm.AddDetalle()
            ActualizarFactura()
        }
        vm.GuardarFactura = function () {
            vm.AddDetalle()
            GuardarFactura()
        }
        vm.GetSuma = (lst) => {
            let Total = 0
            for (let i in lst) {
                Total += lst[i].Precio * lst[i].Cantidad
            }
            return Total
        }
        vm.Imprimir = function (Div) {
            printDiv(Div)
        }
        vm.FiltrarPorPlaca = () => {
            if (vm.Placa.length >= 3) {
                vm.BuscarSolicituds()
            }
        }
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Consultas">
        function GetReporteBySolicitudMant(i) {
            SolicitudAmbulanciaService.GetReporteBySolicitudId(vm.simpleTableOptions.data[i].SolicitudMantenimientoId).then((d) => {
                vm.Reporte = d.data
            })
        }
        function GetFactura(i) {
            SolicitudAmbulanciaService.GetFacturaBySolicitudId(vm.simpleTableOptions.data[i].SolicitudMantenimientoId).then((d) => {
                console.log(d.data)
                vm.VerInformacion = vm.simpleTableOptions.data[i].Descripcion
                
                vm.VerFactura = d.data
                $("#VerFacturaModal").modal('show')
            })
        }
        function GetFacturaForEdit(i) {
            SolicitudAmbulanciaService.GetFacturaBySolicitudId(vm.simpleTableOptions.data[i].SolicitudMantenimientoId).then((d) => {
                console.log(d.data)
                vm.Informacion = vm.simpleTableOptions.data[i].Descripcion
                vm.Factura = d.data
                vm.Detalles = d.data.Detalles
                vm.UpdateBandera = true
                $("#FacturaModal").modal('show')
            })
        }
        function GetSolicituds(arg) {
            vm.cargado = false
            console.log(vm.Placa)
            SolicitudAmbulanciaService.getSolicitudes(vm.Year, vm.Mes, vm.Estado, vm.Placa).then(function (c) {
                vm.simpleTableOptions = {
                    data: [],
                    aoColumns: [
                        {mData: 'SolicitudMantenimientoId'},
                        {mData: 'Fecha'},
                        {mData: 'TipoSolicitud'},
                        {mData: 'Placa'},
                        {mData: 'HaceDias'},
                        {mData: 'Descripcion'},
                        {mData: 'Estado'},
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
                }
                vm.simpleTableOptions.data = SetFormat(c.data)
                vm.cargado = true
            })
        }
        function GetItem() {
            SolicitudAmbulanciaService.getItem().then(function (c) {
                vm.Items = c.data
            })
        }
        function GetProveedor() {
            SolicitudAmbulanciaService.getProveedores().then(function (p) {
                vm.Proveedores = p.data
            })
        }
        function ActualizarFactura(){
            vm.Factura.ModifiedBy = $rootScope.username.NombreCompleto
            vm.Factura.Detalles = JSON.stringify([vm.Detalles])
            var data = {
                Factura: JSON.stringify(vm.Factura)
            }
            vm.CargandoBandera = true
            SolicitudAmbulanciaService.PutSolicitud(data).then(function (d) {
                if (typeof d.data === "string") {
                    swal("Hubo un Error", d.data, "error")
                } else {
                    swal("Enhorabuena!", "Se han actualizar los datos satisfactoriamente", "success")
                    vm.Factura = {
                        SolicitudMantenimientoId: null,
                        Proveedor: {},
                        UrlArchivo: {},
                        Detalles: [],
                        CreatedBy: $rootScope.username.NombreCompleto
                    }
                    vm.Detalles = []
                    vm.Solicituds = []
                    $('#FacturaModal').modal('hide')
                    GetSolicituds()
                }
                vm.CargandoBandera = false
            }, function (e) {
                vm.CargandoBandera = false
                swal("Hubo un Error", e, "error")
            })
        }
        function GuardarFactura() {
            vm.Factura.CreatedBy = $rootScope.username.NombreCompleto
            vm.Factura.Detalles = JSON.stringify([vm.Detalles])
            var data = {
                Factura: JSON.stringify(vm.Factura)
            }
            vm.CargandoBandera = true
            SolicitudAmbulanciaService.postSolicitud(data).then(function (d) {
                if (typeof d.data === "string") {
                    swal("Hubo un Error", d.data, "error")
                } else {
                    swal("Enhorabuena!!", "Se han guardado los datos satisfactoriamente", "success")
                    vm.Factura = {
                        SolicitudMantenimientoId: null,
                        Proveedor: {},
                        UrlArchivo: {},
                        Detalles: [],
                        CreatedBy: $rootScope.username.NombreCompleto
                    }
                    vm.Detalles = []
                    vm.Solicituds = []
                    $('#FacturaModal').modal('hide')
                    GetSolicituds()
//                    _init()
                }
                vm.CargandoBandera = false
            }, function (e) {
                vm.CargandoBandera = false
                swal("Hubo un Error", e, "error")
            })
        }
        function GetEmpresa() {
            EmpresaService.getEmpresa().then(function (e) {
                vm.Empresa = e.data
            })
        }
        function GetEncabezado() {
            EncabezadoService.getEncabezado().then(function (e) {
                vm.Encabezado = e.data
            })
        }
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="HELPERS">
        function SetFormat(lst) {
            for (var i in lst) {
                if (lst[i].Estado == 'Activo') {
                    lst[i].Opciones = '<a class="btn btn-success btn-xs icon-only white" data-toggle="tooltip" title="Añadir factura" onclick=\"angular.element(this).scope().AddFactura(' + i + ')\"' +
                            '><i class="fa fa-plus"></i></a><a class="btn btn-danger btn-xs icon-only white" data-toggle="tooltip" title="Rechazar solicitud" ' +
                            'onclick=\"angular.element(this).scope().RechazarSolicitud(' + i + ')\"' +
                            '><i class="fa fa-trash"></i></a>'
                } else if (lst[i].Estado == 'Vinculado') {
                    lst[i].Opciones = '<a class="btn btn-default btn-xs icon-only white" data-toggle="tooltip" title="Ver factura y reporte" onclick=\"angular.element(this).scope().GetFacturaWithReporte(' + i + ')\"' +
                            '><i class="fa fa-eye"></i></a>'
                } else if(lst[i].Estado == 'Facturado') {
                    lst[i].Opciones = '<a class="btn btn-primary btn-xs icon-only white" onclick=\"angular.element(this).scope().GetFactura(' + i + ')\"' +
                            '><i class="fa fa-eye"></i></a>'
                    if(lst[i].FechaCreacionFactura == moment().format('YYYY-MM-DD')){
                        lst[i].Opciones += '<a class="btn btn-default btn-xs icon-only white" onclick=\"angular.element(this).scope().GetFacturaToEdit(' + i + ')\"' +
                                '><i class="fa fa-pencil"></i></a>'
                    }
                    
                } else {
                    lst[i].Opciones = '<a class="btn btn-primary btn-xs icon-only white"  data-toggle="tooltip" title="Ver factura" ' + 
                    'onclick=\"angular.element(this).scope().GetFactura(' + i + ')\"' +
                            '><i class="fa fa-eye"></i></a>'
                }
                lst[i].Opciones += '<a class="btn btn-default btn-xs icon-only white" data-toggle="tooltip" title="Ver solicitud e imprimir" onclick=\"angular.element(this).scope().VerSolicitud(' + i + ')\"' +
                        '><i class="fa fa-info"></i></a>'
            }
            return lst
        }
        function printDiv(div) {
            $("#" + div).print({
                globalStyles: true,
                mediaPrint: false,
                stylesheet: null,
                noPrintSelector: ".no-print",
                iframe: true,
                append: null,
                prepend: null,
                manuallyCopyFormValues: true,
                deferred: $.Deferred(),
                timeout: 1500,
                title: null,
                doctype: '<!doctype html>'
            })
            
            setTimeout(function () {
                vm.ToPrint = false
                vm.$apply()
            }, 1000)
        }
        //</editor-fold>

        function _init() {
            GetSolicituds()
            GetItem()
            GetProveedor()
        }


    }])







