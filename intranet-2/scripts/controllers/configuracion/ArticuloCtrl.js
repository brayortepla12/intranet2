'use strict';
app.controller('ArticuloCtrl', ["$scope", "$rootScope", "ArticuloService", "GrupoService", "$filter",
    function ($scope, $rootScope, ArticuloService, GrupoService, $filter) {
        //<editor-fold defaultstate="collapsed" desc="inicializar variables">
        $scope.cargado = false;
        $scope.simpleTableOptions = {};
        $scope.Articulos = [];
        $scope.Grupos = [];
        $scope.TipoArticulo = '';
        $scope.Articulo = {
            Nombre: null,
            CodigoKrystalos: null,
            NombreKrystalos: null,
            GrupoId: null,
            ArticuloPara: '',
            CreatedBy: $rootScope.username.NombreUsuario
        };
        $scope.UArticulo = {};
        // inicializamos las variables en _init()
        _init();

        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Consultas">
        $scope.GetArticulos = () => {
            GetArticulo();
        };
        function GetArticulo() {
            $scope.cargado = false;
            $scope.simpleTableOptions = {};
            ArticuloService.getAllArticulo($scope.TipoArticulo).then(function (c) {
                console.log(c.data)
                
                $scope.simpleTableOptions = {
                    data: [],
                    aoColumns: [
                        {mData: 'CodigoKrystalos'},
                        {mData: 'NombreKrystalos'},
                        {mData: 'ArticuloId'},
                        {mData: 'Nombre'},
                        {mData: 'NombreGrupo'},
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
                };
                $scope.simpleTableOptions.data = SetFormat($filter("orderBy")(c.data, "Nombre"));
                $scope.cargado = true;
            });
        }
        function GetGrupo() {
            GrupoService.getAllGrupo().then(function (c) {
                $scope.Grupos = $filter("orderBy")(c.data, "-GrupoId");
                GetArticulo();

            });
        }
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="Botones guardar e imprimir">
        $scope.EditarArticulo = function(i){
           console.log($scope.simpleTableOptions.data[i]);
           $scope.UArticulo = $scope.simpleTableOptions.data[i];
           $scope.$apply();
           $("#UpdateArticuloModal").modal('show');
        };
        $scope.GuardarArticulo = function () {
            var data = {
                Articulo: JSON.stringify($scope.Articulo)
            };
            ArticuloService.PostArticulo(data).then(function (d) {
                if (typeof d.data !== "object") {
                    swal("Hubo un Error", d.data, "error");
                } else {
                    swal("Enhorabuena!!", "Se han guardado los datos satisfactoriamente", "success");
                    $scope.Articulo = {
                        Nombre: null,
                        CodigoKrystalos: null,
                        NombreKrystalos: null,
                        GrupoId: null,
                        ArticuloPara: 'Almacen',
                        CreatedBy: $rootScope.username.NombreUsuario
                    };
                    $('#ArticuloModal').modal('hide');
                    GetArticulo();
                }
            }, function (e) {
                swal("Hubo un Error", e, "error");
            });
        };
        
        $scope.UpdateArticulo = function(){
            $scope.UArticulo.ModifiedBy = $rootScope.username.NombreCompleto;
            var data = {
                Articulo: JSON.stringify($scope.UArticulo)
            };
            ArticuloService.PutArticulo(data).then(function (d) {
                if (typeof d.data !== "object") {
                    swal("Hubo un Error", d.data, "error");
                } else {
                    swal("Enhorabuena!!", "Se han guardado los datos satisfactoriamente", "success");
                    $scope.UArticulo = {};
                    $('#UpdateArticuloModal').modal('hide');
                    GetArticulo();
                }
            }, function (e) {
                swal("Hubo un Error", e, "error");
            });
        };

        $scope.Imprimir = function () {
            $.print("#printable");
        };

        //</editor-fold>
        function SetFormat(lst) {
            for (var i in lst) {
                lst[i].NombreGrupo = "";
                for (var k in $scope.Grupos) {
                    if ($scope.Grupos[k].GrupoId == lst[i].GrupoId) {
                        lst[i].NombreGrupo = $scope.Grupos[k].Nombre;
                    }
                }
                lst[i].Opciones = '<a class="btn  btn-info btn-xs icon-only white"  onclick=\"angular.element(this).scope().EditarArticulo(' + i + ')\"><i class="fa fa-pencil"></i></a>';
            }
            return lst;
        }
        function _init() {
            GetGrupo();
        }

    }]);


