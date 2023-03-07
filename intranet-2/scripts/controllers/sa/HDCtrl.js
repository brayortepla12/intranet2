app.controller('HDCtrl', ["$scope", "$rootScope", "$state", "PacienteSAService", "HDService",
  function ($scope, $rootScope, $state, PacienteSAService, HDService) {
    //<editor-fold defaultstate="collapsed" desc="Variables">
    let vm = $scope;
    vm.EstadoConsultar = ValidarFecha();
    vm.Estado = "TODOS";
    vm.IsLoading = false;
    let DATE = moment();
    vm.Mes = DATE.format('M');
    vm.Year = DATE.format('YYYY');
    vm.Dia = DATE.format('D');
    vm.Mes2 = DATE.format('M');
    vm.Year2 = DATE.format('YYYY');
    vm.Dia2 = DATE.format('D');
    vm.UltimoDiaMes = Number(DATE.endOf("month").format("DD"));
    vm.HDs = {};
    vm.LHD = false;
    vm.OHD = false;
    vm.PHD = false;
    vm.IMPHD = false;
    vm.APreparar = "Desayuno";
    vm.HD = {
      Fecha: moment().format('YYYY-MM-DD'),
      Hora: moment().format('HH:mm:ss'),
      Sector: "",
      Detalles: [],
      Responsable: $rootScope.username.NombreCompleto,
      UResponsableId: $rootScope.username.UserId,
      ResponsableId: $rootScope.username.PersonaId,
      CreatedBy: $rootScope.username.NombreCompleto
    };
    vm.Sectores = [];
    vm.Variables = [];
    vm.PREFIJO = $state.current.name === "sa.listado_hd_cield" ? 'CIELD' : 'PRADO';
    vm.TVD = 0;
    vm.TVMM = 0;
    vm.TVA = 0;
    vm.TVMT = 0;
    vm.TVC = 0;
    vm.TVMN = 0;
    vm.Distribucion = '';
    vm.ImpDis = 'Desayuno';
    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="WATCHERS">
    var FuncionWatch = function (lst) {
      vm.TVD = 0;
      vm.TVMM = 0;
      vm.TVA = 0;
      vm.TVMT = 0;
      vm.TVC = 0;
      vm.TVMN = 0;
      if (vm.Variables.length === 0) {
        return false;
      }
      for (let i in vm.Variables) {
        vm.Variables[i].TD = 0;
        vm.Variables[i].TMM = 0;
        vm.Variables[i].TA = 0;
        vm.Variables[i].TMT = 0;
        vm.Variables[i].TC = 0;
        vm.Variables[i].TMN = 0;
      }
      for (let i in lst) {
        let Dindex = _.findIndex(vm.Variables, {
          VariableId: Number(lst[i].DesayunoId)
        });
        let MMindex = _.findIndex(vm.Variables, {
          VariableId: Number(lst[i].MMId)
        });
        let Aindex = _.findIndex(vm.Variables, {
          VariableId: Number(lst[i].AlmuerzoId)
        });
        let MTindex = _.findIndex(vm.Variables, {
          VariableId: Number(lst[i].MTId)
        });
        let Cindex = _.findIndex(vm.Variables, {
          VariableId: Number(lst[i].CenaId)
        });
        let MNindex = _.findIndex(vm.Variables, {
          VariableId: Number(lst[i].MNId)
        });
        if (Dindex >= 0) {
          vm.HD.Detalles[i].Desayuno = vm.Variables[Dindex].Abrv;
          vm.Variables[Dindex].TD++;
          vm.TVD++;
        }
        if (MMindex >= 0) {
          vm.HD.Detalles[i].MM = vm.Variables[MMindex].Abrv;
          vm.Variables[MMindex].TMM++;
          vm.TVMM++;
        }
        if (Aindex >= 0) {
          vm.HD.Detalles[i].Almuerzo = vm.Variables[Aindex].Abrv;
          vm.Variables[Aindex].TA++;
          vm.TVA++;
        }
        if (MTindex >= 0) {
          vm.HD.Detalles[i].MT = vm.Variables[MTindex].Abrv;
          vm.Variables[MTindex].TMT++;
          vm.TVMT++;
        }
        if (Cindex >= 0) {
          vm.HD.Detalles[i].Cena = vm.Variables[Cindex].Abrv;
          vm.Variables[Cindex].TC++;
          vm.TVC++;
        }
        if (MNindex >= 0) {
          vm.HD.Detalles[i].MN = vm.Variables[MNindex].Abrv;
          vm.Variables[MNindex].TMN++;
          vm.TVMN++;
        }
      }
    };
    vm.$watch('HD.Detalles', FuncionWatch, true);
    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="Eventos">
    vm.CambiarEstado = (i) => {
      if (vm.HD.Detalles[i].Continua && !vm.HD.Detalles[i].Nuevo) {
        vm.HD.Detalles[i].Continua = false;
        vm.HD.Detalles[i].Estado = "Suspendido";
      } else {
        vm.HD.Detalles[i].Continua = true;
        vm.HD.Detalles[i].Estado = "Activo";
      }
    };
    vm.Atras = () => {
      vm.LHD = true;
      vm.OHD = false;
      vm.UHD = false;
      vm.PHD = false;
      vm.IMPHD = false;
    };
    vm.OpenHD = (HDId) => {
      vm.OHD = false;
      HDService.getHDByIdNoP(HDId).then((d) => {
        if (typeof d.data !== "string") {
          vm.HD = d.data[0];
          vm.LHD = false;
          vm.OHD = true;
          if (vm.HD.Desayuno && !vm.HD.FCDesayuno) {
            vm.Distribucion = 'Desayuno'
          } else if (vm.HD.MM && !vm.HD.FCMM) {
            vm.Distribucion = 'MM'
          } else if (vm.HD.Almuerzo && !vm.HD.FCAlmuerzo) {
            vm.Distribucion = 'Almuerzo'
          } else if (vm.HD.MT && !vm.HD.FCMT) {
            vm.Distribucion = 'MT'
          } else if (vm.HD.Cena && !vm.HD.FCCena) {
            vm.Distribucion = 'Cena'
          } else if (vm.HD.MN && !vm.HD.FCMN) {
            vm.Distribucion = 'MN'
          }
          if (vm.Variables.length === 0) {
            vm.Variables = [];
            HDService.getVariables().then(function (c) {
              vm.Variables = c.data;
              FuncionWatch(d.data[0].Detalles);
            });
          } else {
            FuncionWatch(d.data[0].Detalles);
          }

        } else {
          swal("Error", d.data, "error");
          vm.OHD = false;
        }
      });
    };
    vm.ImprimirModal = () => {
      vm.ImpDis = vm.HD.Distribucion;
      vm.IMPHD = true;
    };
    vm.ImprimirHD = () => {
      vm.ToPrint = true;
      setTimeout(function () {
        printDiv();
      }, 600);
    };
    vm.ChangeSector = () => {
      GetPacientes();
    };
    vm.GetHDietas = () => {
      UpdateFecha();
      GetHDs();
    };
    vm.OpenPreparacion = () => {
      vm.PHD = true;
    };
    vm.ConsultarPreparacion = () => {
      GetCantidadAP();
    };
    vm.PrepararComida = () => {
      swal({
        title: `¿Deseas preparar ${vm.Distribucion}?`,
        text: "Nota: Este cambio no se puede DESHACER.",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-success",
        confirmButtonText: "Acepto",
        cancelButtonText: "Cancelar",
        closeOnConfirm: false,
        closeOnCancel: true
      }, function (isConfirm) {
        if (isConfirm && !vm.IsLoading) {
          vm.IsLoading = true;
          let data = {
            HDId: vm.HD.HDId,
            Detalles: vm.HD.Detalles,
            ModifiedBy: $rootScope.username.NombreCompleto,
            ResponsableId: $rootScope.username.PersonaId,
            UResponsableId: $rootScope.username.UserId,
            Distribucion: vm.Distribucion
          };
          let obj = {
            Apreparar: JSON.stringify(data)
          };
          HDService.putHD(obj).then((d) => {
            if (typeof d.data !== "string") {
              vm.IsLoading = false;
              swal("Enhorabuena", "Se han actualizado los datos con exito", "success");
              vm.PHD = false;
              vm.OHD = false;
              vm.LHD = true;
              vm.HD = {};
              GetHDs();
            } else {
              vm.IsLoading = false;
              swal("Error", d.data, "error");
            }
          }, () => {
            vm.IsLoading = false;
          }).catch(error => {
            console.error(error.stack || error);
            vm.IsLoading = false;
          });
        }
      });
    };
    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="Consultas">
    function GetSectores() {
      if (vm.Sectores.length === 0) {
        vm.Sectores = [];
        HDService.getSectores().then(function (c) {
          vm.Sectores = c.data;
        });
      }
    }

    function GetVariables() {
      if (vm.Variables.length === 0) {
        vm.Variables = [];
        HDService.getVariables().then(function (c) {
          vm.Variables = c.data;
        });
      }
    }

    function GetPacientes() {
      let obj = {
        Sector: vm.HD.Sector,
        Usuario: $rootScope.username.Email
      };
      PacienteSAService.getPacientesBySector(obj).then(function (d) {
        $scope.HD.Detalles = d.data;
      });
    }

    function GetCantidadAP() {
      vm.IsLoading = true;
      HDService.getCantidadAP(vm.EstadoConsultar, vm.Dia2, vm.Mes2, vm.Year2).then((d) => {
        vm.IsLoading = false;
        vm.CantidadAP = d.data;
      }, () => {
        vm.IsLoading = false;
      });
    }

    function GetHDs() {
      vm.LHD = false;
      vm.UHD = false;
      vm.OHD = false;
      HDService.getHDsByEmpresa(vm.Estado, vm.Dia, vm.Mes, vm.Year, $rootScope.username.UserId).then((d) => {
        vm.HDs = {
          data: [],
          aoColumns: [{
              mData: 'HDId'
            },
            {
              mData: 'Fecha'
            },
            {
              mData: 'SECTOR'
            },
            {
              mData: 'DESCRIPCION'
            },
            {
              mData: 'Estado'
            },
            {
              mData: 'FCDesayuno'
            },
            {
              mData: 'FCMM'
            },
            {
              mData: 'FCAlmuerzo'
            },
            {
              mData: 'FCMT'
            },
            {
              mData: 'FCCena'
            },
            {
              mData: 'FCMN'
            },
            {
              mData: 'Opciones'
            },
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
        };
        vm.HDs.data = SetFormat(d.data);
        vm.LHD = true;
      });
    }
    //</editor-fold>
    //<editor-fold defaultstate="collapsed" desc="Helpers">
    function ValidarFecha() {
      let tipo = "";
      let FechaHoy = moment();
      let fh = FechaHoy.format('YYYY-MM-DD');
      let HDesayuno = moment(`${fh} 09:00:00`);
      let HAlmuerzo = moment(`${fh} 14:00:00`);
      let HCena = moment(`${fh} 20:00:00`);
      if (FechaHoy.isBefore(HDesayuno)) {
        tipo = "Desayuno Preparado";
      } else if (FechaHoy.isBefore(HAlmuerzo)) {
        tipo = "Almuerzo Preparado";
      } else if (FechaHoy.isBefore(HCena)) {
        tipo = "Cena Preparada";
      }
      return tipo;
    }

    function printDiv() {
      $("#HD").print({
        globalStyles: false,
        mediaPrint: false,
        stylesheet: "/intranet-2/public_html/styles/hd.css",
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
        $scope.ToPrint = false;
        $scope.$apply();
      }, 1000);
    }

    function UpdateFecha() {
      $('#dia-select option:selected').removeAttr('selected');
      let f = moment(`${vm.Year}-${vm.Mes}-01`);
      vm.UltimoDiaMes = Number(f.endOf("month").format("DD"));
      if (vm.Dia > vm.UltimoDiaMes) {
        vm.Dia = Number(vm.UltimoDiaMes);
      }
    }

    function SetFormat(lst) {
      for (var i in lst) {
        lst[i].Opciones = '';
        lst[i].Opciones += '<a class="btn  btn-info btn-xs icon-only white" onclick=\"angular.element(this).scope().OpenHD(' + lst[i].HDId + ')\"><i class="fa fa-eye"></i></a>';

      }
      return lst;
    }
    //</editor-fold>
    function __init__() {
      GetHDs();
    }
    __init__();
  }
]);