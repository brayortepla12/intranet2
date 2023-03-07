app.controller('PersonalLiderCtrl', ["$scope", "$rootScope", "$stateParams", "$crypto", "ColaboradoresService", "PermisoCTService", "$$mdEventCalendarUtil", "PersonaService",
    function ($scope, $rootScope, $stateParams, $crypto, ColaboradoresService, PermisoCTService, $$mdEventCalendarUtil, PersonaService) {
        //<editor-fold defaultstate="collapsed" desc="Variables">
        let vm = $scope;
        vm.variables = [];
        vm.Variable = {
            Nombre: "",
            Abreviatura: "",
            FechaInicio: "",
            FechaFin: "",
            FechaInicio2: null,
            FechaFin2: null,
            UsuarioPersona: vm.Usuario,
            CreatedBy: $rootScope.username.NombreCompleto
        };
        vm.BanderaLider = false;
        vm.IsGHUser = $rootScope.username.IsGHUser;
        vm.Persona = {};
        vm.IsAdminSistemas = $rootScope.username.IsAdminSistemas;
        vm.NumeroColaboradoresSeleccionados = 0;
        vm.Lideres = [];
        vm.Colaboradores = [];
        vm.BanderaTraslado = false;
        vm.date = {};
        vm.monthDisplay = {};
        vm.yearDisplay = {};
        vm.isTodayDisabled = {};
        vm.selected = {};
        vm.Tabla = [];
        vm.events = [];
        vm.excelFile = {};
        vm.Funcionario = "";
        vm.Mes = moment().format('M');
        vm.Year = moment().format('YYYY');
        vm.Tipo = "Entrada";
        vm.TipoTurno = "";
        vm.Listados = [];
        vm.ListadosES = [];
        vm.BuscarPermiso = false;
        vm.Usuario = "";
        vm.SubirExcelTurnos = false;
        vm.Permiso = {};
        vm.SelectedFile = {
            name: ''
        };
        vm.Jefe = null;
        vm.BanderaNV = false;
        vm.BanderaUV = false;
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Excel">
        vm.SelectFile = function (file) {
            vm.SelectedFile = file;
        };
        vm.Preview = function () {
            var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xls|.xlsx)$/;
            if (regex.test(vm.SelectedFile.name.toLowerCase())) {
                if (typeof (FileReader) != "undefined") {
                    var reader = new FileReader();
                    //For Browsers other than IE.
                    if (reader.readAsBinaryString) {
                        reader.onload = function (e) {
                            vm.ProcessExcel(e.target.result);
                        };
                        reader.readAsBinaryString(vm.SelectedFile);
                    } else {
                        //For IE Browser.
                        reader.onload = function (e) {
                            var data = "";
                            var bytes = new Uint8Array(e.target.result);
                            for (var i = 0; i < bytes.byteLength; i++) {
                                data += String.fromCharCode(bytes[i]);
                            }
                            vm.ProcessExcel(data);
                        };
                        reader.readAsArrayBuffer(vm.SelectedFile);
                    }
                } else {
                    swal("Error", "This browser does not support HTML5.", "error");
                }
            } else {
                swal("Error", "Por favor subir un archivo de excel valido.", "error");
            }
        };
        vm.ProcessExcel = function (data) {
            //Read the Excel File data.
            var workbook = XLSX.read(data, {
                type: 'binary'
            });
            //Fetch the name of First Sheet.
            var firstSheet = workbook.SheetNames[0];
            //Read all rows from First Sheet into an JSON array.
            var excelRows = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[firstSheet]);
            console.log(excelRows);
            vm.Tabla = excelRows;
            vm.UltimoDiaMes = getDaysInMonth(vm.Mes, vm.Year);
            //Display the data from Excel file in Table.
            vm.$apply(function () {
                vm.IsVisible = true;
            });
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Botones">
        vm.TrasladarColaborador = () => {
            if (!vm.Jefe) {
                swal("Error", "Debes seleccionar el jefe al que vas a tranferir estos colaboradores", "error");
            } else if (vm.NumeroColaboradoresSeleccionados === 0) {
                swal("Error", "Debes seleccionar minimo 1 colaborador", "error");
            } else {
                let lst = [];
                for (var i = 0; i < vm.Colaboradores.length; i++) {
                    if (vm.Colaboradores[i].PASAR == 1) {
                        let o = {
                            PersonaId: vm.Colaboradores[i].PersonaId,
                            JefeId_Origen: vm.Usuario,
                            JefeId_Destino: vm.Jefe[0].PersonaId,
                            ModifiedBy: $rootScope.username.NombreCompleto
                        };
                        lst.push(o);
                    }
                }
                let obj = {
                    Traslados: JSON.stringify([lst])
                };
                PersonaService.putPersona(obj).then((d) => {
                    if (typeof d.data != "string") {
                        swal("Enhorabuena", "Se han guardado los cambios correctamente", "success");
                        vm.NumeroColaboradoresSeleccionados = 0;
                        vm.Lideres = [];
                        vm.Colaboradores = [];
                        vm.BanderaTraslado = false;
                        __init__();
                    } else {
                        swal("Error", d.data, "error");
                    }
                });
            }
        };
        vm.openTrasladarColaboradores = () => {
            vm.BanderaTraslado = true;
        };
        vm.OpenModalMisVariables = () => {
            GetVariables();
            $('#MisVariablesModal').modal('show');
        };
        vm.SelectColaborador = (PersonaId) => {
            vm.NumeroColaboradoresSeleccionados = 0;
            for (var i = 0; i < vm.Colaboradores.length; i++) {
                if (PersonaId == vm.Colaboradores[i].PersonaId) {
                    vm.Colaboradores[i].PASAR == 1 ? 1 : 0;
                }
                if (vm.Colaboradores[i].PASAR == 1) {
                    vm.NumeroColaboradoresSeleccionados++;
                }
            }
        };
        vm.NuevaVariable = () => {
            vm.Variable = {
                Nombre: "",
                Abreviatura: "",
                FechaInicio: "",
                FechaFin: "",
                FechaInicio2: null,
                FechaFin2: null,
                UsuarioPersona: vm.Usuario,
                CreatedBy: $rootScope.username.NombreCompleto
            };
            vm.BanderaNV = true;
        };
        vm.ModificarVariable = (i) => {
            vm.Variable = angular.copy(vm.variables[i]);
            vm.BanderaNV = false;
            vm.BanderaUV = true;
        };
        vm.AtrasVariable = () => {
            vm.BanderaNV = false;
            vm.BanderaUV = false;
            vm.Variable = {
                Nombre: "",
                Abreviatura: "",
                FechaInicio: "",
                FechaFin: "",
                FechaInicio2: null,
                FechaFin2: null,
                UsuarioPersona: vm.Usuario,
                CreatedBy: $rootScope.username.NombreCompleto
            };
        };
        vm.CrearVariable = () => {
            let i,f,i2,f2;
            i = moment(vm.Variable.FechaInicio, 'HH:mm:ss');
            f = moment(vm.Variable.FechaFin,'HH:mm:ss');
            i2 = moment(vm.Variable.FechaInicio2, 'HH:mm:ss');
            f2 = moment(vm.Variable.FechaFin2,'HH:mm:ss');
            if (!vm.Var.$valid) {
                angular.element("[name='" + vm.Var.$name + "']").find('.ng-invalid:visible:first').focus();
            } else if (vm.Variable.FechaFin === "" && vm.Variable.FechaInicio === "") {
                swal("Error", "Debes añadir minimo una fecha inicio y fin", "error");
            } 
//            else if (f.isBefore(i)) {
//                swal("Error", "La fecha fin debe ser mayor que la de inicio", "error");
//            }
            else if ((i2.isBetween(i, f) || i.isBetween(i2, f2) || f.isBetween(i2, f2))) {
                swal("Error", "LAS FECHAS NO PUEDEN CRUZARCE", "error");
            } else {
                let obj = {
                    Variable: JSON.stringify(vm.Variable)
                };
                ColaboradoresService.postPersona(obj).then((d) => {
                    if (typeof d.data !== "string") {
                        swal("Enhorabuena", "Se ha guardado los datos con exito", "success");
                        vm.Variable = {
                            Nombre: "",
                            Abreviatura: "",
                            FechaInicio: "",
                            FechaFin: "",
                            FechaInicio2: null,
                            FechaFin2: null,
                            UsuarioPersona: vm.Usuario,
                            CreatedBy: $rootScope.username.NombreCompleto
                        };
                        vm.BanderaNV = false;
                        GetVariables();
                    } else {
                        swal("Error", d.data, "error");
                    }
                });
            }
        };
        vm.ActualizarVariable = () => {
            vm.ModifiedBy = $rootScope.username.NombreCompleto;
            let i,f,i2,f2;
            i = moment(vm.Variable.FechaInicio, 'HH:mm:ss');
            f = moment(vm.Variable.FechaFin,'HH:mm:ss');
            i2 = moment(vm.Variable.FechaInicio2, 'HH:mm:ss');
            f2 = moment(vm.Variable.FechaFin2,'HH:mm:ss');
            if (!vm.Var.$valid) {
                angular.element("[name='" + vm.Var.$name + "']").find('.ng-invalid:visible:first').focus();
            } else if (vm.Variable.FechaFin === "" && vm.Variable.FechaInicio === "") {
                swal("Error", "Debes añadir minimo una fecha inicio y fin", "error");
            } else if (f.isBefore(i)) {
                swal("Error", "La fecha fin debe ser mayor que la de inicio", "error");
            } else if (f2.isBefore(i2)) {
                swal("Error", "La fecha fin 2 debe ser mayor que la de inicio 2", "error");
            } else if ((i2.isBetween(i, f) || f2.isBetween(i, f) || i.isBetween(i2, f2) || f.isBetween(i2, f2))) {
                swal("Error", "LAS FECHAS NO PUEDEN CRUZARCE", "error");
            } else {
                let obj = {
                    Variable: JSON.stringify(vm.Variable)
                };
                ColaboradoresService.putPersona(obj).then((d) => {
                    if (typeof d.data !== "string") {
                        swal("Enhorabuena", "Se ha guardado los datos con exito", "success");
                        vm.Variable = {
                            Nombre: "",
                            Abreviatura: "",
                            FechaInicio: "",
                            FechaFin: "",
                            FechaInicio2: null,
                            FechaFin2: null,
                            UsuarioPersona: vm.Usuario,
                            CreatedBy: $rootScope.username.NombreCompleto
                        };
                        vm.BanderaNV = false;
                        vm.BanderaUV = false;
                        GetVariables();
                    } else {
                        swal("Error", d.data, "error");
                    }
                });
            }
        };
        vm.GetHorario = (PersonaId, Nombres) => {
            vm.events = [];
            vm.BuscarPermiso = false;
            vm.Permiso = {};
            vm.Nombres = Nombres;
            $('#HorarioModal').modal('show');
            ColaboradoresService.getHorarioByColaboradorId(vm.Year, vm.Mes, PersonaId).then((d) => {
                vm.Horario = d.data;
                for (var i in d.data) {
                    vm.Rondas = angular.copy(d.data);
                    let ev = {
                        title: d.data[i].HoraInicio + "-" + d.data[i].HoraFin,
                        start: new Date(toDate(d.data[i].DiaMes)),
                        end: new Date(toDate(d.data[i].DiaMes)),
                        allDay: true
                    };
                    vm.events.push(ev);
                }
                let _date = new Date(vm.Year, vm.Mes, 1);
                vm.mdEventCalendar.selectMonth(_date);
            });
        };
        vm.SubirArchivo = () => {
            swal({
                title: `¿Deseas cargar este horario para el mes ${vm.Mes}/${vm.Year}?`,
                text: "Nota: se notificará a GESTIÓN HUMANA para validar y autorizar el cambio del horario.",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Acepto",
                cancelButtonText: "Cancelar",
                closeOnConfirm: false,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm && !vm.BanderaLider) {
                    vm.BanderaLider = true;
                    let obj = {
                        Usuario: vm.Usuario,
                        Data: JSON.stringify(vm.Tabla),
                        Mes: vm.Mes,
                        Year: vm.Year,
                        IsUGH: vm.IsGHUser,
                        CreatedBy: $rootScope.username.NombreCompleto
                    };
                    ColaboradoresService.postPersona(obj).then((d) => {
                        if (typeof d.data !== "string") {
                            swal("Enhorabuena", "Se ha guardado los datos con exito", "success");
                            vm.IsVisible = false;
                            vm.SubirExcelTurnos = false;
                            vm.BanderaLider = false;
                            vm.Tabla = [];
                            vm.SelectedFile = {
                                name: ''
                            };
                        } else {
                            vm.BanderaLider = false;
                            swal("Error", d.data, "error");
                        }
                    },
                            () => {
                        vm.BanderaLider = false;
                    });
                }
            });
        };

        vm.Imprimir = () => {
            printDiv("#TablaEstadisticas");
        };
        vm.ImprimirEntrada = () => {
            printDiv("#TablaEntrada");
        };
        vm.ImprimirSalida = () => {
            printDiv("#TablaSalida");
        };
        vm.getColaboradores = () => {
            vm.Colaboradores = [];
            var PersonaId = $stateParams.PersonaId || null;
            if (PersonaId) {
                PersonaId = $crypto.decrypt(PersonaId, 'Franklin Ospino');
                GetColaboradoresByLiderId(PersonaId);
            } else {
                GetColaboradores();
            }
        };

        vm.VerPermiso = (PermisoId) => {
            vm.Permiso = {};
            PermisoCTService.getPermisoByPermisoId(PermisoId).then((d) => {
                if (typeof d.data != 'string' && d.data.length > 0) {
                    vm.BuscarPermiso = true;
                    vm.Permiso = d.data[0];
                }
            });
        };

        vm.VerListado = (PersonaId, TipoTurno, Nombres) => {
            vm.Funcionario = Nombres;
            vm.TipoTurno = TipoTurno == 'Tarde' ? 'Incumplidas' : 'Cumplidas';
            ColaboradoresService.getListadoE_S(vm.Year, vm.Mes, vm.Tipo, PersonaId, TipoTurno).then((d) => {
                vm.Listados = d.data;
                $('#EventosModal').modal('show');
            });
        };

        vm.GetEstadistica = (PersonaId, Nombres) => {
            vm.Nombres = Nombres;
            ColaboradoresService.getListadoES(vm.Year, vm.Mes, PersonaId).then((d) => {
                vm.ListadosES = d.data;
                $('#ListadoESModal').modal('show');
            });
        };

        vm.VerListadoSalidas = (PersonaId, TipoTurno, Nombres) => {
            vm.Funcionario = Nombres;
            vm.TipoTurno = TipoTurno;
            ColaboradoresService.getListadoE_S(vm.Year, vm.Mes, vm.Tipo, PersonaId, TipoTurno).then((d) => {
                vm.Listados = d.data;
                $('#EventosSalidasModal').modal('show');
            });
        };
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Helpers">
        function getDaysInMonth(m, y) {
            return moment(new Date(y, m - 1, 1)).endOf('month').format('DD');
        }
        function toDate(dateStr) {
            var parts = dateStr.split("-");
            return new Date(parts[0], parts[1] - 1, parts[2]);
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
            });

            setTimeout(function () {
                vm.ToPrint = false;
                vm.$apply();
            }, 1000);
        }
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Consultas">
        function GetVariables() {
            vm.variables = [];
            ColaboradoresService.getVariablesByUsuario(vm.Usuario).then((d) => {
                vm.variables = d.data;
            });
        }
        function GetColaboradores() {
            ColaboradoresService.getColaboradoresByLider(vm.Year, vm.Mes, vm.Tipo, $rootScope.username.UserId).then((d) => {
                vm.Colaboradores = d.data;
            }).then(() => {
                GetPersonaByUsuarioOrId($rootScope.username.UserId);
            });
        }

        function GetColaboradoresByLiderId(PersonaId) {
            ColaboradoresService.getColaboradoresByLiderId(vm.Year, vm.Mes, vm.Tipo, PersonaId).then((d) => {
                vm.Colaboradores = d.data;
            }).then(() => {
                GetPersonaByUsuarioOrId(PersonaId);
            });
        }

        function GetPersonaByUsuarioOrId(Usuario_PersonaId) {
            ColaboradoresService.getPersonaByUser(Usuario_PersonaId).then((d) => {
                if (d.data.length > 0) {
                    vm.Persona = d.data[0];
                } else {
                    vm.Persona = {};
                }
            });
        }

        function GetLideres() {
            PersonaService.getLideres().then((d) => {
                vm.Lideres = d.data;
            });
        }
        //</editor-fold>
        function __init__() {
            swal("INFORMACIÓN", "PUEDES CONFIGURAR TUS PROPIOS TURNOS PARA ASIGNARLOS A TUS COLABORADORES", "warning");
            let PersonaId = $stateParams.PersonaId || null;
            if (PersonaId) {
                PersonaId = $crypto.decrypt(PersonaId, 'Franklin Ospino');
                vm.Usuario = PersonaId;
                GetColaboradoresByLiderId(PersonaId);
            } else {
                GetColaboradoresByLiderId($rootScope.username.UserId);
                vm.Usuario = $rootScope.username.UserId;
            }
            if (vm.IsAdminSistemas) {
                GetLideres();
            }
        }
        __init__();
    }]);


