app.controller('PersonalCtrl', ["$scope", "$rootScope", "$state", "$crypto", "$filter", "PersonaService", "SedeService", "ServicioService",
    "ControlService", "ColaboradoresService", "PermisoCTService", 'LideresService', 'UsuarioService',
    function ($scope, $rootScope, $state, $crypto, $filter, PersonaService, SedeService, ServicioService, ControlService,
            ColaboradoresService, PermisoCTService, LideresService, UsuarioService) {
        //<editor-fold defaultstate="collapsed" desc="Variables">
        let vm = $scope
        vm.UsuarioP = {}
        vm.IsAdminSistemas = $rootScope.username.IsAdminSistemas
        vm.IsSistemas = $rootScope.username.IsSistemas
        vm.PersonaIdPrev = null
        vm.selected = {}
        vm.Tabla = []
        vm.events = []
        vm.Mes = moment().format('M')
        vm.Year = moment().format('YYYY')
        vm.Estado = 'Activo'
        vm.simpleTableOptions = {}
        vm.Turnos = []
        vm.Horarios = []
        vm.cargado = false
        vm.ActualizarBool = false
        vm.Lideres = []
        vm.Cargos = []
        vm.Biometrico = []
        vm.image = ""
        vm.DataImg = null
        vm.DataImg2 = null
        vm.image2 = null
        vm.labels = {
            "itemsSelected": "Seleccionados",
            "selectAll": "Seleccionar Todo",
            "unselectAll": "Deseleccionar Todo",
            "search": "Buscar",
            "select": "Seleccionar"
        }
        vm.Persona = {
            SedeId: "",
            ServicioId: "",
            PrimerNombre: "",
            SegundoNombre: "",
            PrimerApellido: "",
            SegundoApellido: "",
            Genero: "M",
            Cedula: "",
            Usuario: "",
            Celular: "",
            Correo: "",
            CodigoTarjeta: "",
            FechaNacimiento: "",
            IsAdOrAsist: "Administrativo",
            HasHorarioFijo: "1",
            Rh: "Desconocido",
            Foto: "",
            Firma: "",
            TipoPersona: "Colaborador",
            Cargo: [],
            Jefe: [],
            TurnoId: ''
        }
        vm.Sedes = []
        vm.Servicios = []
        vm.Dispositivos = []
        vm.Usuarios = []
        vm.BuscarPermiso = false
        vm.Permiso = {}
        var artyom
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Eventos-Botones">
        vm.FirmarWacom = () => {
            window.SigCapt.init({
                licenceString: "eyJhbGciOiJSUzUxMiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiI3YmM5Y2IxYWIxMGE0NmUxODI2N2E5MTJkYTA2ZTI3NiIsImV4cCI6MjE0NzQ4MzY0NywiaWF0IjoxNTYwOTUwMjcyLCJyaWdodHMiOlsiU0lHX1NES19DT1JFIiwiU0lHQ0FQVFhfQUNDRVNTIl0sImRldmljZXMiOlsiV0FDT01fQU5ZIl0sInR5cGUiOiJwcm9kIiwibGljX25hbWUiOiJTaWduYXR1cmUgU0RLIiwid2Fjb21faWQiOiI3YmM5Y2IxYWIxMGE0NmUxODI2N2E5MTJkYTA2ZTI3NiIsImxpY191aWQiOiJiODUyM2ViYi0xOGI3LTQ3OGEtYTlkZS04NDlmZTIyNmIwMDIiLCJhcHBzX3dpbmRvd3MiOltdLCJhcHBzX2lvcyI6W10sImFwcHNfYW5kcm9pZCI6W10sIm1hY2hpbmVfaWRzIjpbXX0.ONy3iYQ7lC6rQhou7rz4iJT_OJ20087gWz7GtCgYX3uNtKjmnEaNuP3QkjgxOK_vgOrTdwzD-nm-ysiTDs2GcPlOdUPErSp_bcX8kFBZVmGLyJtmeInAW6HuSp2-57ngoGFivTH_l1kkQ1KMvzDKHJbRglsPpd4nVHhx9WkvqczXyogldygvl0LRidyPOsS5H2GYmaPiyIp9In6meqeNQ1n9zkxSHo7B11mp_WXJXl0k1pek7py8XYCedCNW5qnLi4UCNlfTd6Mk9qz31arsiWsesPeR9PN121LBJtiPi023yQU8mgb9piw_a-ccciviJuNsEuRDN3sGnqONG3dMSA"
            });
            $(window).on('sigCapt:renderBitmap', function (e, imageBase64) {
                console.log('Signature captured:', imageBase64);
                vm.Persona.Firma = imageBase64;
                vm.$apply()
            });
            window.SigCapt.capture(
                `${vm.Persona.PrimerNombre} ${vm.Persona.PrimerApellido}`,
                'Firma digitalmente'
            );
        }
        vm.DiceNombres = (Nombre, Apellido) => {
            artyom.say(`${Nombre} ${Apellido}`.toLowerCase())
        }
        vm.VerColaboradores = (PersonaId) => {
            var encrypted = $crypto.encrypt(PersonaId, 'Franklin Ospino')
            $state.go("bio.listado_personal", {PersonaId: encrypted})
        }
        vm.VerPermiso = (PermisoId) => {
            vm.Permisos = []
            console.log(PermisoId)
            PermisoCTService.getPermisoByPermisoId(PermisoId).then((d) => {
                if (typeof d.data != 'string' && d.data.length > 0) {
                    vm.BuscarPermiso = true
                    vm.Permiso = d.data[0]
                }
                console.log(d.data)
            })
        }
        vm.getPersonas = () => {
            GetPersonas()
        }
        vm.GetHorario = (PersonaId, Nombres) => {
            vm.events = []
            vm.BuscarPermiso = false
            vm.Permiso = {}
            vm.Nombres = Nombres
            $('#HorarioModal').modal('show')
            ColaboradoresService.getHorarioByColaboradorId(vm.Year, vm.Mes, PersonaId).then((d) => {
                console.log(d.data)
                vm.Horario = d.data
                for (var i in d.data) {
                    vm.Rondas = angular.copy(d.data)
                    let ev = {
                        title: d.data[i].HoraInicio + "-" + d.data[i].HoraFin,
                        start: new Date(toDate(d.data[i].DiaMes)),
                        end: new Date(toDate(d.data[i].DiaMes)),
                        allDay: true
                    }
                    vm.events.push(ev)
                }
                let _date = new Date(vm.Year, vm.Mes, 1)
                vm.mdEventCalendar.selectMonth(_date)
            })
        }
        vm.GetEstadistica = (PersonaId, Nombres) => {
            vm.BuscarPermiso = false
            vm.Permiso = {}
            vm.Permisos = []
            vm.Nombres = Nombres
            ColaboradoresService.getListadoES(vm.Year, vm.Mes, PersonaId).then((d) => {
                console.log(d.data)
                vm.ListadosES = d.data
                $('#ListadoESModal').modal('show')
            })
        }
        vm.ChangeTurno = () => {
            GetHorarioByTurno()
        }
        vm.ChangeEstadoPersona = (i, EstadoNuevo) => {
            let obj = {
                PersonaId_ne: vm.simpleTableOptions.data[i].PersonaId,
                EstadoNuevo_ne: EstadoNuevo,
                ModifiedBy_ne: $rootScope.username.NombreCompleto
            }
            PersonaService.putPersona(obj).then((d) => {
                if (typeof d.data !== "string") {
                    swal("Enhorabuena", "Se han actualizado los datos con exito", "success")
                    GetPersonas()
                }
            })
        }
        vm.Consultar = () => {
            if (!vm.Consultar_data.$valid) {
                angular.element("[name='" + vm.Consultar_data.$name + "']").find('.ng-invalid:visible:first').focus()
            } else {
                ControlService.getBiometricoByPersonaId(vm.Persona.PersonaId, vm.Desde, vm.Hasta).then((d) => {
                    console.log(d.data)
                    vm.Biometrico = d.data
                })
            }
        }
        vm.OpenCrearModal = () => {
            document.getElementById("firma-persona").value = ""
            document.getElementById("foto-persona").value = ""
            $scope.$broadcast('angucomplete-alt:clearInput')
            vm.UsuarioP = {}
            vm.ActualizarBool = false
            vm.image = null
            vm.image2 = null
            vm.DataImg = null
            vm.DataImg2 = null
            vm.Horarios = []
            vm.Persona = {
                SedeId: "",
                ServicioId: "",
                PrimerNombre: "",
                SegundoNombre: "",
                PrimerApellido: "",
                SegundoApellido: "",
                Genero: "M",
                Cedula: "",
                Usuario: "",
                Celular: "",
                Correo: "",
                CodigoTarjeta: "",
                FechaNacimiento: "",
                IsAdOrAsist: "Administrativo",
                HasHorarioFijo: "1",
                Rh: "Desconocido",
                Foto: "",
                Firma: "",
                TipoPersona: "Colaborador",
                Cargo: [],
                Jefe: [],
                TurnoId: ''
            }
            GetTurnos()
            GetSede()
            GetUsuarios()
        }
        vm.ModalReporte = (i) => {
            vm.Biometrico = []
            vm.Persona = angular.copy(vm.simpleTableOptions.data[i])
            $('#ReporteModal').modal('show')
        }
        vm.ModalEditarPersona = (PersonaId) => {
            document.getElementById("firma-persona").value = ""
            document.getElementById("foto-persona").value = ""
            $scope.$broadcast('angucomplete-alt:clearInput')
            vm.UsuarioP = {}
            vm.Horarios = []
            vm.DataImg = null
            vm.DataImg2 = null
            vm.image = null
            vm.image2 = null
            GetUsuarios()
            PersonaService.getPersonaById(PersonaId).then((d) => {
                vm.Persona = angular.copy(d.data[0])
                let Usuario = vm.Usuarios.find((o) => {
                    return o.UsuarioId === d.data[0].UsuarioIntranetId
                })
                if (Usuario) {
                    $scope.$broadcast('angucomplete-alt:changeInput', 'persona-create', Usuario.NombreUsuario)
                }
                vm.UsuarioP = Usuario
                vm.Persona.Cargo = []
                vm.Persona.Jefe = []
                for (let i in vm.Cargos) {
                    if (vm.Cargos[i].CargoId == vm.Persona.CargoId) {
                        vm.Persona.NombreCargo = vm.Cargos[i].Cargo
                        break
                    }
                }
                vm.ActualizarBool = true
                GetTurnoByPersonaId()
                GetHorarioByTurno()
                GetSede()
            })
        }
        vm.ChangeSede = function () {
            GetServicio()
        }
        vm.CrearPersona = () => {
            if (!vm.Datos.$valid) {
                angular.element("[name='" + vm.Datos.$name + "']").find('.ng-invalid:visible:first').focus()
            } else {
                if (vm.Persona.Cargo.length != 0) {
                    vm.Persona.CargoId = vm.Persona.Cargo[0].CargoId
                } else {
                    vm.Persona.CargoId = ""
                }
                if (vm.Persona.Jefe.length != 0) {
                    vm.Persona.JefeId = vm.Persona.Jefe[0].PersonaId
                } else {
                    vm.Persona.JefeId = ""
                }

                if (vm.UsuarioP) {
                    if (vm.UsuarioP.originalObject) {
                        vm.Persona.UsuarioIntranetId = vm.UsuarioP.originalObject.UsuarioId
                    } else {
                        vm.Persona.UsuarioIntranetId = vm.UsuarioP.UsuarioId
                    }
                } else {
                    vm.Persona.UsuarioIntranetId = null
                }
                if (vm.DataImg) {
                    vm.Persona.Foto = vm.DataImg.compressed ? vm.DataImg.compressed.dataURL : ""
                }
                if (vm.DataImg2) {
                    vm.Persona.Firma = vm.DataImg2.compressed ? vm.DataImg2.compressed.dataURL : ""
                }
                vm.Persona.CreatedBy = $rootScope.username.NombreCompleto
                let obj = {Persona: JSON.stringify(vm.Persona)}
                PersonaService.postPersona(obj).then((d) => {
                    if (typeof d.data !== "string") {
                        swal("Enhorabuena", "Se ha guardado los datos con exito", "success")
                        document.getElementById("firma-persona").value = ""
                        $scope.$broadcast('angucomplete-alt:clearInput')
                        $('#PersonaModal').modal('hide')
                        vm.Persona = {
                            SedeId: "",
                            ServicioId: "",
                            PrimerNombre: "",
                            SegundoNombre: "",
                            PrimerApellido: "",
                            SegundoApellido: "",
                            Genero: "M",
                            Cedula: "",
                            Usuario: "",
                            Celular: "",
                            Correo: "",
                            CodigoTarjeta: "",
                            FechaNacimiento: "",
                            IsAdOrAsist: "Administrativo",
                            HasHorarioFijo: "1",
                            Rh: "Desconocido",
                            Foto: "",
                            Firma: "",
                            TipoPersona: "Colaborador",
                            Cargo: [],
                            Jefe: [],
                            TurnoId: ''
                        }
                        vm.UsuarioP = {}
                        vm.image = null
                        vm.image2 = null
                        vm.DataImg = null
                        vm.DataImg2 = null
                        GetPersonas()
                    } else {
                        swal("Error", d.data, "error")
                    }
                })
            }
        }
        vm.ActualizarPersona = () => {
            if (!vm.Datos.$valid) {
                angular.element("[name='" + vm.Datos.$name + "']").find('.ng-invalid:visible:first').focus()
            } else {
                if (vm.Persona.Cargo.length != 0) {
                    if (vm.Persona.CargoId != vm.Persona.Cargo[0].CargoId) {
                        vm.Persona.CargoId = vm.Persona.Cargo[0].CargoId
                    }
                }
                if (vm.Persona.Jefe.length != 0) {
                    if (vm.Persona.JefeId != vm.Persona.Jefe[0].PersonaId) {
                        vm.Persona.JefeId = vm.Persona.Jefe[0].PersonaId
                    }
                }
                if (vm.UsuarioP) {
                    if (vm.UsuarioP.originalObject) {
                        vm.Persona.UsuarioIntranetId = vm.UsuarioP.originalObject.UsuarioId
                    } else {
                        vm.Persona.UsuarioIntranetId = vm.UsuarioP.UsuarioId
                    }
                } else {
                    vm.Persona.UsuarioIntranetId = null
                }
                if (vm.DataImg) {
                    vm.Persona.Foto = vm.DataImg.compressed ? vm.DataImg.compressed.dataURL : ""
                }
                if (vm.DataImg2) {
                    vm.Persona.Firma = vm.DataImg2.compressed ? vm.DataImg2.compressed.dataURL : ""
                }
                vm.Persona.ModifiedBy = $rootScope.username.NombreCompleto
                let obj = {Persona: JSON.stringify(vm.Persona)}
                PersonaService.putPersona(obj).then((d) => {
                    if (typeof d.data !== "string") {
                        swal("Enhorabuena", "Se han actualizado los datos con exito", "success")
                        $scope.$broadcast('angucomplete-alt:clearInput')
                        document.getElementById("firma-persona").value = ""
                        $('#PersonaModal').modal('hide')
                        vm.Persona = {
                            SedeId: "",
                            ServicioId: "",
                            PrimerNombre: "",
                            SegundoNombre: "",
                            PrimerApellido: "",
                            SegundoApellido: "",
                            Genero: "M",
                            Cedula: "",
                            Usuario: "",
                            Celular: "",
                            Correo: "",
                            CodigoTarjeta: "",
                            FechaNacimiento: "",
                            IsAdOrAsist: "Administrativo",
                            HasHorarioFijo: "1",
                            Rh: "Desconocido",
                            Foto: "",
                            Firma: "",
                            TipoPersona: "Colaborador",
                            Cargo: [],
                            Jefe: [],
                            TurnoId: ''
                        }
                        vm.UsuarioP = {}
                        vm.image = null
                        vm.image2 = null
                        vm.DataImg = null
                        vm.DataImg2 = null
                        GetPersonas()
                        GetTurnos()
                    } else {
                        swal("Error", d.data, "error")
                    }
                })
            }

        }
        vm.GetVerHorario = (PersonaId, Nombres) => {
            vm.UltimoDiaMes = getDaysInMonth(vm.Mes, vm.Year)
            vm.Nombres = Nombres
            vm.PersonaIdPrev = PersonaId
            LideresService.getHorarioColaboradores(PersonaId, vm.Mes, vm.Year).then((d) => {
                vm.Horarios = d.data
                vm.BanderaHorario = true
            })
        }
        vm.GetTableHorarios = () => {
            if (vm.BanderaHorario) {
                LideresService.getHorarioColaboradores(vm.PersonaIdPrev, vm.Mes, vm.Year).then((d) => {
                    vm.Horarios = d.data
                    vm.BanderaHorario = true
                })
            }
        }
        vm.ImprimirHorario = () => {
            printDiv("#TablaHorario", "/intranet-2/public_html/styles/ExcelHorarioCM.css")
        }

        vm.ExportarHorario = () => {
            $("#TablaHorario").tableExport({
                formats: ["xlsx"],
                position: "top", // (top, bottom), position of the caption element relative to table, (default: 'bottom')
            })
        }
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Consultas">
        function GetLideres() {
            if (vm.Lideres.length === 0) {
                PersonaService.getLideres().then((d) => {
                    vm.Lideres = d.data
                    if (vm.Persona.JefeId !== "") {
                        for (let i in d.data) {
                            if (d.data[i].PersonaId == vm.Persona.JefeId) {
                                vm.Persona.NombreJefe = d.data[i].Nombres
                                break
                            }
                        }
                    }
                }).then(() => {
                    $('#PersonaModal').modal('show')
                    if (vm.ActualizarBool) {
                        vm.image = vm.Persona.Foto
                    }
                })
            } else {
                $('#PersonaModal').modal('show')
                if (vm.ActualizarBool) {
                    vm.image = vm.Persona.Foto
                }
            }
        }
        function GetHorarioByTurno() {
            PersonaService.getHorario(vm.Persona.TurnoId).then((d) => {
                vm.Horarios = d.data
            })
        }
        function GetTurnoByPersonaId() {
            PersonaService.getTurnosByPersonaId(vm.Persona.PersonaId).then((d) => {
                vm.Turnos = d.data
            })
        }
        function GetTurnos() {
            PersonaService.getTurnos().then((d) => {
                vm.Turnos = d.data
                if (typeof d.data != 'string' && d.data.length > 0) {
                    vm.Persona.TurnoId = d.data[0].TurnoId
                    vm.ChangeTurno()
                }
            })
        }
        function GetCargos() {
            PersonaService.getCargos().then((d) => {
                vm.Cargos = d.data
            })
        }
        function GetDispositivos() {
            PersonaService.getDispositivos().then((d) => {
                vm.Dispositivos = d.data
            })
        }
        function GetUsuarios() {
            if (vm.Usuarios.length === 0) {
                UsuarioService.GetUsuariosCT().then((d) => {
                    if (typeof d.data === "object") {
                        vm.Usuarios = d.data
                    }
                })
            }
        }
        function GetPersonas() {
            vm.cargado = false
            vm.simpleTableOptions.data = []
            PersonaService.getPersonas(vm.Estado).then((d) => {
                vm.simpleTableOptions = {
                    data: [],
                    aoColumns: [
                        {mData: 'PersonaId'},
                        {mData: 'Cedula'},
                        {mData: 'CodigoTarjeta'},
                        {mData: 'PrimerNombre'},
                        {mData: 'SegundoNombre'},
                        {mData: 'PrimerApellido'},
                        {mData: 'SegundoApellido'},
                        {mData: 'TipoPersona'},
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
                vm.simpleTableOptions.data = SetFormat(d.data)
                vm.cargado = true
            })
        }
        function GetServicio() {
            ServicioService.getServicioBySede(vm.Persona.SedeId).then(function (c) {
                vm.Servicios = $filter("orderBy")(angular.copy(c.data), "Nombre")
                vm.Persona.ServicioId = vm.Persona.ServicioId === "" ? vm.Servicios[0].ServicioId : vm.Persona.ServicioId
            }).then(() => {
                GetLideres()
            })
        }
        function GetSede() {
            if (vm.Sedes.length === 0) {
                SedeService.getAllSede().then(function (c) {
                    vm.Sedes = $filter("orderBy")(c.data, "Nombre")
                    vm.Persona.SedeId = vm.Persona.SedeId === "" ? vm.Sedes[0].SedeId : vm.Persona.SedeId
                    GetServicio()
                })
            } else {
                vm.Persona.SedeId = vm.Persona.SedeId === "" ? vm.Sedes[0].SedeId : vm.Persona.SedeId
                GetServicio()
            }
        }

        vm.ImprimirEstadisticas = () => {
            printDiv("#TablaEstadisticas")
        }
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Helpers">
        function toDate(dateStr) {
            var parts = dateStr.split("-")
            return new Date(parts[0], parts[1] - 1, parts[2])
        }
        function printDiv(id) {
            $(id).print({
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
        function SetFormat(lst) {
            for (var i in lst) {
                lst[i].Opciones = ''
                lst[i].Opciones += '<a class="btn  btn-info btn-xs icon-only white" onclick=\"angular.element(this).scope().ModalEditarPersona(' + lst[i].PersonaId + ')\"><i class="fa fa-pencil"></i></a>' +
                        ' <a class="btn  btn-primary btn-xs icon-only white" onclick=\"angular.element(this).scope().ModalReporte(' + i + ')\"><i class="fas fa-file-invoice"></i></a>'
                lst[i].Opciones += `<a class="btn btn-default btn-xs icon-only white" data-toggle="tooltip" title="Ver estadisticas"  onclick=\"angular.element(this).scope().GetEstadistica(${lst[i].PersonaId},'${lst[i].PrimerNombre} ${lst[i].PrimerApellido}')\"><i class="fa fa-line-chart"></i></a>`
                lst[i].Opciones += `<a class="btn btn-default btn-xs icon-only white" data-toggle="tooltip" title="Ver horario"  onclick=\"angular.element(this).scope().GetHorario(${lst[i].PersonaId},'${lst[i].PrimerNombre} ${lst[i].PrimerApellido}')\"><i class="fa fa-table"></i></a>`
                if (lst[i].TipoPersona == 'Lider') {
                    lst[i].Opciones += `<a class="btn btn-warning btn-xs icon-only white" data-toggle="tooltip" title="Ver colaboradores"  onclick=\"angular.element(this).scope().VerColaboradores('${lst[i].UsuarioIntranetId}')\"><i class="fa fa-list-ol"></i></a>`
                    lst[i].Opciones += `<a class="btn btn-warning btn-xs icon-only white" data-toggle="tooltip" title="Ver horario colaboradores"  onclick=\"angular.element(this).scope().GetVerHorario('${lst[i].PersonaId}', '${lst[i].PrimerNombre} ${lst[i].PrimerApellido}')\"><i class="fa fa-table"></i></a>`
                }
                if (lst[i].Estado == 'Activo') {
                    lst[i].Opciones += ` <a class="btn btn-primary btn-xs icon-only white" data-toggle="tooltip" title="Inactivar!" onclick=\"angular.element(this).scope().ChangeEstadoPersona(${i}, ${"'Inactivo'"})\"><i class="fas fa-check"></i></a>`
                } else {
                    lst[i].Opciones += ` <a class="btn btn-danger btn-xs icon-only white" data-toggle="tooltip" title="Activar!" onclick=\"angular.element(this).scope().ChangeEstadoPersona(${i}, ${"'Activo'"})\"><i class="fas fa-times"></i></a>`
                }
            }
            return lst
        }
        //</editor-fold>
        function getDaysInMonth(m, y) {
            return moment(new Date(y, m - 1, 1)).endOf('month').format('DD')
        }

        function startContinuousArtyom() {
            artyom.fatality()// Detener cualquier instancia previa
            setTimeout(function () {// Esperar 250ms para inicializar
                artyom.initialize({
                    lang: "es-ES", // Más lenguajes son soportados, lee la documentación
                    continuous: false, // Artyom obedecera por siempre
                    listen: false, // Iniciar !
                    debug: true, // Muestra un informe en la consola
                    speed: 1 // Habla normalmente
                }).then(function () {
                    console.log("Ready to work !")
                })
            }, 250)
        }
        function __init__() {
            artyom = new Artyom()
            startContinuousArtyom()
            GetPersonas()
            GetCargos()
            GetDispositivos()
//            GetTurnos()
        }
        __init__()
    }])

