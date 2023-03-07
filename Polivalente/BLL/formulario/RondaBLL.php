<?php

require_once dirname(__FILE__) . '/../../DAL/formulario/RondaDAL.php';
require_once dirname(__FILE__) . '/../configuracion/SedeBLL.php';
require_once dirname(__FILE__) . '/../seguridad/UsuarioBLL.php';
require_once dirname(__FILE__) . '/ActividadesRondaBLL.php';
require_once dirname(__FILE__) . '/ActividadesUsuarioBLL.php';
require_once dirname(__FILE__) . '/../Helpers/Notificacion/NotificacionFMC.php';
require_once dirname(__FILE__) . '/../Helpers/Mail/sendMail.php';
require_once dirname(__FILE__) . '/../configuracion/EmpresaBLL.php';

class RondaBLL {

    public function GetRondaesByUsuario($UsuarioId) {
        $Helper = new RondaDAL();
        return $Helper->GetRondaByUsuario($UsuarioId);
    }

    public function GetRondaesById($RondaId) {
        $Helper = new RondaDAL();
        $har = new ActividadesRondaBLL();
        $Rondas = json_decode($Helper->GetRondaById($RondaId));
        foreach ($Rondas as $value) {
            $value->Equipos = $har->GetActividadesRondaByRondaId($value->RondaId);
        }
        return $Rondas;
    }
    
    public function GetTareas($UsuarioId) {
        $har = new ActividadesUsuarioBLL();
        return $har->GetActividadesPendientes($UsuarioId);
    }
    
    public function NotificarCumplimiento($Nombre,$ActividadesUsuarioId) {
        $hm = new sendMail();
        $hau = new ActividadesUsuarioBLL();
        $Eh = new EmpresaBLL();
        $Empresa = $Eh->GetEmpresa();
        $au = $hau->GetAllActividadUsuarioById($ActividadesUsuarioId)[0];
        // cambiamos el estado
        $hau->CambiarEstadoActividadesUsuario($ActividadesUsuarioId, "Terminado");
        $hn = new NotificacionFMC($au->Equipo,$au->Descripcion . '<br> ' . $Nombre .' >>>>> He terminado la tarea', 'AAAAxogIVlc:APA91bENY9JhKLbYgIs0-db-_JOWzYwQq7dwTlJcFuVt7eCOCTo0TBspfe-qdV4QipQ0IhrXQXO7y7eNpR47yzpHcZBCPHK5IvWggBA25GymL9X5-WySUKYoKi3fVVNMIIVNG9sk-hEs');
        $hu = new UsuarioBLL();
        //$u = $hu->GetUsuarioByNombre("RAFAEL ELIAS ARAUJO RODRIGUEZ");//JAIRO DAVID NUNEZ POLO
        $u = $hu->GetUsuarioByNombre($au->CreatedBy);
        $hn->SendNotificacion($u->Token);
        $u2 = $hu->GetUsuarioByNombre("DOUGLAS ALFONSO AMAYA");
        $mensaje = "<p><strong>$Nombre</strong> <br> $au->Equipo. <br><br>Tarea:<br> $au->Descripcion.</p>";
        $hm->EnviarEmail_Notificacion($Empresa, "He terminado mi tarea", $mensaje, $u2->Email, 'DOUGLAS ALFONSO AMAYA');
        
        return $hn->SendNotificacion($u2->Token);
    }
    
    public function Actualizar($Ronda, $ModifiedBy) {
        $Helper = new RondaDAL();
        foreach ($Ronda as $value) {
            $Helper->UpdateRonda($this->MapToUpdate($value,$ModifiedBy), $value->RondaId);
        }
        return $Ronda[0];
    }
    
    public function ActualizarActividadUsuario($ActividadUsuario, $ModifiedBy) {
        $Helper = new ActividadesUsuarioBLL();
        foreach ($ActividadUsuario as $value) {
            foreach ($value->Equipos as $o) {
                foreach ($o->Usuarios as $t) {
                    $Helper->ActualizarActividadesUsuario($t, $ModifiedBy, $t->ActividadUsuarioId);
                }
            }
//            $Helper->UpdateRonda($this->MapToUpdate($value,$ModifiedBy), $value->RondaId);
        }
        return array();
    }
    
    public function DeleteRondaByRondaId($RondaId) {
        $Helper = new RondaDAL();
        $Helper->DeleteRonda($RondaId);
        return $RondaId;
    }
    
    public function DeleteRondaById($RondaId) {
        $Helper = new RondaDAL();
        $har = new ActividadesRondaBLL();
        $Rondas = json_decode($Helper->GetRondaById($RondaId));
        foreach ($Rondas as $value) {
            $value->Equipos = $har->GetActividadesRondaByRondaId($value->RondaId);
        }
        return $Rondas;
    }

    public function GetAllRondas($UsuarioId) {
        $Helper = new RondaDAL();
        $har = new ActividadesRondaBLL();
        $Rondas = json_decode($Helper->GetAllRondas($UsuarioId));
        foreach ($Rondas as $value) {
            $value->Equipos = $har->GetActividadesRondaByRondaId($value->RondaId);
        }
//        echo print_r($Rondas);
        return $Rondas;
    }
    
    public function GetAllRondasHiastorico($UsuarioId) {
        $Helper = new RondaDAL();
        $har = new ActividadesRondaBLL();
        $Rondas = json_decode($Helper->GetAllRondasHistorico($UsuarioId));
        foreach ($Rondas as $value) {
            $value->Equipos = $har->GetActividadesRondaByRondaId($value->RondaId);
        }
//        echo print_r($Rondas);
        return $Rondas;
    }
    
    public function GetAllRondasHiastorico_lite($UsuarioId) {
        $Helper = new RondaDAL();
        $har = new ActividadesRondaBLL();
        $Rondas = json_decode($Helper->GetAllRondasHistorico($UsuarioId));
//        foreach ($Rondas as $value) {
//            $value->Equipos = $har->GetActividadesRondaByRondaId($value->RondaId);
//        }
//        echo print_r($Rondas);
        return $Rondas;
    }
    
    public function GetAllRondasLite($UsuarioId) {
        $Helper = new RondaDAL();
        $Rondas = json_decode($Helper->GetAllRondasLite($UsuarioId));
        return $Rondas;
    }

    public function GetAllRondasByFecha($Fecha, $UsuarioId) {
        $Helper = new RondaDAL();
        $har = new ActividadesRondaBLL();
        $Rondas = json_decode($Helper->GetAllRondasByFecha($Fecha, $UsuarioId));
        foreach ($Rondas as $value) {
            $value->Equipos = $har->GetActividadesRondaByRondaId($value->RondaId);
        }
//        echo print_r($Rondas);
        return $Rondas;
    }

    public function CreateRonda($list) {
        $Helper = new RondaDAL();
        $id = $Helper->CreateRonda($this->MAPToArray($list));
        if ($id) {
            $har = new ActividadesRondaBLL();
            for ($index = 0; $index < count($list); $index++) {
                $id2 = $har->CreateActividadesRonda($list[$index]->Equipos, $id[$index], $list[$index]->Realizo);
            }
            return $id2;
        }
        return $id;
    }

    public function AsignarRonda($list, $CreatedBy) {
        $Helper = new RondaDAL();
        $Listado = array();
        $auh = new ActividadesUsuarioBLL();
        foreach ($list as $value) {
            foreach ($value->Equipos as $e) {
                $auh->DeleteRondaUsuarioByActividadRondaId($e->ActividadesRondaId);
                foreach ($e->Usuarios2 as $o) {
                    $uh = new UsuarioBLL();
                    $u = $uh->GetUsuarioByNombre($o);
                    array_push($Listado, array(
                        'ActividadesRondaId' => $e->ActividadesRondaId,
                        'UsuarioId' => $u->UsuarioId,
                        'CreatedBy' => $CreatedBy
                    ));
                    // NOTIFICAMOS EL O lOs usuarios
                    $nh = new NotificacionFMC($value->Servicio, $e->Descripcion,'AAAAxogIVlc:APA91bENY9JhKLbYgIs0-db-_JOWzYwQq7dwTlJcFuVt7eCOCTo0TBspfe-qdV4QipQ0IhrXQXO7y7eNpR47yzpHcZBCPHK5IvWggBA25GymL9X5-WySUKYoKi3fVVNMIIVNG9sk-hEs');
                    if ($u->Token != NULL) {
                        $nh->SendNotificacion($u->Token);
                    }
                }
            }
        }
//        echo print_r($Listado);
        $auh->CreateActividadesUsuario($Listado);
//        $har = new ActividadesRondaBLL();
//        for ($index = 0; $index < count($list); $index++) {
//            $id2 = $har->CreateActividadesRonda($list[$index]->Equipos, $id[$index], $list[$index]->Realizo);
//        }
        return $Listado;
    }

    public function CambiarEstadoRonda($RondaId, $Estado = "Leido") {
        $Helper = new RondaDAL();
        return $Helper->UpdateRonda($this->GetCambioEstado($Estado), $RondaId);
    }

    public function CountRondaes($UsuarioId) {
        $Helper = new RondaDAL();
        $hu = new SedeBLL();
        $lsede = json_decode($hu->GetAllByUserId($UsuarioId));
        $contador = 0;
        foreach ($lsede as $value) {
            $contador += $Helper->CountRondaes($value->SedeId)[0]->Total;
        }
        return Array("Total" => $contador);
    }

    public function MAPToArray($list) {
        $list2 = Array();
        for ($index = 0; $index < count($list); $index++) {
            array_push($list2, Array(
                'Fecha' => $list[$index]->Fecha,
                'NombreJefeArea' => $list[$index]->NombreJefeArea,
                'Realizo' => $list[$index]->Realizo,
                'SedeId' => $list[$index]->SedeId,
                'ServicioId' => $list[$index]->ServicioId,
                'Observaciones' => $list[$index]->Observaciones,
                'CreatedBy' => $list[$index]->Realizo,
                'CreatedAt' => $this->getDatetimeNow()
            ));
        }
        return $list2;
    }
    
    public function MAPToUpdate($list, $ModifiedBy) {
        $list2 = Array();
            array_push($list2, Array(
                'Cumplimiento' => $list->Cumplimiento,
                'ObservacionSeguimiento' => $list->ObservacionSeguimiento,
                'Estado' => $list->Cumplimiento == 'SI' ? 'Finalizado' : 'Activo',
                'ModifiedBy' => $ModifiedBy,
                'ModifiedAt' => $this->getDatetimeNow()
            ));
        return $list2;
    }

    function GetCambioEstado($Estado) {
        $list2 = Array();
        array_push($list2, Array(
            'Estado' => $Estado,
            'ModifiedAt' => $this->getDatetimeNow()
        ));

        return $list2;
    }

    private function getDatetimeNow() {
        $tz_object = new DateTimeZone('America/Bogota');
        //date_default_timezone_set('Brazil/East');

        $datetime = new DateTime();
        $datetime->setTimezone($tz_object);
        return $datetime->format('Y\-m\-d\ h:i:s');
    }

}
