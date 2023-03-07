'use strict'
app.config([
  '$stateProvider',
  '$urlRouterProvider',
  '$locationProvider',
  function ($stateProvider, $urlRouterProvider, $locationProvider) {
    $locationProvider.hashPrefix('')
    $urlRouterProvider.otherwise('/login/')
    $stateProvider
      .state('app', {
        abstract: true,
        url: '/app',
        templateUrl: '/intranet-2/public_html/views/Main_layout.html?v=2.8.19'
      })
      .state('observador_virtual', {
        abstract: true,
        url: '/observador_virtual',
        templateUrl: '/intranet-2/public_html/views/Main_layout.html?v=2.8.19'
      })
      .state('hoja_vida', {
        abstract: true,
        url: '/hoja_vida',
        templateUrl: '/intranet-2/public_html/views/Main_layout.html?v=2.8.19'
      })
      .state('gh', {
        abstract: true,
        url: '/gh',
        templateUrl: '/intranet-2/public_html/views/Main_layout.html?v=2.8.19'
      })
      .state('mantenimiento', {
        abstract: true,
        url: '/mantenimiento',
        templateUrl: '/intranet-2/public_html/views/Main_layout.html?v=2.8.19'
      })
      .state('ambulancia', {
        abstract: true,
        url: '/ambulancia',
        templateUrl: '/intranet-2/public_html/views/Main_layout.html?v=2.8.19'
      })
      .state('almacen', {
        abstract: true,
        url: '/almacen',
        templateUrl: '/intranet-2/public_html/views/Main_layout.html?v=2.8.19'
      })
      .state('bs', {
        abstract: true,
        url: '/bs',
        templateUrl: '/intranet-2/public_html/views/Main_layout.html?v=2.8.19'
      })
      .state('bio', {
        abstract: true,
        url: '/bio',
        templateUrl: '/intranet-2/public_html/views/Main_layout.html?v=2.8.19'
      })
      .state('autorizacion', {
        abstract: true,
        url: '/autorizacion',
        templateUrl: '/intranet-2/public_html/views/Main_layout.html?v=2.8.19'
      })
      .state('pc', {
        abstract: true,
        url: '/protocolo',
        templateUrl: '/intranet-2/public_html/views/Main_layout.html?v=2.8.19'
      })
      .state('cm', {
        abstract: true,
        url: '/central_mezclas',
        templateUrl: '/intranet-2/public_html/views/Main_layout.html?v=2.8.19'
      })
      .state('farmacia', {
        abstract: true,
        url: '/farmacia',
        templateUrl: '/intranet-2/public_html/views/Main_layout.html?v=2.8.19'
      })
      .state('tm', {
        abstract: true,
        url: '/eventos',
        templateUrl: '/intranet-2/public_html/views/Main_layout.html?v=2.8.19'
      })
      .state('sst', {
        abstract: true,
        url: '/sst',
        templateUrl: '/intranet-2/public_html/views/Main_layout.html?v=2.8.19'
      })
      .state('solicitud', {
        abstract: true,
        url: '/solicitud',
        templateUrl: '/intranet-2/public_html/views/Main_layout.html?v=2.8.19'
      })
      .state('viatico', {
        abstract: true,
        url: '/viatico',
        templateUrl: '/intranet-2/public_html/views/Main_layout.html?v=2.8.19'
      })
      .state('cronograma', {
        abstract: true,
        url: '/cronograma',
        templateUrl: '/intranet-2/public_html/views/Main_layout.html?v=2.8.19'
      })
      .state('configuracion', {
        abstract: true,
        url: '/configuracion',
        templateUrl: '/intranet-2/public_html/views/Main_layout.html?v=2.8.19'
      })
      .state('thc', {
        abstract: true,
        url: '/thc',
        templateUrl: '/intranet-2/public_html/views/Main_layout.html?v=2.8.19'
      })
      .state('ronda', {
        abstract: true,
        url: '/ronda',
        templateUrl: '/intranet-2/public_html/views/Main_layout.html?v=2.8.19'
      })
      .state('biomedicos', {
        abstract: true,
        url: '/biomedicos',
        templateUrl: '/intranet-2/public_html/views/Main_layout.html?v=2.8.19'
      })
      .state('sa', {
        abstract: true,
        url: '/sa',
        templateUrl: '/intranet-2/public_html/views/Main_layout.html?v=2.8.19'
      })
      .state('tel', {
        abstract: true,
        url: '/tel',
        templateUrl: '/intranet-2/public_html/views/Main_layout.html?v=2.8.19'
      })
      .state('login', {
        url: '/login/:UsuarioId?',
        templateUrl: '/intranet-2/public_html/views/login.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Inicio',
          description: ''
        }
      })
      .state('restablecer_contrasena', {
        url: '/restablecer_contrasena/:UserId/:token',
        templateUrl:
          '/intranet-2/public_html/views/restablecer_contrasena.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'restablecer contraseña',
          description: ''
        }
      })
      .state('controlturno_pantalla', {
        url: '/controlturno_pantalla/:Dispositivo',
        templateUrl:
          '/intranet-2/public_html/views/controlturno/controlturno_pantalla.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Pantalla',
          description: ''
        }
      })
      .state('polivalente', {
        abstract: true,
        url: '/polivalente',
        templateUrl: '/intranet-2/public_html/views/Main_layout.html?v=2.8.19'
      })

      .state('hemodinamia', {
        abstract: true,
        url: '/hemodinamia',
        templateUrl: '/intranet-2/public_html/views/Main_layout.html?v=2.8.19'
      })

      .state('sistemas', {
        abstract: true,
        url: '/sistemas',
        templateUrl: '/intranet-2/public_html/views/Main_layout.html?v=2.8.19'
      })
      .state('app.reg_usuario', {
        url: '/reg_usuario',
        templateUrl:
          '/intranet-2/public_html/views/inicio/reg_usuario.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Registrar Usuario',
          description: ''
        }
      })
      .state('app.inicio', {
        url: '/inicio',
        templateUrl: '/intranet-2/public_html/views/inicio/home.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Principal',
          description: ''
        }
      })
      .state('app.cambiar_contrasena', {
        url: '/cambiar_contrasena',
        templateUrl:
          '/intranet-2/public_html/views/inicio/cambiar_contrasena.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Cambiar Contraseña',
          description: ''
        }
      })
      .state('polivalente.ficha_tecnica', {
        url: '/ficha_tecnica/:HojaVidaId',
        templateUrl:
          '/intranet-2/public_html/views/equipos/ficha_tecnica.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Ficha tecnica',
          description: ''
        },
        controller: 'AuditoriaSesion'
      })
      .state('thc.historias', {
        // THC
        url: '/historias',
        templateUrl:
          '/intranet-2/public_html/views/thc/historias.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Consultar historia clinica',
          description: ''
        },
        controller: 'AuditoriaSesion'
      })
      .state('thc.mis_historias', {
        // THC
        url: '/mis_historias',
        templateUrl:
          '/intranet-2/public_html/views/thc/mis_historias.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Consultar y entregar historia clinica',
          description: ''
        },
        controller: 'AuditoriaSesion'
      })
      .state('thc.enfermeria', {
        // THC
        url: '/enfermeria',
        templateUrl:
          '/intranet-2/public_html/views/thc/enfermeria.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Consultar y entregar historia clinica',
          description: ''
        },
        controller: 'AuditoriaSesion'
      })
      .state('bs.sensor_temperatura', {
        // BANCO DE SANGRE
        url: '/sensor_temperatura',
        templateUrl:
          '/intranet-2/public_html/views/bs/sensor_temperatura.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Consultar temperatura',
          description: ''
        },
        controller: 'AuditoriaSesion'
      })
      .state('bio.ver_permisos', {
        url: '/ver_permisos',
        templateUrl:
          '/intranet-2/public_html/views/bio/ver_permisos.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Consultar Permisos por mes',
          description: ''
        },
        controller: 'AuditoriaSesion'
      })
      .state('bio.sms', {
        url: '/sms',
        templateUrl: '/intranet-2/public_html/views/bio/sms.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Consultar SMS por mes',
          description: ''
        },
        controller: 'AuditoriaSesion'
      })
      .state('bio.SolicitudHorario', {
        url: '/cambio_horario',
        templateUrl:
          '/intranet-2/public_html/views/bio/cambio_horario.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Solicitud cambio de horario',
          description: ''
        },
        controller: 'AuditoriaSesion'
      })
      .state('bio.Personal', {
        url: '/personal',
        templateUrl: '/intranet-2/public_html/views/bio/personal.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Personal',
          description: ''
        },
        controller: 'AuditoriaSesion'
      })
      .state('bio.listado_personal', {
        url: '/listado_personal/:PersonaId',
        templateUrl:
          '/intranet-2/public_html/views/bio/personalLider.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Personal a cargo',
          description: ''
        },
        controller: 'AuditoriaSesion'
      })
      .state('bio.GetionPermiso', {
        url: '/gestion_permisos',
        templateUrl:
          '/intranet-2/public_html/views/bio/gestion_permisos.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Getionar Permisos',
          description: ''
        },
        controller: 'AuditoriaSesion'
      })
      .state('bio.ListadoPermisos', {
        url: '/ListadoPermisos',
        templateUrl:
          '/intranet-2/public_html/views/bio/listado_permisos.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Listado de Permisos',
          description: ''
        },
        controller: 'AuditoriaSesion'
      })
      .state('bio.VerPermiso', {
        url: '/ver_permiso/:PermisoId',
        templateUrl:
          '/intranet-2/public_html/views/bio/ver_permiso.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Ver Permiso',
          description: ''
        },
        controller: 'AuditoriaSesion'
      })
      .state('bio.GetionPermisoDocumento', {
        url: '/gestion_permisos_documento',
        templateUrl:
          '/intranet-2/public_html/views/bio/gestion_permisos_documento.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Getionar Permisos',
          description: ''
        },
        controller: 'AuditoriaSesion'
      })
      .state('bio.AutorizarPermiso', {
        url: '/autorizar_permisos',
        templateUrl:
          '/intranet-2/public_html/views/bio/autorizar_permisos.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Autorizar Permisos',
          description: ''
        },
        controller: 'AuditoriaSesion'
      })
      .state('bio.AutorizarPermisoDocumento', {
        url: '/autorizar_permisos_documento',
        templateUrl:
          '/intranet-2/public_html/views/bio/autorizar_permisos_documento.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Autorizar Permisos',
          description: ''
        },
        controller: 'AuditoriaSesion'
      })
      .state('bio.cargos', {
        url: '/cargos',
        templateUrl: '/intranet-2/public_html/views/bio/cargos.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Cargos',
          description: ''
        },
        controller: 'AuditoriaSesion'
      })
      .state('bio.lideres', {
        url: '/lideres',
        templateUrl: '/intranet-2/public_html/views/bio/lideres.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Lideres',
          description: ''
        },
        controller: 'AuditoriaSesion'
      })
      .state('bio.ver_pantallas', {
        url: '/ver_pantallas',
        templateUrl:
          '/intranet-2/public_html/views/bio/ver_pantallas.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Ver pantallas',
          description: ''
        },
        controller: 'AuditoriaSesion'
      })
      .state('bio.planilla_lideres', {
        url: '/planilla_lideres',
        templateUrl:
          '/intranet-2/public_html/views/bio/planilla_lideres.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Planilla Lideres',
          description: ''
        },
        controller: 'AuditoriaSesion'
      })
      .state('bio.planilla_colaboradores', {
        url: '/planilla_colaboradores',
        templateUrl:
          '/intranet-2/public_html/views/bio/planilla_colaboradores.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Planilla Colaboradores',
          description: ''
        },
        controller: 'AuditoriaSesion'
      })
      .state('bio.colaboradores', {
        url: '/colaboradores',
        templateUrl:
          '/intranet-2/public_html/views/bio/colaboradores.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Colaboradores',
          description: ''
        },
        controller: 'AuditoriaSesion'
      })
      .state('sistemas.Actas', {
        url: '/actas',
        templateUrl: '/intranet-2/public_html/views/equipos/acta.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Actas - Sistemas',
          description: ''
        },
        controller: 'AuditoriaSesion'
      })
      .state('sistemas.ficha_tecnica_2', {
        url: '/ficha_tecnica_2/:HojaVidaId',
        templateUrl:
          '/intranet-2/public_html/views/equipos/ficha_tecnica_2.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Ficha tecnica - Sistemas',
          description: ''
        },
        controller: 'AuditoriaSesion'
      })
      .state('sistemas.historial_recarga', {
        url: '/historial_recarga/:HojaVidaId',
        templateUrl:
          '/intranet-2/public_html/views/equipos/historial_recarga.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Historial de recarga - Sistemas',
          description: ''
        },
        controller: 'AuditoriaSesion'
      })
      .state('sst.ficha_tecnica_3', {
        url: '/ficha_tecnica_3/:HojaVidaId',
        templateUrl:
          '/intranet-2/public_html/views/equipos/ficha_tecnica_3.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Ficha tecnica - Seguridad y Salud en el Trabajo',
          description: ''
        },
        controller: 'AuditoriaSesion'
      })
      .state('polivalente.Listado_equipos', {
        url: '/listado_equipos',
        templateUrl:
          '/intranet-2/public_html/views/equipos/listado_equipos.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Listado de Equipos',
          description: ''
        },
        controller: 'AuditoriaSesion'
      })
      .state('sistemas.Listado_equipos_sistemas', {
        url: '/listado_equipos_sistemas',
        templateUrl:
          '/intranet-2/public_html/views/equipos/listado_equipos_sistemas.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Listado de Equipos',
          description: ''
        },
        controller: 'AuditoriaSesion'
      })
      .state('sistemas.monitor_actividad', {
        url: '/monitor_actividad',
        templateUrl:
          '/intranet-2/public_html/views/equipos/monitor_actividad.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Listado de Equipos',
          description: ''
        },
        controller: 'AuditoriaSesion'
      })
      .state('sst.Listado_equipos_sst', {
        url: '/listado_equipos_sst',
        templateUrl:
          '/intranet-2/public_html/views/equipos/listado_equipos_sst.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Listado de Equipos',
          description: ''
        },
        controller: 'AuditoriaSesion'
      })
      .state('sst.cronograma', {
        url: '/cronograma',
        templateUrl:
          '/intranet-2/public_html/views/equipos/cronograma_sst.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Cronograma',
          description: ''
        },
        controller: 'AuditoriaSesion'
      })
      .state('polivalente.generar_qr', {
        url: '/generar_qr',
        templateUrl:
          '/intranet-2/public_html/views/equipos/generar_qr.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Generar codigo QR',
          description: ''
        },
        controller: 'AuditoriaSesion'
      })
      .state('polivalente.ver_hoja_vida', {
        url: '/ver_hoja_vida/:servicio_id/',
        templateUrl:
          '/intranet-2/public_html/views/equipos/ver_hoja_vida.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'ver Hojas de vida',
          description: ''
        },
        controller: 'AuditoriaSesion'
      })
      .state('polivalente.Estadisticas', {
        url: '/estadisticas',
        templateUrl:
          '/intranet-2/public_html/views/equipos/Estadisticas.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Datos Estadisticos',
          description: ''
        },
        controller: 'AuditoriaSesion'
      })
      .state('pc.solicitud', {
        // Solicitud procesos
        url: '/solicitud',
        templateUrl:
          '/intranet-2/public_html/views/procesos/solicitud.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Solicitud de procesos',
          description: ''
        },
        controller: 'AuditoriaSesion'
      })
      .state('pc.solicitud_admin', {
        // Solicitud procesos
        url: '/solicitud_admin',
        templateUrl:
          '/intranet-2/public_html/views/procesos/listado_solicitudes.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Listado de Solicitudes',
          description: ''
        },
        controller: 'AuditoriaSesion'
      })
      .state('pc.auditoria', {
        // Solicitud de procesos
        url: '/auditoria',
        templateUrl:
          '/intranet-2/public_html/views/procesos/auditoria.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Listado de Solicitudes',
          description: ''
        },
        controller: 'AuditoriaSesion'
      })
      .state('pc.OrdenCompra', {
        // Solicitud procesos
        url: '/ordenes_compra',
        templateUrl:
          '/intranet-2/public_html/views/procesos/ordenes_compra.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Ordenes de compra',
          description: ''
        },
        controller: 'AuditoriaSesion'
      })
      .state('polivalente.reporte_escaneado', {
        url: '/reporte_escaneado',
        templateUrl:
          '/intranet-2/public_html/views/equipos/reporte_escaneado.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Subir un reporte escaneado',
          description: ''
        },
        controller: 'AuditoriaSesion'
      })
      .state('polivalente.reporte_escaneado_interno', {
        url: '/reporte_escaneado_interno',
        templateUrl:
          '/intranet-2/public_html/views/equipos/reporte_escaneado_interno.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Subir un reporte escaneado',
          description: ''
        }
      })
      .state('polivalente.reporte_servicio', {
        url: '/reporte_servicio/:Reporte_Id',
        templateUrl:
          '/intranet-2/public_html/views/equipos/ReporteServicio.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Reporte de Servicio',
          description: ''
        },
        controller: 'AuditoriaSesion'
      })
      .state('sistemas.reporte_servicio_sistemas', {
        url: '/reporte_servicio_sistemas/:Reporte_Id',
        templateUrl:
          '/intranet-2/public_html/views/equipos/ReporteServicio_sistemas.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Reporte de Servicio',
          description: ''
        },
        controller: 'AuditoriaSesion'
      })
      .state('sst.reporte_servicio_sst', {
        url: '/reporte_servicio_sst/:Reporte_Id',
        templateUrl:
          '/intranet-2/public_html/views/equipos/ReporteServicio_sst.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Reporte de Servicio',
          description: ''
        },
        controller: 'AuditoriaSesion'
      })
      .state('sistemas.generar_qr', {
        url: '/generar_qr',
        templateUrl:
          '/intranet-2/public_html/views/equipos/generar_qr_sistemas.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Generar codigo QR',
          description: ''
        },
        controller: 'AuditoriaSesion'
      })
      .state('sst.generar_qr', {
        url: '/generar_qr',
        templateUrl:
          '/intranet-2/public_html/views/equipos/generar_qr_sst.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Generar codigo QR',
          description: ''
        },
        controller: 'AuditoriaSesion'
      })
      .state('polivalente.reporte_servicio_interno', {
        url: '/reporte_servicio_interno',
        templateUrl:
          '/intranet-2/public_html/views/equipos/ReporteServicioInterno.html?v=2.8.19',
        controller: 'AuditoriaSesion',
        ncyBreadcrumb: {
          label: 'Reporte de Servicio',
          description: ''
        }
      })
      .state('polivalente.listado_reportes', {
        url: '/listado_reportes',
        templateUrl:
          '/intranet-2/public_html/views/equipos/listado_reportes.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Reporte de Servicio',
          description: ''
        }
      })
      .state('sistemas.listado_reportes_sistemas', {
        url: '/listado_reportes_sistemas',
        templateUrl:
          '/intranet-2/public_html/views/equipos/listado_reportes_sistemas.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Listado de reportes de sistemas',
          description: ''
        }
      })
      .state('sst.listado_reportes_sst', {
        url: '/listado_reportes_sistemas',
        templateUrl:
          '/intranet-2/public_html/views/equipos/listado_reportes_sst.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Listado de reportes de SST',
          description: ''
        }
      })
      .state('polivalente.ver_reporte_servicio', {
        url: '/ver_reporte_servicio/:sede_id/:servicio_id/:year/:mes',
        templateUrl:
          '/intranet-2/public_html/views/equipos/ver_reporte_servicio.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'ver Reportes',
          description: ''
        },
        controller: 'AuditoriaSesion'
      })
      .state('sistemas.ver_reporte_servicio_sistemas', {
        url: '/ver_reporte_servicio_sistemas/:sede_id/:servicio_id/:year/:mes',
        templateUrl:
          '/intranet-2/public_html/views/equipos/ver_reporte_servicio_sistemas.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'ver Reportes Sistemas',
          description: ''
        },
        controller: 'AuditoriaSesion'
      })
      .state('polivalente.ver_reporte', {
        url: '/ver_reporte/:reporte_id',
        templateUrl:
          '/intranet-2/public_html/views/equipos/ver_reporte.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'ver Reporte',
          description: ''
        },
        controller: 'AuditoriaSesion'
      })
      .state('solicitud.solicitud-pol', {
        url: '/solicitud-pol',
        templateUrl:
          '/intranet-2/public_html/views/Solicitud/solicitud.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Solicitud',
          description: ''
        },
        controller: 'AuditoriaSesion'
      })
      .state('solicitud.solicitud-bio', {
        url: '/solicitud-bio',
        templateUrl:
          '/intranet-2/public_html/views/Solicitud/solicitud.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Solicitud',
          description: ''
        },
        controller: 'AuditoriaSesion'
      })
      .state('solicitud.solicitud-sis', {
        url: '/solicitud-sis',
        templateUrl:
          '/intranet-2/public_html/views/Solicitud/solicitud.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Solicitud',
          description: ''
        },
        controller: 'AuditoriaSesion'
      })
      .state('solicitud.solicitudAdmin-pol', {
        url: '/solicitud_admin_pol',
        templateUrl:
          '/intranet-2/public_html/views/Solicitud/solicitud_admin.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Administrar Solicitudes',
          description: ''
        },
        controller: 'AuditoriaSesion'
      })
      .state('solicitud.solicitudAdmin-bio', {
        url: '/solicitud_admin_bio',
        templateUrl:
          '/intranet-2/public_html/views/Solicitud/solicitud_admin.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Administrar Solicitudes',
          description: ''
        },
        controller: 'AuditoriaSesion'
      })
      .state('sa.Estadisticas', {
        url: '/estadisticas_hd',
        templateUrl:
          '/intranet-2/public_html/views/sa/Estadisticas.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'HD - Estadisticas',
          description: ''
        },
        controller: 'AuditoriaSesion'
      })
      .state('sa.EstadisticasDetalladas', {
        url: '/estadisticas_detalladas_hd',
        templateUrl:
          '/intranet-2/public_html/views/sa/EstadisticasDetalladas.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'HD - Estadisticas Detalladas',
          description: ''
        },
        controller: 'AuditoriaSesion'
      })
      .state('sa.solicitud_hd', {
        url: '/servicio_hd',
        templateUrl: '/intranet-2/public_html/views/sa/SNHD.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'HD - Servicios',
          description: ''
        },
        controller: 'AuditoriaSesion'
      })
      .state('sa.crear_hd', {
        url: '/nutricion_hd',
        templateUrl: '/intranet-2/public_html/views/sa/SNHD.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'HD - Nutrición',
          description: ''
        },
        controller: 'AuditoriaSesion'
      })
      .state('sa.listado_hd_cield', {
        url: '/listado_hd_cield',
        templateUrl:
          '/intranet-2/public_html/views/sa/listado_hd.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'HD - Empresas',
          description: ''
        },
        controller: 'AuditoriaSesion'
      })
      .state('sa.listado_hd_prado', {
        url: '/listado_hd_prado',
        templateUrl:
          '/intranet-2/public_html/views/sa/listado_hd.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'HD - Empresas',
          description: ''
        },
        controller: 'AuditoriaSesion'
      })
      .state('ver_entrega_externa', {
        url: '/tel_entregas/:token',
        templateUrl:
          '/intranet-2/public_html/views/tel/entregas_externo.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Firmar Entregas',
          description: ''
        }
      })
      .state('politicas', {
        url: '/POLITICAS',
        templateUrl:
          '/intranet-2/public_html/views/tel/Politicas.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Politicas',
          description: ''
        }
      })
      .state('tel.Telefonos', {
        url: '/telefonos',
        templateUrl:
          '/intranet-2/public_html/views/tel/telefonos.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Telefonos',
          description: ''
        },
        controller: 'AuditoriaSesion'
      })
      .state('tel.entrega', {
        url: '/entregas',
        templateUrl: '/intranet-2/public_html/views/tel/entregas.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Entregas',
          description: ''
        },
        controller: 'AuditoriaSesion'
      })
      .state('tel.inventario', {
        url: '/inventarios',
        templateUrl:
          '/intranet-2/public_html/views/tel/inventarios.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Inventarios',
          description: ''
        },
        controller: 'AuditoriaSesion'
      })
      .state('tel.Solicitud', {
        url: '/solicitudes',
        templateUrl:
          '/intranet-2/public_html/views/tel/solicitud.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Solicitudes',
          description: ''
        },
        controller: 'AuditoriaSesion'
      })
      .state('solicitud.solicitudAdmin-sis', {
        url: '/solicitud_admin_sis',
        templateUrl:
          '/intranet-2/public_html/views/Solicitud/solicitud_admin.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Administrar Solicitudes',
          description: ''
        },
        controller: 'AuditoriaSesion'
      })
      .state('viatico.presolicitudViaticos', {
        url: '/pre_solicitud_viaticos',
        templateUrl:
          '/intranet-2/public_html/views/viaticos/pre_solicitud_viaticos.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Solicitud de viaticos',
          description: ''
        },
        controller: 'AuditoriaSesion'
      })
      .state('viatico.generarsolicitudViaticos', {
        url: '/generar_solicitud_viaticos',
        templateUrl:
          '/intranet-2/public_html/views/viaticos/generar_solicitud_viaticos.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Solicitud de viaticos',
          description: ''
        },
        controller: 'AuditoriaSesion'
      })
      .state('viatico.autorizarViaticos', {
        url: '/autorizar_viaticos',
        templateUrl:
          '/intranet-2/public_html/views/viaticos/autorizar_viaticos.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Autorizar viaticos',
          description: ''
        },
        controller: 'AuditoriaSesion'
      })
      .state('viatico.LegalizacionViaticos', {
        url: '/legalizar_viaticos',
        templateUrl:
          '/intranet-2/public_html/views/viaticos/legalizar_viaticos.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Legalizar viaticos',
          description: ''
        },
        controller: 'AuditoriaSesion'
      })
      .state('viatico.LegalizacionViaticosAguachica', {
        url: '/legalizar_viaticos_aguachica',
        templateUrl:
          '/intranet-2/public_html/views/viaticos/legalizar_viaticos.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Legalizar viaticos',
          description: ''
        },
        controller: 'AuditoriaSesion'
      })
      .state('viatico.RevisarLegalizacion', {
        url: '/revisar_legalizacion_viaticos',
        templateUrl:
          '/intranet-2/public_html/views/viaticos/revizar_legalizacion_viaticos.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Revisar Legalizacion viaticos',
          description: ''
        },
        controller: 'AuditoriaSesion'
      })
      .state('polivalente.ReporteDiario', {
        url: '/reporte_diario/:ReporteId',
        templateUrl:
          '/intranet-2/public_html/views/equipos/reporte_diario.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Reporte diario',
          description: ''
        },
        controller: 'AuditoriaSesion'
      })
      .state('polivalente.firmar_reporte', {
        url: '/firmar_reporte/:reporte_id/:token',
        templateUrl:
          '/intranet-2/public_html/views/equipos/ver_reporte_externo.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Firmar Reporte',
          description: ''
        }
      })
      .state('polivalente.Diario', {
        url: '/cronograma_diario',
        templateUrl:
          '/intranet-2/public_html/views/equipos/cronograma_diario.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Cronograma de reporte diario',
          description: 'Cronograma de reporte diario'
        },
        controller: 'AuditoriaSesion'
      })
      .state('polivalente.CronogramaMantenimiento', {
        url: '/cronograma_mantenimiento_preventivo',
        templateUrl:
          '/intranet-2/public_html/views/equipos/cronograma_mantenimiento_preventivo.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Cronograma Mantenimiento Preventivo',
          description: 'Cronograma de Mantenimientos Preventivos'
        },
        controller: 'AuditoriaSesion'
      })
      .state('polivalente.CMI', {
        url: '/cronograma_mantenimiento_infraestructura',
        templateUrl:
          '/intranet-2/public_html/views/equipos/cronograma_mantenimiento_infraestructura.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Cronograma Mantenimiento Infraestructura',
          description: 'Cronograma de Mantenimientos Infraestructura'
        },
        controller: 'AuditoriaSesion'
      })
      .state('sistemas.CronogramaMantenimientoSistemas', {
        url: '/cronograma_mantenimiento_preventivo_sistemas',
        templateUrl:
          '/intranet-2/public_html/views/equipos/cronograma_mantenimiento_preventivo_sistemas.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Cronograma Mantenimiento Preventivo',
          description: 'Cronograma de Mantenimientos Preventivos - Sistemas'
        },
        controller: 'AuditoriaSesion'
      })
      .state('polivalente.InventarioEquipos', {
        url: '/inventario_equipos',
        templateUrl:
          '/intranet-2/public_html/views/equipos/inventario_equipos.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Inventario de equipos',
          description: 'Inventario de equipos'
        },
        controller: 'AuditoriaSesion'
      })
      .state('polivalente.Calibracion', {
        url: '/calibracion',
        templateUrl:
          '/intranet-2/public_html/views/equipos/Calibracion.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Calibración',
          description: 'Calibración'
        },
        controller: 'AuditoriaSesion'
      })
      .state('polivalente.Traslados', {
        url: '/traslados',
        templateUrl:
          '/intranet-2/public_html/views/equipos/Traslados.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Traslados',
          description: 'Traslados'
        },
        controller: 'AuditoriaSesion'
      })
      .state('ver_reporte_externo', {
        url: '/ver_reporte_externo/:reporte_id/:token',
        templateUrl:
          '/intranet-2/public_html/views/equipos/ver_reporte_externo.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Ver reporte externo',
          description: ''
        }
      })
      .state('ver_proceso', {
        url: '/ver_proceso/:token',
        templateUrl:
          '/intranet-2/public_html/views/procesos/ver_proceso.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Ver reporte externo',
          description: ''
        }
      })
      .state('ver_reporte_externo_sistemas', {
        url: '/ver_reporte_externo_sistemas/:reporte_id/:token',
        templateUrl:
          '/intranet-2/public_html/views/equipos/ver_reporte_externo_sistemas.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Ver reporte externo sistemas',
          description: ''
        }
      })
      .state('ver_reporte_externo_sst', {
        url: '/ver_reporte_externo_sst/:reporte_id/:token',
        templateUrl:
          '/intranet-2/public_html/views/equipos/ver_reporte_externo_sst.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Ver reporte externo sistemas',
          description: ''
        }
      })
      .state('hemodinamia.historia_clinica', {
        // Hemodinamia
        url: '/historia_clinica',
        templateUrl:
          '/intranet-2/public_html/views/hemodinamia/historia_clinica.html?v=2.8.19',
        controller: 'AuditoriaSesion',
        ncyBreadcrumb: {
          label: 'Historia Clinica',
          description: ''
        }
      })
      .state('ambulancia.solicitud_mantenimiento', {
        // ambulancia
        url: '/solicitud_mantenimiento',
        templateUrl:
          '/intranet-2/public_html/views/ambulancia/solicitud_mantenimiento.html?v=2.8.19',
        controller: 'AuditoriaSesion',
        ncyBreadcrumb: {
          label: 'Solicitar mantenimiento',
          description: ''
        }
      })
      .state('ambulancia.solicitud_mantenimiento_admin', {
        // ambulancia
        url: '/solicitud_mantenimiento_admin',
        templateUrl:
          '/intranet-2/public_html/views/ambulancia/solicitud_mantenimiento_admin.html?v=2.8.19',
        controller: 'AuditoriaSesion',
        ncyBreadcrumb: {
          label: 'Administrar solicitudes de mantenimiento',
          description: ''
        }
      })
      .state('ambulancia.referencia', {
        // ambulancia
        url: '/referencia',
        templateUrl:
          '/intranet-2/public_html/views/ambulancia/referencia.html?v=2.8.19',
        controller: 'AuditoriaSesion',
        ncyBreadcrumb: {
          label: 'Consultar traslados por mes',
          description: ''
        }
      })
      .state('ambulancia.crear_reporte', {
        // ambulancia
        url: '/crear_reporte/:SolicitudMantenimiento',
        templateUrl:
          '/intranet-2/public_html/views/ambulancia/crear_reporte.html?v=2.8.19',
        controller: 'AuditoriaSesion',
        ncyBreadcrumb: {
          label: 'Formulario para reportes',
          description: ''
        }
      })
      .state('ambulancia.cronograma', {
        // ambulancia
        url: '/cronograma',
        templateUrl:
          '/intranet-2/public_html/views/ambulancia/cronograma.html?v=2.8.19',
        controller: 'AuditoriaSesion',
        ncyBreadcrumb: {
          label: 'Cronograma de mantenimiento',
          description: ''
        }
      })
      .state('ambulancia.pantalla', {
        // ambulancia
        url: '/pantalla',
        templateUrl:
          '/intranet-2/public_html/views/ambulancia/pantalla.html?v=2.8.19',
        controller: 'AuditoriaSesion',
        ncyBreadcrumb: {
          label: 'Pantalla',
          description: ''
        }
      })
      .state('ambulancia.actualizar_km', {
        // ambulancia
        url: '/actualizar_km',
        templateUrl:
          '/intranet-2/public_html/views/ambulancia/actualizar_km.html?v=2.8.19',
        controller: 'AuditoriaSesion',
        ncyBreadcrumb: {
          label: 'Actualizar Km',
          description: ''
        }
      })
      .state('ambulancia.crear_hoja_vida', {
        // ambulancia
        url: '/crear_hoja_vida/:HojaVidaId',
        templateUrl:
          '/intranet-2/public_html/views/ambulancia/crear_hoja_vida.html?v=2.8.19',
        controller: 'AuditoriaSesion',
        ncyBreadcrumb: {
          label: 'Formulario para pedidos',
          description: ''
        }
      })
      .state('ambulancia.listar_hojas_de_vida', {
        // ambulancia
        url: '/listar_hojas_de_vida',
        templateUrl:
          '/intranet-2/public_html/views/ambulancia/listar_hojas_de_vida.html?v=2.8.19',
        controller: 'AuditoriaSesion',
        ncyBreadcrumb: {
          label: 'Listado ambulancias',
          description: ''
        }
      })
      .state('almacen.Inventario', {
        // Almecen
        url: '/Inventario',
        templateUrl:
          '/intranet-2/public_html/views/almacen/Inventario.html?v=2.8.19',
        controller: 'AuditoriaSesion',
        ncyBreadcrumb: {
          label: 'Inventario',
          description: ''
        }
      })
      .state('almacen.TrasladarPlantilla', {
        // Almecen
        url: '/TrasladarPlantilla',
        templateUrl:
          '/intranet-2/public_html/views/almacen/TrasladarPlantilla.html?v=2.8.19',
        controller: 'AuditoriaSesion',
        ncyBreadcrumb: {
          label: 'Trasladar Plantilla',
          description: ''
        }
      })
      .state('almacen.pedidos', {
        // Almecen
        url: '/pedidos',
        templateUrl:
          '/intranet-2/public_html/views/almacen/pedidos.html?v=2.8.19',
        controller: 'AuditoriaSesion',
        ncyBreadcrumb: {
          label: 'Formulario para pedidos',
          description: ''
        }
      })
      .state('almacen.estadisticas', {
        // Almecen
        url: '/estadisticas',
        templateUrl:
          '/intranet-2/public_html/views/almacen/estadisticas.html?v=2.8.19',
        controller: 'AuditoriaSesion',
        ncyBreadcrumb: {
          label: 'Generar reportes estadisticos',
          description: ''
        }
      })
      .state('almacen.pedidos2', {
        // Almecen
        url: '/pedidos2',
        templateUrl:
          '/intranet-2/public_html/views/almacen/pedidos2.html?v=2.8.19',
        controller: 'AuditoriaSesion',
        ncyBreadcrumb: {
          label: 'Formulario para pedidos',
          description: ''
        }
      })
      .state('farmacia.pedidos2_central', {
        // Almecen
        url: '/pedidos2_farmacia',
        templateUrl:
          '/intranet-2/public_html/views/almacen/pedidos2.html?v=2.8.19',
        controller: 'AuditoriaSesion',
        ncyBreadcrumb: {
          label: 'Formulario para pedidos - Central',
          description: ''
        }
      })
      .state('farmacia.relacion_costos_central', {
        // Almecen
        url: '/plantilla_farmacia',
        templateUrl:
          '/intranet-2/public_html/views/almacen/relacion_costos.html?v=2.8.19',
        controller: 'AuditoriaSesion',
        ncyBreadcrumb: {
          label: 'Plantilla para padidos a Central de mezclas',
          description: ''
        }
      })
      .state('farmacia.solicitudes_pedidos_2_central', {
        // Almecen
        url: '/administrar_solicitudes_pedidos_farmacia',
        templateUrl:
          '/intranet-2/public_html/views/almacen/administrar_solicitudes_pedidos.html?v=2.8.19',
        controller: 'AuditoriaSesion',
        ncyBreadcrumb: {
          label: 'Administración de pedidos - Central de mezclas',
          description: ''
        }
      })
      .state('almacen.relacion_costos', {
        // Almecen
        url: '/plantilla',
        templateUrl:
          '/intranet-2/public_html/views/almacen/relacion_costos.html?v=2.8.19',
        controller: 'AuditoriaSesion',
        ncyBreadcrumb: {
          label: 'Plantilla Almacen',
          description: ''
        }
      })
      .state('almacen.solicitudes_pedidos', {
        // Almecen
        url: '/solicitudes_pedidos',
        templateUrl:
          '/intranet-2/public_html/views/almacen/solicitudes_pedidos.html?v=2.8.19',
        controller: 'AuditoriaSesion',
        ncyBreadcrumb: {
          label: 'Formulario para pedidos',
          description: ''
        }
      })
      .state('almacen.solicitudes_pedidos_2', {
        // Almecen
        url: '/administrar_solicitudes_pedidos',
        templateUrl:
          '/intranet-2/public_html/views/almacen/administrar_solicitudes_pedidos.html?v=2.8.19',
        controller: 'AuditoriaSesion',
        ncyBreadcrumb: {
          label: 'Administración de solicitud de pedidos',
          description: ''
        }
      })
      .state('almacen.solicitudes_pedidos_2_repuesto', {
        // Almecen
        url: '/administrar_solicitrudes_repuesto',
        templateUrl:
          '/intranet-2/public_html/views/almacen/administrar_solicitrudes_repuesto.html?v=2.8.19',
        controller: 'AuditoriaSesion',
        ncyBreadcrumb: {
          label: 'Administración de solicitud de repuesto',
          description: ''
        }
      })
      .state('polivalente.listado', {
        // Rondas
        url: '/listado_rondas',
        templateUrl:
          '/intranet-2/public_html/views/rondas/listado_rondas.html?v=2.8.19',
        controller: 'AuditoriaSesion',
        ncyBreadcrumb: {
          label: 'Listado de Rondas',
          description: ''
        }
      })
      .state('polivalente.ambiental', {
        // Rondas
        url: '/listado_rondas_ambientales',
        templateUrl:
          '/intranet-2/public_html/views/rondas/listado_rondas_ambientales.html?v=2.8.19',
        controller: 'AuditoriaSesion',
        ncyBreadcrumb: {
          label: 'Listado de Rondas Ambientales',
          description: ''
        }
      })
      .state('polivalente.planilla', {
        // Rondas
        url: '/planilla/:planilla_id',
        templateUrl:
          '/intranet-2/public_html/views/rondas/planilla.html?v=2.8.19',
        controller: 'AuditoriaSesion',
        ncyBreadcrumb: {
          label: 'Planilla de control',
          description: ''
        }
      })
      .state('sistemas.listado_rondas_sistemas', {
        // Rondas
        url: '/listado_rondas_sistemas/:RondaId',
        templateUrl:
          '/intranet-2/public_html/views/rondas/listado_rondas_sistemas.html?v=2.8.19',
        controller: 'AuditoriaSesion',
        ncyBreadcrumb: {
          label: 'Listado de rondas',
          description: ''
        }
      })
      .state('sistemas.Crear_detalleRonda_sistema', {
        // Rondas
        url: '/crear_detalle_ronda_sistema/:RondaId/:token',
        templateUrl:
          '/intranet-2/public_html/views/rondas/Crear_detalleRonda_sistema.html?v=2.8.19',
        controller: 'AuditoriaSesion',
        ncyBreadcrumb: {
          label: 'Crear rondas de Sistemas',
          description: ''
        }
      })
      .state('polivalente.rondaMantAdmin', {
        // Rondas
        url: '/ronda_mantenimiento/',
        templateUrl:
          '/intranet-2/public_html/views/rondas/rondaMantAdmin.html?v=2.8.19',
        controller: 'AuditoriaSesion',
        ncyBreadcrumb: {
          label: 'Rondas de mantenimiento',
          description: ''
        }
      })
      .state('polivalente.tarea', {
        // Rondas
        url: '/tarea/:token',
        templateUrl: '/intranet-2/public_html/views/rondas/tarea.html?v=2.8.19',
        controller: 'AuditoriaSesion',
        ncyBreadcrumb: {
          label: 'ver Tareas del dia',
          description: ''
        }
      })
      .state('polivalente.tareas', {
        // Rondas
        url: '/tareas/',
        templateUrl:
          '/intranet-2/public_html/views/rondas/tareas.html?v=2.8.19',
        controller: 'AuditoriaSesion',
        ncyBreadcrumb: {
          label: 'Asignar Tareas',
          description: ''
        }
      })
      .state('sistemas.ronda_sistemas', {
        // Rondas
        url: '/ronda_sistemas/',
        templateUrl:
          '/intranet-2/public_html/views/rondas/ronda_sistemas.html?v=2.8.19',
        controller: 'AuditoriaSesion',
        ncyBreadcrumb: {
          label: 'Realizar Ronda',
          description: ''
        }
      })
      .state('polivalente.seguimiento', {
        // Rondas
        url: '/seguimiento/:token',
        templateUrl:
          '/intranet-2/public_html/views/rondas/seguimiento.html?v=2.8.19',
        controller: 'AuditoriaSesion',
        ncyBreadcrumb: {
          label: 'Seguimiento de las Tareas',
          description: ''
        }
      })

      .state('observador_virtual.tokens', {
        // Observador
        url: '/tokens/',
        templateUrl:
          '/intranet-2/public_html/views/observador_virtual/tokens.html?v=2.8.19',
        controller: 'AuditoriaSesion',
        ncyBreadcrumb: {
          label: 'Generador de tokens para las cunas',
          description: ''
        }
      })
      .state('autorizacion.estadisticas', {
        // Autorizacion
        url: '/programar_correo/',
        templateUrl:
          '/intranet-2/public_html/views/Autorizacion/estadisticas.html?v=2.8.19',
        controller: 'AuditoriaSesion',
        ncyBreadcrumb: {
          label: 'Estadisticas MenaSoft',
          description: ''
        }
      })
      .state('autorizacion.programar_correo', {
        // Autorizacion
        url: '/programar_correo/',
        templateUrl:
          '/intranet-2/public_html/views/Autorizacion/programar_correo.html?v=2.8.19',
        controller: 'AuditoriaSesion',
        ncyBreadcrumb: {
          label: 'Generador de tokens para las cunas',
          description: ''
        }
      })
      .state('cm.DatosEstadisticos', {
        // central de mezclas
        url: '/estadisticas/',
        templateUrl:
          '/intranet-2/public_html/views/cm/DatosEstadisticos.html?v=2.8.19',
        controller: 'AuditoriaSesion',
        ncyBreadcrumb: {
          label: 'Estadisticas',
          description: ''
        }
      })
      .state('cm.RondaVerificacion', {
        // central de mezclas
        url: '/ronda_verificacion/',
        templateUrl:
          '/intranet-2/public_html/views/cm/ronda_verificacion.html?v=2.8.19',
        controller: 'AuditoriaSesion',
        ncyBreadcrumb: {
          label: 'Calendario Ronda',
          description: ''
        }
      })
      .state('cm.UpdateRondaVerificacion', {
        // central de mezclas
        url: '/update_ronda_verificacion/:RondaVerificacionId/:TipoMedicamentoId/:token/:TipoRonda/',
        templateUrl:
          '/intranet-2/public_html/views/cm/update_ronda_verificacion.html?v=2.8.19',
        controller: 'AuditoriaSesion',
        ncyBreadcrumb: {
          label: 'Actualizar Ronda',
          description: ''
        }
      })
      .state('cm.FVerificacionApro_Etiquetas', {
        // central de mezclas
        url: '/FVerificacionApro_Etiquetas/',
        templateUrl:
          '/intranet-2/public_html/views/cm/FVerificacionApro_Etiquetas.html?v=2.8.19',
        controller: 'AuditoriaSesion',
        ncyBreadcrumb: {
          label: 'Formato de verificación y aprobacion de etiquetas',
          description: ''
        }
      })
      .state('cm.CrearRondaVerificacionLoteado', {
        // central de mezclas
        url: '/ronda_verificacion_loteados/:token/',
        templateUrl:
          '/intranet-2/public_html/views/cm/crear_ronda_verificacion_loteados.html?v=2.8.19',
        controller: 'AuditoriaSesion',
        ncyBreadcrumb: {
          label: 'Crear Loteados',
          description: ''
        }
      })
      .state('cm.CrearRondaVerificacion', {
        // central de mezclas
        url: '/ronda_verificacion/:token/',
        templateUrl:
          '/intranet-2/public_html/views/cm/crear_ronda_verificacion.html?v=2.8.19',
        controller: 'AuditoriaSesion',
        ncyBreadcrumb: {
          label: 'Crear Ronda',
          description: ''
        }
      })
      .state('cm.generar_excel', {
        // central de mezclas
        url: '/generar_excel/:RondaVerificacionId/:TipoMedicamentoId/:FechaSeleccionada/:TipoRonda/',
        templateUrl:
          '/intranet-2/public_html/views/cm/generar_excel.html?v=2.8.19',
        controller: 'AuditoriaSesion',
        ncyBreadcrumb: {
          label: 'Generar Excel',
          description: ''
        }
      })
      .state('cm.ListadoRondaVerificacion', {
        // central de mezclas
        url: '/listado_ronda_verificacion/',
        templateUrl:
          '/intranet-2/public_html/views/cm/listado_ronda_verificacion.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Listado de Rondas',
          description: ''
        }
      })
      .state('cm.RotularMedicamentos', {
        // central de mezclas
        url: '/rotular_medicamentos/:token',
        templateUrl:
          '/intranet-2/public_html/views/cm/RotularMedicamentos.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Rotular medicamentos',
          description: ''
        }
      })
      .state('tm.tarifas', {
        // Transporte Maternas
        url: '/tarifas/',
        templateUrl: '/intranet-2/public_html/views/tm/tarifas.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Tarifas',
          description: ''
        }
      })
      .state('tm.agenda_maternas', {
        // Transporte Maternas
        url: '/agenda_maternas/',
        templateUrl:
          '/intranet-2/public_html/views/tm/agenda_maternas.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Agenda Maternas',
          description: ''
        }
      })
      .state('tm.actividad_mes', {
        // Transporte Maternas
        url: '/actividad_mes/',
        templateUrl:
          '/intranet-2/public_html/views/tm/actividad_mes.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Actividad Maternas por mes',
          description: ''
        }
      })
      .state('tm.eventos', {
        // Transporte Maternas
        url: '/listado_eventos/',
        templateUrl:
          '/intranet-2/public_html/views/tm/listado_eventos.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Listado de Eventos',
          description: ''
        }
      })
      .state('tm.eventos_admin', {
        // Transporte Maternas
        url: '/admin_listado_eventos/',
        templateUrl:
          '/intranet-2/public_html/views/tm/admin_listado_eventos.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Administrar el listado de Eventos',
          description: ''
        }
      })
      .state('tm.lideres', {
        // Transporte Maternas
        url: '/listado_lideres/',
        templateUrl:
          '/intranet-2/public_html/views/tm/listado_lideres.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Listado de lideres',
          description: ''
        }
      })
      .state('tm.maternas', {
        // Transporte Maternas
        url: '/listado_maternas/',
        templateUrl:
          '/intranet-2/public_html/views/tm/listado_maternas.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Listado de maternas',
          description: ''
        }
      })
      .state('configuracion.servicios', {
        // Configuraciones
        url: '/servicios',
        templateUrl:
          '/intranet-2/public_html/views/configuracion/servicios.html?v=2.8.19',
        controller: 'AuditoriaSesion',
        ncyBreadcrumb: {
          label: 'Servicios',
          description: ''
        }
      })
      .state('configuracion.ListaUsuarioColaborador', {
        // Configuraciones
        url: '/lista_usuario_colaboradores',
        templateUrl:
          '/intranet-2/public_html/views/configuracion/lista_usuario_colaboradores.html?v=2.8.19',
        controller: 'AuditoriaSesion',
        ncyBreadcrumb: {
          label: 'Lista de usuarios de colaboradores',
          description: ''
        }
      })
      .state('configuracion.crono_infra', {
        // Configuraciones
        url: '/crear_crono_infra',
        templateUrl:
          '/intranet-2/public_html/views/configuracion/crear_crono_infra.html?v=2.8.19',
        controller: 'AuditoriaSesion',
        ncyBreadcrumb: {
          label: 'Configurar Cronograma de infraestructura',
          description: ''
        }
      })
      .state('configuracion.EditarLimites', {
        // Configuraciones
        url: '/editar_limites',
        templateUrl:
          '/intranet-2/public_html/views/configuracion/editar_limites.html?v=2.8.19',
        controller: 'AuditoriaSesion',
        ncyBreadcrumb: {
          label: 'Editar limites en pedidos',
          description: ''
        }
      })
      .state('configuracion.cunas', {
        // Configuraciones
        url: '/cunas',
        templateUrl:
          '/intranet-2/public_html/views/configuracion/cunas.html?v=2.8.19',
        controller: 'AuditoriaSesion',
        ncyBreadcrumb: {
          label: 'Registrar Cunas',
          description: ''
        }
      })
      .state('configuracion.Cronograma', {
        // Configuraciones
        url: '/cronograma',
        templateUrl:
          '/intranet-2/public_html/views/configuracion/cronograma.html?v=2.8.19',
        controller: 'AuditoriaSesion',
        ncyBreadcrumb: {
          label: 'Modulo Cronograma',
          description: ''
        }
      })
      .state('configuracion.ModuloCronogramaSistemas', {
        // Configuraciones
        url: '/modulo_cronograma_sistemas',
        templateUrl:
          '/intranet-2/public_html/views/configuracion/cronograma_sistemas.html?v=2.8.19',
        controller: 'AuditoriaSesion',
        ncyBreadcrumb: {
          label: 'Modulo Cronograma Sistemas',
          description: ''
        }
      })
      .state('configuracion.sedes', {
        // Configuraciones
        url: '/sedes',
        templateUrl:
          '/intranet-2/public_html/views/configuracion/sedes.html?v=2.8.19',
        controller: 'AuditoriaSesion',
        ncyBreadcrumb: {
          label: 'Sedes',
          description: ''
        }
      })
      .state('configuracion.proveedores', {
        // Configuraciones
        url: '/proveedores',
        templateUrl:
          '/intranet-2/public_html/views/configuracion/proveedores.html?v=2.8.19',
        controller: 'AuditoriaSesion',
        ncyBreadcrumb: {
          label: 'Servicios',
          description: ''
        }
      })
      .state('configuracion.frecuencias', {
        // Configuraciones
        url: '/frecuencias',
        templateUrl:
          '/intranet-2/public_html/views/configuracion/frecuencias.html?v=2.8.19',
        controller: 'AuditoriaSesion',
        ncyBreadcrumb: {
          label: 'Frecuencias para mantenimientos',
          description: ''
        }
      })
      .state('configuracion.Empresa', {
        // Configuraciones
        url: '/empresa',
        templateUrl:
          '/intranet-2/public_html/views/configuracion/empresa.html?v=2.8.19',
        controller: 'AuditoriaSesion',
        ncyBreadcrumb: {
          label: 'Datos de la Empresa',
          description: ''
        }
      })
      .state('configuracion.EncabezadoPiePagina', {
        // Configuraciones
        url: '/encabezado_pie_pagina',
        templateUrl:
          '/intranet-2/public_html/views/configuracion/encabezado_pie_pagina.html?v=2.8.19',
        controller: 'AuditoriaSesion',
        ncyBreadcrumb: {
          label: 'Encabezados y pie de pagina',
          description: ''
        }
      })
      .state('configuracion.Usuarios', {
        // Configuraciones
        url: '/usuarios',
        templateUrl:
          '/intranet-2/public_html/views/configuracion/modulo_usuarios.html?v=2.8.19',
        controller: 'AuditoriaSesion',
        ncyBreadcrumb: {
          label: 'Modulo Usuarios',
          description: ''
        }
      })
      .state('configuracion.protocolo', {
        // Configuraciones
        url: '/protocolo',
        templateUrl:
          '/intranet-2/public_html/views/configuracion/protocolo.html?v=2.8.19',
        controller: 'AuditoriaSesion',
        ncyBreadcrumb: {
          label: 'Modulo Protocolo',
          description: ''
        }
      })
      .state('configuracion.Permisos', {
        // Configuraciones
        url: '/permisos',
        templateUrl:
          '/intranet-2/public_html/views/configuracion/modulo_permisos.html?v=2.8.19',
        controller: 'AuditoriaSesion',
        ncyBreadcrumb: {
          label: 'Modulo Permisos',
          description: ''
        }
      })
      .state('configuracion.ronda_impresoras', {
        // Configuraciones
        url: '/ronda_impresoras',
        templateUrl:
          '/intranet-2/public_html/views/configuracion/ronda_impresoras.html?v=2.8.19',
        controller: 'AuditoriaSesion',
        ncyBreadcrumb: {
          label: 'Modulo Ronda Impresoras',
          description: ''
        }
      })
      .state('configuracion.ronda_ambiental', {
        // Configuraciones
        url: '/ronda_ambiental',
        templateUrl:
          '/intranet-2/public_html/views/configuracion/ronda_ambiental.html?v=2.8.19',
        controller: 'AuditoriaSesion',
        ncyBreadcrumb: {
          label: 'Modulo Ronda Ambiental',
          description: ''
        }
      })
      .state('configuracion.grupos', {
        // Configuraciones
        url: '/grupos',
        templateUrl:
          '/intranet-2/public_html/views/configuracion/grupos.html?v=2.8.19',
        controller: 'AuditoriaSesion',
        ncyBreadcrumb: {
          label: 'Registrar grupos de articulos',
          description: ''
        }
      })
      .state('configuracion.correos_autorizacion', {
        // Configuraciones
        url: '/correos_autorizacion',
        templateUrl:
          '/intranet-2/public_html/views/configuracion/correos_autorizacion.html?v=2.8.19',
        controller: 'AuditoriaSesion',
        ncyBreadcrumb: {
          label: 'Registrar correos para Autorizaciones',
          description: ''
        }
      })
      .state('configuracion.articulos', {
        // Configuraciones
        url: '/articulos',
        templateUrl:
          '/intranet-2/public_html/views/configuracion/articulos.html?v=2.8.19',
        controller: 'AuditoriaSesion',
        ncyBreadcrumb: {
          label: 'Registrar articulos',
          description: ''
        }
      })
      .state('ver_cuna', {
        // Externo
        url: '/ver_cuna/:token',
        templateUrl:
          '/intranet-2/public_html/views/observador_virtual/ver_cuna.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Ver Cuna',
          description: ''
        }
      })
      .state('admision', {
        // Externo
        url: '/admision',
        templateUrl:
          '/intranet-2/public_html/views/observador_virtual/Admision.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Visita Virtual',
          description: ''
        }
      })
      .state('ver_cuna_ext', {
        // Externo
        url: '/ver_cuna_ext/:admision',
        templateUrl:
          '/intranet-2/public_html/views/observador_virtual/ver_cuna_ext.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Ver Cuna',
          description: ''
        }
      })
      .state('ver_cuna_int', {
        // Externo
        url: '/ver_cuna_int/',
        templateUrl:
          '/intranet-2/public_html/views/observador_virtual/ver_cuna_int.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Ver Cuna',
          description: ''
        }
      })
      .state('principal', {
        // Externo
        url: '/principal/',
        templateUrl: '/intranet-2/public_html/views/principal.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Intranet',
          description: ''
        }
      })
      // GESTION HUMANA
      .state('gh.vista', {
        // Externo
        url: '/vista/',
        templateUrl: '/intranet-2/public_html/views/gh/vista.html?v=2.8.19',
        controller: 'AuditoriaSesion',
        ncyBreadcrumb: {
          label: 'Vista diagrama',
          description: ''
        }
      })
      // GESTION HUMANA
      .state('lavanderia.vista', {
        // Externo
        url: '/vista/',
        templateUrl:
          '/intranet-2/public_html/views/lavanderia/vista.html?v=2.8.22',
        controller: 'AuditoriaSesion',
        ncyBreadcrumb: {
          label: 'Vista diagrama',
          description: ''
        }
      })
      // SOLICITUD
      .state('vsolicitud', {
        // Externo
        url: '/vsolicitud',
        templateUrl:
          '/intranet-2/public_html/views/Solicitud/ventana-SOL.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Ventana - SOL',
          description: ''
        }
      })
      .state('proceso-solicitud', {
        // Externo
        url: '/proceso-solicitud',
        templateUrl:
          '/intranet-2/public_html/views/Solicitud/CPSOL.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'PROCESO - SOL',
          description: ''
        }
      })
      .state('ver-proceso-solicitud', {
        // Externo
        url: '/ver-proceso-solicitud',
        templateUrl:
          '/intranet-2/public_html/views/Solicitud/VerPSOL.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'PROCESO - SOL',
          description: ''
        }
      })
      .state('create-nota-solicitud', {
        // Externo
        url: '/create-nota-solicitud',
        templateUrl:
          '/intranet-2/public_html/views/Solicitud/CNotaSol.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'PROCESO - Crear Nota',
          description: ''
        }
      })
      .state('create-solicitud-viatico', {
        // Externo
        url: '/create-solicitud-viatico',
        templateUrl:
          '/intranet-2/public_html/views/viaticos/CPreSOL.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Crear pre-solicitud de viatico',
          description: ''
        }
      })
      .state('autorizar-solicitud-viatico', {
        // Externo
        url: '/autorizar-solicitud-viatico',
        templateUrl:
          '/intranet-2/public_html/views/viaticos/AuthViatico.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Autorizar solicitud de viatico',
          description: ''
        }
      })
      .state('completar-solicitud-viatico', {
        // Externo
        url: '/completar-solicitud-viatico',
        templateUrl:
          '/intranet-2/public_html/views/viaticos/GSOL.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Crear solicitud de viatico - completa',
          description: ''
        }
      })
      .state('legalizar-solicitud-viatico', {
        // Externo
        url: '/legalizar-solicitud-viatico',
        templateUrl:
          '/intranet-2/public_html/views/viaticos/LegSolViatico.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'legalizacion de viatico',
          description: ''
        }
      })
      .state('anexos-legalizacion', {
        // Externo
        url: '/anexos-legalizacion',
        templateUrl:
          '/intranet-2/public_html/views/viaticos/anexos-legalizacion.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Anexos de la legalizacion',
          description: ''
        }
      })
      .state('create-orden-compra', {
        // Externo
        url: '/create-orden-compra',
        templateUrl:
          '/intranet-2/public_html/views/procesos/create-orden-compra.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Crear orden de compra',
          description: ''
        }
      })
      .state('admin_tarifas', {
        // Externo
        url: '/admin_tarifas',
        templateUrl:
          '/intranet-2/public_html/views/tm/admin_tarifas.html?v=2.8.19',
        ncyBreadcrumb: {
          label: 'Administrar las tarifas',
          description: ''
        }
      })
  },
])
