'use strict';
app.controller('RegUserCtrl', ["$scope", "$rootScope", "$state", "UsuarioService", "PersonaService", "SesionService",
    function ($scope, $rootScope, $state, UsuarioService, PersonaService, SesionService) {
        let vm = $scope;
        vm.USUARIO = $rootScope.username;
        vm.Documento = "";
        vm.Persona = null;

        vm.VerificarDocumento = () => {
            UsuarioService.VerificarDocumento(vm.Documento).then((d) => {
                if (typeof d.data != "string" && d.data.length > 0) {
                    vm.Persona = d.data[0];
                } else {
                    vm.Persona = null;
                }
            });
        };

        vm.VincularUsuario = () => {
            let obj = {
                VincularUsuarioCT: JSON.stringify({
                    PersonaId: vm.Persona.PersonaId,
                    ModifiedBy: vm.USUARIO.NombreCompleto,
                    UsuarioId: vm.USUARIO.UserId
                })
            };
            swal({
                title: `¿Deseas VINCULAR este usuario a ${vm.Persona.PrimerNombre} ${vm.Persona.PrimerApellido}`,
                text: "Nota: Una vez creado no podrás modificarlo!",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-primary",
                confirmButtonText: "VINCULAR USUARIO",
                cancelButtonText: "Cancelar!",
                closeOnConfirm: false,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {
                    PersonaService.putPersona(obj).then((d) => {
                        if (typeof d.data !== "string") {
                            swal("Enhorabuena", "Se han actualizado los datos con exito", "success");
                            vm.USUARIO.NombreCompleto = `${vm.Persona.PrimerNombre} ${vm.Persona.PrimerApellido}`;
                            vm.USUARIO.PersonaId = vm.Persona.PersonaId;
                            vm.USUARIO.Url_Foto = vm.Persona.Foto;
                            SesionService.set(vm.USUARIO, "UserData_Polivalente");
                            $state.go("app.inicio");
                        } else {
                            swal("Error", d.data, "error");
                        }
                    });
                }
            });
        };
        
        function _init(){
            if($rootScope.username.PersonaId){
                $state.go("app.inicio");
            }
        }
        _init();
    }]);