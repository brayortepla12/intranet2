app.controller('ControlTurnoCtrl', [
  '$scope',
  '$rootScope',
  '$timeout',
  '$stateParams',
  'PersonaService',
  'ControlService',
  'SesionService',
  'PermisoCTService',
  function (
    $scope,
    $rootScope,
    $timeout,
    $stateParams,
    PersonaService,
    ControlService,
    SesionService,
    PermisoCTService
  ) {
    let vm = $scope
    vm.HayConexion = true
    vm.CumpleanoBandera = false
    vm.AdiosCumpleBandera = false
    vm.PersonaCumple = {}
    var intervalo_persona = setInterval(function () {
      vm.GetPersonas()
    }, 7200000)
    var day = moment().format('YYYY-MM-DD')
    let Datos_dia = SesionService.getNormal('Data_' + day) || []
    var artyom
    vm.IDPantalla = makeid(8)
    vm.ID = ''
    vm.ListadoEmpleados = []
    vm.UltimoControl = []
    vm.Dispositivo = $stateParams.Dispositivo
    vm.PermisosBandera = false
    vm.Persona = {
      Nombres: '--',
      Cargo: '--',
      Foto: '/intranet-2/public_html/fotos_perfiles/default-user.png',
    }
    vm.VERSION = '?v=2.8.19'
    //<editor-fold defaultstate="collapsed" desc="Conexion RT">
    var conn = new WebSocket('ws://192.168.8.125:8089/turno_pantalla')
    conn.onopen = function (e) {
      console.log('Connection established - pantalla!')
      conn.send(
        JSON.stringify({
          event: 'connect',
          is_admin: false,
          UsuarioId: vm.IDPantalla,
          msg: '',
          Envia: vm.Dispositivo,
        })
      )
      toastr.success('Funcion tiempo real OK', 'Conectado: ')
    }

    conn.onmessage = function (e) {
      let event = JSON.parse(e.data)
      if (
        event.event !== 'connect' &&
        event.event !== 'connected' &&
        event.event !== 'close'
      ) {
        if (event.event === 'result') {
          try {
            artyom.say(event.msg)
          } catch (e) {
            console.log(e)
          }
        }
        GetListEmpleados(true)
      } else if (event.event === 'connect') {
        toastr.info('Estamos revisando la pantalla. Remotamente.', 'Admin: ')
      } else if (event.event === 'close') {
        toastr.success('Todo en ORDEN.', 'Admin: ')
      } else if (event.event === 'update_lst') {
        console.log('actualizar listado personas')
      }
    }
    conn.onerror = e => {
      toastr.error('e', 'ERROR!')
    }

    // botones
    vm.SendMensaje = function (msg) {
      let host = window.location.hostname
      if (host === '192.168.9.139' || host === 'localhost') {
        msg = 'mensaje de prueba. ' + msg
      }
      conn.send(
        JSON.stringify({
          event: 'foraall',
          is_admin: false,
          UsuarioId: vm.IDPantalla,
          Envia: vm.Dispositivo,
          msg: msg,
        })
      )
    }
    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="Eventos">
    vm.GetPersonas = () => {
      //            if (vm.HayConexion) {
      //                GetPersonas();
      //            }
    }
    vm.MostrarPermisos = () => {
      vm.cargado = false
      PermisoCTService.getPermisosLimite(100).then(p => {
        vm.PermisosBandera = true
        vm.simpleTableOptions = {
          data: [],
          aoColumns: [
            { mData: 'PermisoId' },
            { mData: 'CreatedAt' },
            { mData: 'Nombres' },
            { mData: 'Motivo' },
            { mData: 'Cual' },
            { mData: 'FechaInicio' },
            { mData: 'FechaFin' },
            { mData: 'VBGestionHumana' },
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
              sNext: 'Siguiente',
            },
          },
          aaSorting: [],
        }
        vm.simpleTableOptions.data = p.data
        vm.cargado = true
      })
    }
    vm.ConsultarEmpleado = () => {
      let id = $('#txtId').val()
      $('#txtId').val('')
      let evento = {
        id: id,
        Fecha: moment().format('YYYY-MM-DD HH:mm:ss'),
      }
      if (vm.HayConexion) {
        ControlOnline(id, evento)
      } else {
        ControlOffline(id, evento)
      }
    }
    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="Consultas">
    function GetListEmpleados(ConsultarBandera) {
      if (vm.HayConexion) {
        if (ConsultarBandera) {
          ControlService.getListEmpleado(vm.Dispositivo)
            .then(d => {
              vm.ListadoEmpleados = d.data
            })
            .then(() => {
              GetUltimoControl()
            })
        } else {
          GetUltimoControl()
        }
      }
    }
    function GetDispositivosByParam() {
      if (vm.HayConexion) {
        PersonaService.getDispositivoByName(vm.Dispositivo).then(d => {
          if (typeof d.data !== 'string' && d.data.length > 0) {
            launchFullScreen(document.documentElement) // the whole page
            artyom = new Artyom()
            startContinuousArtyom()
            //        saySomething("Mu ha ha! esto trabaja muy bien.");
            showTime()
            GetListEmpleados(true)
          } else {
            swal('Error', 'Debes añadir un dispisitivo valido.', 'error')
          }
        })
      } else {
        launchFullScreen(document.documentElement) // the whole page
        artyom = new Artyom()
        startContinuousArtyom()
      }
    }

    function GetUltimoControl() {
      if (vm.HayConexion) {
        ControlService.getUltimoControl(
          vm.ListadoEmpleados[vm.ListadoEmpleados.length - 1].PersonaId
        ).then(u => {
          vm.UltimoControl = u.data
        })
      }
    }
    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="Helpers">
    function showTime() {
      var date = new Date()
      var h = date.getHours() // 0 - 23
      var m = date.getMinutes() // 0 - 59
      var s = date.getSeconds() // 0 - 59
      var session = 'AM'
      if (h >= 12) {
        h = h - 12
        session = 'PM'
      }
      if (h == 0) {
        h = 12
      }
      h = h < 10 ? '0' + h : h
      m = m < 10 ? '0' + m : m
      s = s < 10 ? '0' + s : s
      var time = `<div id='clock'>${h}:${m}:${s} ${session}</div> <h3 id='Dia'>${moment()
        .lang('es')
        .format('dddd')}, ${moment().lang('es').format('LL')}</h3>`
      document.getElementById('MyClockDisplay').innerHTML = time
      setTimeout(showTime, 1000)
    }

    setInterval(function () {
      if (!vm.PermisosBandera) {
        $('#txtId').focus()
      }
    }, 1000)

    // Find the right method, call on correct element
    function launchFullScreen(element) {
      if (element.requestFullScreen) {
        element.requestFullScreen()
      } else if (element.mozRequestFullScreen) {
        element.mozRequestFullScreen()
      } else if (element.webkitRequestFullScreen) {
        element.webkitRequestFullScreen()
      }
    }
    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="whatchers">
    // vm.$watch('online', function (newStatus) {
    //     vm.HayConexion = newStatus;
    //     if (vm.HayConexion) {
    //         $indexedDB.openStore('ct_control', function (ct_control) {
    //             ct_control.getAll().then(function (Controls) {
    //                 // Update scope
    //                 vm.Controls = Controls;
    //                 if (Controls.length > 0) {
    //                     let lst = {
    //                         ControlesOffline: JSON.stringify(Controls)
    //                     };
    //                     PersonaService.postPersona(lst).then((d) => {
    //                         console.log(d.data);
    //                         if (typeof d.data != "string") {
    //                             $indexedDB.openStore('ct_control', function (ct_control) {
    //                                 ct_control.clear().then(function () {
    //                                     vm.GetPersonas();
    //                                 });
    //                             });
    //                         }
    //                     });
    //                 }
    //             });
    //         });
    //     }
    // });
    //</editor-fold>

    // Launch fullscreen for browsers that support it!
    GetDispositivosByParam()

    function startContinuousArtyom() {
      artyom.fatality() // Detener cualquier instancia previa

      setTimeout(function () {
        // Esperar 250ms para inicializar
        artyom
          .initialize({
            lang: 'es-ES', // Más lenguajes son soportados, lee la documentación
            continuous: false, // Artyom obedecera por siempre
            listen: false, // Iniciar !
            debug: false, // Muestra un informe en la consola
            speed: 1.18, // Habla normalmente
          })
          .then(function () {
            console.log('Ready to work !')
            //                    artyom.say(`Ooh. solano`.toLowerCase());
            // Add a single command
            //                    var comandoHola = {
            //                        indexes: ["hola", "buenos días", "holita"], // Decir alguna de estas palabras activara el comando
            //                        action: function () { // Acción a ejecutar cuando alguna palabra de los indices es reconocida
            //                            artyom.say("Hola! como estás hoy?");
            //                        }
            //                    };
            //
            //                    var comandoApagate = {
            //                        indexes: ["Adiós", "Bye"], // Decir alguna de estas palabras activara el comando
            //                        action: function () { // Acción a ejecutar cuando alguna palabra de los indices es reconocida
            //                            artyom.say("Adiós");
            //                        }
            //                    };
            //                    artyom.on(['buenos días', 'adiós']).then((i) => {
            //                        switch (i) {
            //                            case 0:
            //                                artyom.say("Hola! como estás hoy?");
            //                                break;
            //                            case 1:
            //                                artyom.say("Adiós");
            //                                break;
            //                        }
            //                    });
          })
      }, 250)
    }
    async function GetPersonas() {
      // wait until the promise returns us a value
      let result = await PersonaService.getPersonasActivas()
      if (typeof result.data != 'string' && result.data.length > 0) {
        $indexedDB.openStore('ct_persona', function (store) {
          // multiple items
          store.insert(result.data).then(function (e) {
            // do something
          })
        })
      }
    }

    function makeid(length) {
      var result = ''
      var characters =
        'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'
      var charactersLength = characters.length
      for (var i = 0; i < length; i++) {
        result += characters.charAt(
          Math.floor(Math.random() * charactersLength)
        )
      }
      return result
    }
    //<editor-fold defaultstate="collapsed" desc="alternativa">
    function saySomething(whatToSay) {
      const synth = window.speechSynthesis
      // enter your voice here, because voices list loads asynchronously.. check the console.log below.
      getVoice('Google español', synth)
        .then(voice => {
          var utterThis = new SpeechSynthesisUtterance(whatToSay)
          utterThis.voice = voice
          synth.speak(utterThis)
        })
        .catch(error => console.log('error: ', error))
    }

    function getVoice(voiceName, synth) {
      return new Promise((resolve, reject) => {
        synth.onvoiceschanged = function () {
          const voices = synth.getVoices()

          console.log(
            'see all available languages and voices on your system: ',
            voices
          )

          for (let i = 0; i < voices.length; i++) {
            if (voices[i].name == voiceName) {
              resolve(voices[i])
            }
          }
        }
        synth.getVoices()
      })
    }
    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="ControlOnline">
    function ControlOnline(id, evento) {
      PersonaService.getPersonaByCodigo(id, vm.Dispositivo)
        .then(
          d => {
            evento.respuesta = d.data
            if (typeof d.data != 'string' && d.data.length > 0) {
              vm.Persona = d.data[0]
              evento.Persona = angular.copy(vm.Persona)
              let mensaje =
                vm.Persona.Genero == 'F' ? 'Bienvenida ' : 'Bienvenido '
              //                    mensaje = vm.Persona.EstadoTurnoEntrada == 'Tarde' ? `si usted. acumula 2 llegadas tarde. Será suspendido!. `.toLowerCase() : mensaje;
              mensaje =
                vm.Persona.EstadoTurnoEntrada == 'Usted ha llegado: Tarde'
                  ? `Usted ha llegado tarde `.toLowerCase()
                  : mensaje
              if (vm.Persona.Estado == 'Activo') {
                if (vm.ListadoEmpleados.length > 0) {
                  vm.ListadoEmpleados.shift()
                  vm.ListadoEmpleados.push(angular.copy(vm.Persona))
                }
                if (vm.Persona.Tipo === 'Entrada') {
                  if (vm.Persona.IsCumpleano == 0) {
                    try {
                      if (vm.Persona.HasPermiso) {
                        vm.Persona.EstadoTurnoEntrada = 'Permiso autorizado!.'
                        artyom.say(
                          `Permiso autorizado!. ${vm.Persona.PrimerNombre} ${vm.Persona.PrimerApellido}`.toLowerCase()
                        )
                      } else {
                        artyom.say(
                          `${mensaje} ${vm.Persona.PrimerNombre} ${vm.Persona.PrimerApellido}`.toLowerCase()
                        )
                      }
                    } catch (e) {
                      console.log(e)
                    }
                    evento.MensajePantalla = `${mensaje} ${vm.Persona.PrimerNombre} ${vm.Persona.PrimerApellido}`
                    //                            Datos_dia.push(evento);
                    //                            SesionService.setNormal(Datos_dia, "Data_" + day);
                    swal({
                      title: 'INGRESANDO!',
                      type: 'success',
                      timer: 1200,
                    })
                    try {
                      if (vm.Persona.HasPermiso) {
                        vm.Persona.EstadoTurnoEntrada = 'Permiso autorizado!.'
                        vm.SendMensaje(
                          `Permiso autorizado!. ${vm.Persona.PrimerNombre} ${vm.Persona.PrimerApellido}`.toLowerCase()
                        )
                      } else {
                        vm.SendMensaje(
                          `${mensaje} ${vm.Persona.PrimerNombre} ${vm.Persona.PrimerApellido}`.toLowerCase()
                        )
                      }
                    } catch (e) {
                      console.log(e)
                    }
                  } else {
                    vm.CumpleanoBandera = true
                    let url =
                      '/intranet-2/public_html/cancion/cancion.mp3?cb=' +
                      new Date().getTime()
                    let audio = new Audio(url)
                    audio.load()
                    audio.play()
                    vm.PersonaCumple = angular.copy(vm.Persona)
                    $timeout(function () {
                      vm.CumpleanoBandera = false
                      vm.AdiosCumpleBandera = false
                    }, 10000)
                  }
                } else if (vm.Persona.Tipo === 'Salida') {
                  if (vm.Persona.IsCumpleano == 0) {
                    try {
                      if (vm.Persona.HasPermiso) {
                        vm.Persona.EstadoTurnoEntrada = 'Permiso autorizado!.'
                        if (vm.Persona.PermisoIsGeneral == 1) {
                          artyom.say(
                            `Adios ${vm.Persona.PrimerNombre} ${vm.Persona.PrimerApellido}. Tienes un permiso especial.`.toLowerCase()
                          )
                        } else {
                          artyom.say(
                            `Permiso autorizado!. ${vm.Persona.PrimerNombre} ${vm.Persona.PrimerApellido}`.toLowerCase()
                          )
                        }
                      } else if (vm.Persona.EstadoTurnoEntrada == 'Adios') {
                        artyom.say(
                          `Adiós ${vm.Persona.PrimerNombre} ${vm.Persona.PrimerApellido}`.toLowerCase()
                        )
                      } else {
                        artyom.say(
                          `${vm.Persona.PrimerNombre} ${vm.Persona.PrimerApellido}. ${vm.Persona.EstadoTurnoEntrada}`.toLowerCase()
                        )
                      }
                    } catch (e) {
                      console.log(e)
                    }
                    evento.MensajePantalla = `Adiós!. ${vm.Persona.PrimerNombre} ${vm.Persona.PrimerApellido}`
                    //                            Datos_dia.push(evento);
                    //                            SesionService.setNormal(Datos_dia, "Data_" + day);
                    swal({
                      title: 'SALIENDO!',
                      type: 'info',
                      timer: 1200,
                    })
                    try {
                      if (vm.Persona.HasPermiso) {
                        vm.SendMensaje(
                          `Permiso autorizado!. ${vm.Persona.PrimerNombre} ${vm.Persona.PrimerApellido}`.toLowerCase()
                        )
                      } else {
                        vm.SendMensaje(
                          `Adiós!. ${vm.Persona.PrimerNombre} ${vm.Persona.PrimerApellido}`.toLowerCase()
                        )
                      }
                    } catch (e) {
                      console.log(e)
                    }
                  } else {
                    vm.CumpleanoBandera = true
                    vm.AdiosCumpleBandera = true
                    let url =
                      '/intranet-2/public_html/cancion/cancion.mp3?cb=' +
                      new Date().getTime()
                    let audio = new Audio(url)
                    audio.load()
                    audio.play()
                    vm.PersonaCumple = angular.copy(vm.Persona)
                    $timeout(function () {
                      vm.CumpleanoBandera = false
                      vm.AdiosCumpleBandera = false
                    }, 7000)
                  }
                }
              } else {
                try {
                  artyom.say(`Usuario INACTIVO`)
                  evento.MensajePantalla = `Usuario, INACTIVO`
                  //                            Datos_dia.push(evento);
                  //                            SesionService.setNormal(Datos_dia, "Data_" + day);
                } catch (e) {
                  console.log(e)
                }
              }
            } else {
              try {
                evento.MensajePantalla = `Usuario, no encontrado. Diríjase a gestión humana.`
                Datos_dia.push(evento)
                SesionService.setNormal(Datos_dia, 'Data_' + day)
                artyom.say(`Usuario no encontrado.`.toLowerCase()) // , Diríjase a gestión humana
              } catch (e) {
                console.log(e)
              }
              swal({
                title: 'USUARIO NO REGISTRADO!!',
                text: d.data.toLowerCase(),
                type: 'error',
                timer: 1200,
              })
              vm.Persona = {
                Nombres: '--',
                Cargo: '--',
                Foto: '/intranet-2/public_html/fotos_perfiles/default-user.png',
              }
            }
          },
          () => {
            // ControlOffLine(id, evento); // error en servidor
          }
        )
        .then(() => {
          GetListEmpleados(false)
        })
    }
    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="Offline">
    function ControlOffline(id, evento) {
      $indexedDB.openStore('ct_persona', function (ct_persona) {
        // build query
        var find = ct_persona.query()
        find = find.$eq(id)
        find = find.$index('CodigoTarjeta')

        // update scope
        ct_persona.eachWhere(find).then(function (e) {
          vm.Persona = e[0]
          vm.Persona.Nombres = `${vm.Persona.PrimerNombre} ${vm.Persona.PrimerApellido}`
          $indexedDB.openStore('ct_control', function (ct_control) {
            // build query
            var find = ct_control.query()
            find = find.$eq(vm.Persona.PersonaId)
            find = find.$index('PersonaId')

            // update scope
            ct_control.eachWhere(find).then(function (c) {
              ct_control.count().then(function (contador) {
                let tipo =
                  vm.Persona.EstadoBiometrico == 'Entrada'
                    ? 'Salida'
                    : 'Entrada'
                let cont = contador + 1
                if (c.length == 0) {
                  // single item
                  ct_control
                    .insert({
                      ControlId: cont,
                      PersonaId: vm.Persona.PersonaId,
                      Fecha: evento.Fecha,
                      Tipo: tipo,
                      Dispositivo: vm.Dispositivo,
                    })
                    .then(function (e) {
                      // do something
                    })
                } else {
                  tipo =
                    c[c.length - 1].Tipo == 'Entrada' ? 'Salida' : 'Entrada'
                  // single item
                  ct_control
                    .insert({
                      ControlId: cont,
                      PersonaId: vm.Persona.PersonaId,
                      Fecha: evento.Fecha,
                      Tipo: tipo,
                      Dispositivo: vm.Dispositivo,
                    })
                    .then(function (e) {
                      // do something
                    })
                }
                if (tipo == 'Salida') {
                  artyom.say(
                    `Adiós!. ${vm.Persona.PrimerNombre} ${vm.Persona.PrimerApellido}`.toLowerCase()
                  )
                  evento.MensajePantalla = `Adiós!. ${vm.Persona.PrimerNombre} ${vm.Persona.PrimerApellido}`
                  swal({
                    title: 'SALIENDO!',
                    type: 'info',
                    timer: 1200,
                  })
                } else if (tipo == 'Entrada') {
                  artyom.say(
                    `Bienvenido. ${vm.Persona.PrimerNombre} ${vm.Persona.PrimerApellido}`.toLowerCase()
                  )
                  evento.MensajePantalla = `Bienvenido ${vm.Persona.PrimerNombre} ${vm.Persona.PrimerApellido}`
                  swal({
                    title: 'INGRESANDO!',
                    type: 'success',
                    timer: 1200,
                  })
                }
              })
            })
          })
        })
      })
    }
    //</editor-fold>

    function __init__() {
      //            vm.GetPersonas();
    }
    __init__()
  },
])
