<?php

/**
 * @author Franklin ospino
 */
include_once dirname(__FILE__) . '/../BLL/ct/PersonaBLL.php';

class PersonaAPI {

    public function API() {
        header('Content-Type: application/JSON');
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'GET'://consultaTurnoId
                $Helper = new PersonaBLL();
                if (isset($_GET["Codigo"]) && isset($_GET["Dispositivo"])) {
                    echo json_encode($Helper->GetPersonaByCodigo($_GET["Codigo"], $_GET["Dispositivo"]));
                }else if (isset($_GET["TurnoId"])) {
                    echo json_encode($Helper->GetHorarioByTurnoId($_GET["TurnoId"]));
                }else if (isset($_GET["Turnos"])) {
                    echo json_encode($Helper->GetTurnos());
                }else if (isset($_GET["TurnoPersona"])) {
                    echo json_encode($Helper->GetTurnosByPersonaId($_GET["TurnoPersona"]));
                }else if (isset($_GET["Lider"])) {
                    echo json_encode($Helper->GetLideres());
                }else if (isset($_GET["LiderAll"]) && isset($_GET["Mes"]) && isset($_GET["Year"]) && isset($_GET["Tipo"])) {
                    echo json_encode($Helper->GetLideresAll($_GET["Year"], $_GET["Mes"], $_GET["Tipo"]));
                }else if (isset($_GET["ColaboradorAll"]) && isset($_GET["Mes"]) && isset($_GET["Year"]) && isset($_GET["Tipo"])) {
                    echo json_encode($Helper->GetColaboradoresAll($_GET["Year"], $_GET["Mes"], $_GET["Tipo"]));
                }else if (isset($_GET["UsuarioLider_plid"]) && isset($_GET["Mes_plid"]) && isset($_GET["Year_plid"]) && isset($_GET["Tipo_plid"])) {
                    echo json_encode($Helper->GetColaboradoresByLider($_GET["Year_plid"], $_GET["Mes_plid"], $_GET["Tipo_plid"], $_GET["UsuarioLider_plid"]));
                }else if (isset($_GET["LiderId_plid2"]) && isset($_GET["Mes_plid2"]) && isset($_GET["Year_plid2"]) && isset($_GET["Tipo_plid2"])) {
                    echo json_encode($Helper->GetColaboradoresByLiderId($_GET["Year_plid2"], $_GET["Mes_plid2"], $_GET["Tipo_plid2"], $_GET["LiderId_plid2"]));
                }else if (isset($_GET["Mes_es"]) && isset($_GET["Year_es"]) && isset($_GET["Tipo_es"]) && isset($_GET["PersonaId_es"]) && isset($_GET["TipoTurno_es"])) {
                    echo json_encode($Helper->GetListadoE_S($_GET["Year_es"], $_GET["Mes_es"], $_GET["Tipo_es"], $_GET["PersonaId_es"], $_GET["TipoTurno_es"]));
                }else if (isset($_GET["Mes_es2"]) && isset($_GET["Year_es2"]) && isset($_GET["PersonaId_es2"])) {
                    echo json_encode($Helper->GetListado_ES($_GET["Year_es2"], $_GET["Mes_es2"], $_GET["PersonaId_es2"]));
                }else if (isset($_GET["Mes_lid"]) && isset($_GET["Year_lid"]) && isset($_GET["Dia_lid"])) {
                    echo json_encode($Helper->GetESLideres($_GET["Year_lid"], $_GET["Mes_lid"], $_GET["Dia_lid"]));
                }else if (isset($_GET["Mes_col"]) && isset($_GET["Year_col"]) && isset($_GET["Dia_col"])) {
                    echo json_encode($Helper->GetESColaboradores($_GET["Year_col"], $_GET["Mes_col"], $_GET["Dia_col"]));
                }else if (isset($_GET["Cargo"])) {
                    echo json_encode($Helper->GetCargos());
                }else if (isset($_GET["Dispositivo"])) {
                    echo json_encode($Helper->GetDispositivos());
                }else if (isset($_GET["NombreDispositivo"])) {
                    echo json_encode($Helper->GetDispositivoByName($_GET["NombreDispositivo"]));
                }else if (isset($_GET["CargoId"])) {
                    echo json_encode($Helper->GetCargoById($_GET["CargoId"]));
                }else if (isset($_GET["Usuario_lider"])) {
                    echo json_encode($Helper->GetPersonasByLider($_GET["Usuario_lider"]));
                }else if (isset($_GET["PersonaId"])) {
                    echo json_encode($Helper->GetPersonaById($_GET["PersonaId"]));
                }else if (isset($_GET["LiderId_permiso"]) && isset($_GET["Mes_permiso"]) && isset($_GET["Year_permiso"])) {
                    echo json_encode($Helper->GetPermisoByLiderId($_GET["LiderId_permiso"], $_GET["Mes_permiso"], $_GET["Year_permiso"]));
                }else if (isset($_GET["CodigoTarjeta_gh"])) {
                    echo json_encode($Helper->GetPermisoByCodigoTarjeta($_GET["CodigoTarjeta_gh"]));
                }else if (isset($_GET["PermisoId"])) {
                    echo json_encode($Helper->GetPermisoByPermisoId($_GET["PermisoId"]));
                }else if (isset($_GET["Documento_especial"])) {
                    echo json_encode($Helper->GetPermisoByDocumento($_GET["Documento_especial"]));
                }else if (isset($_GET["Documento_persona"])) {
                    echo json_encode($Helper->GetPersonaByCedula($_GET["Documento_persona"]));
                }else if (isset($_GET["PersonaId_eva"]) && isset($_GET["FechaInicio_eva"]) && isset($_GET["FechaFin_eva"])) {
                    echo json_encode($Helper->GetPermisoByRango_PersonaId($_GET["PersonaId_eva"], $_GET["FechaInicio_eva"], $_GET["FechaFin_eva"]));
                }else if(isset ($_GET["Estado_persona"])){
                    echo json_encode($Helper->GetPersonas($_GET["Estado_persona"]));
                }else if(isset ($_GET["PersonasActivas"])){
                    echo json_encode($Helper->GetPersonasActivas());
                }else if(isset ($_GET["CantidadPersonaActivas"])){
                    echo json_encode($Helper->GetCPersonasActivas());
                }else if(isset ($_GET["AllPermiso"])){
                    echo json_encode($Helper->GetPermisos($_GET["AllPermiso"]));
                }else if(isset ($_GET["PermisoLimite"])){
                    echo json_encode($Helper->GetPermisosLimite($_GET["PermisoLimite"]));
                }else if(isset ($_GET["PermisoLimiteA"])){
                    echo json_encode($Helper->GetPermisosLimiteA($_GET["PermisoLimiteA"]));
                }else if(isset ($_GET["UltimoControlByPer"])){
                    echo json_encode($Helper->GetUltimoControlByPer($_GET["UltimoControlByPer"]));
                }else if(isset ($_GET["AllHorario"])){
                    echo json_encode($Helper->GetAllHorarios());
                }else if(isset ($_GET["Mes_ct"]) && isset ($_GET["Year_ct"]) && isset ($_GET["LiderId_ct"])){
                    $Helper->GetExcelParaSH($_GET["LiderId_ct"], $_GET["Mes_ct"], $_GET["Year_ct"]);
                }else if(isset ($_GET["Mes_ctxlsx"]) && isset ($_GET["Year_ctxlsx"]) && isset ($_GET["LiderId_ctxlsx"])){
                    $Helper->GetExcelEstadisticas($_GET["LiderId_ctxlsx"], $_GET["Mes_ctxlsx"], $_GET["Year_ctxlsx"]);
                }else if(isset ($_GET["Mes_horario"]) && isset ($_GET["Year_horario"]) && isset ($_GET["LiderId_horario"])){
                    echo json_encode($Helper->GetHorarioByJefeId($_GET["LiderId_horario"], $_GET["Mes_horario"], $_GET["Year_horario"]));
                }else if(isset ($_GET["Mes_hor"]) && isset ($_GET["Year_hor"]) && isset ($_GET["ColaboradorId_hor"])){
                    echo json_encode($Helper->GetHorarioByColaboradorId($_GET["ColaboradorId_hor"], $_GET["Mes_hor"], $_GET["Year_hor"]));
                }else if(isset ($_GET["SedeId_cambhor"]) && isset ($_GET["Mes_cambhor"]) && isset ($_GET["Year_cambhor"])){
                    echo json_encode($Helper->GetCambioHorarios($_GET["SedeId_cambhor"], $_GET["Mes_cambhor"], $_GET["Year_cambhor"]));
                }else if(isset ($_GET["SedeId_persede"]) && isset ($_GET["Mes_persede"]) && isset ($_GET["Year_persede"])){
                    echo json_encode($Helper->GetPermisosBySedeIdAndMes($_GET["SedeId_persede"], $_GET["Mes_persede"], $_GET["Year_persede"]));
                }else if(isset ($_GET["Usuario_persona"])){
                    echo json_encode($Helper->GetPersonaByUsuario($_GET["Usuario_persona"]));
                }else if(isset ($_GET["UsuarioIdRegUser"])){
                    echo json_encode($Helper->VerificarUsuarioIdRegUser($_GET["UsuarioIdRegUser"]));
                }else if(isset ($_GET["Estado_persona_lite"])){
                    echo json_encode($Helper->GetPersonasLite($_GET["Estado_persona_lite"]));
                }else if(isset ($_GET["VariablesUP"])){
                    echo json_encode($Helper->GetVariablesByUP($_GET["VariablesUP"]));
                }
                break;
            case 'POST'://inserta
                $Helper = new PersonaBLL();
                if (isset($_POST["Persona"])) {
                    $obj = json_decode($_POST["Persona"]);
                    echo json_encode($Helper->CreatePersona($obj));
                }else if (isset($_POST["Cargo"])) {
                    $obj = json_decode($_POST["Cargo"]);
                    echo json_encode($Helper->CreateCargo($obj));
                }else if (isset($_POST["Permiso"])) {
                    $obj = json_decode($_POST["Permiso"]);
                    echo json_encode($Helper->CreatePermiso($obj));
                }else if(isset($_POST["ControlesOffline"])){
                    $list = json_decode($_POST["ControlesOffline"]);
                    echo json_encode($Helper->CreateControlOffline($list));
                }else if(isset($_POST["Usuario"]) && isset($_POST["Data"]) && isset($_POST["IsUGH"])){
                    $list = json_decode($_POST["Data"]);
                    echo json_encode($Helper->CreateTurnosByUsuario($_POST["Usuario"], $list, $_POST["CreatedBy"], $_POST["Year"], $_POST["Mes"], $_POST["IsUGH"]));
                }else if(isset($_POST["Variable"])){
                    $obj = json_decode($_POST["Variable"]);
                    echo json_encode($Helper->CreateVariableUP($obj));
                }
                break;
            case 'PUT'://actualiza
                parse_str(file_get_contents('php://input'), $_PUT);
                $Helper = new PersonaBLL();
                if (isset($_PUT["Persona"])) {
                    $obj = json_decode($_PUT["Persona"]);
                    echo json_encode($Helper->UpdatePersona($obj));
                }else if (isset($_PUT["Cargo"])) {
                    $obj = json_decode($_PUT["Cargo"]);
                    echo json_encode($Helper->UpdateCargo($obj));
                }else if (isset($_PUT["Permiso_autorizar"]) && isset($_PUT["UsuarioGHId"]) && isset ($_PUT["ValidarPermisos"])) {
                    $Permisos = json_decode($_PUT["ValidarPermisos"]);
                    echo json_encode($Helper->AutorizarPermiso($_PUT["Permiso_autorizar"], $_PUT["UsuarioGHId"],$Permisos));
                }else if (isset($_PUT["PersonaId_ne"]) && isset($_PUT["EstadoNuevo_ne"]) && isset ($_PUT["ModifiedBy_ne"])) {
                    echo json_encode($Helper->UpdateEstadoPersona($_PUT["PersonaId_ne"], $_PUT["EstadoNuevo_ne"], $_PUT["ModifiedBy_ne"]));
                }else if(isset ($_PUT["SolicitudHorario"])){
                    $obj = json_decode($_PUT["SolicitudHorario"]);
                    echo json_encode($Helper->UpdateCambioHorario($obj[0]));
                }else if(isset ($_PUT["Traslados"])){
                    $obj = json_decode($_PUT["Traslados"]);
                    echo json_encode($Helper->UpdateJefe($obj[0]));
                }else if(isset ($_PUT["VincularUsuarioCT"])){
                    $obj = json_decode($_PUT["VincularUsuarioCT"]);
                    echo json_encode($Helper->VincularUsuarioCT($obj));
                }else if(isset ($_PUT["Variable"])){
                    $obj = json_decode($_PUT["Variable"]);
                    echo json_encode($Helper->UpdateVariable($obj));
                }
                break;
            case 'DELETE'://elimina
                parse_str(file_get_contents('php://input'), $_DELETE);
                $Helper = new PersonaBLL();
                if (isset($_DELETE["Permiso"])) {
                    $obj = json_decode($_DELETE["Permiso"]);
                    echo json_encode($Helper->DeletePermiso($obj));
                }
                break;
            default://metodo NO soportado
                echo 'METODO NO SOPORTADO';
                break;
        }
    }

}

//end class
