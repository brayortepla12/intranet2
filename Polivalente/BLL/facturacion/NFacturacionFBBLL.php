<?php

/**
 * Description of NFacturacionFBBLL
 *
 * @author DESARROLLO2
 */
require_once dirname(__FILE__) . '/../seguridad/UsuarioBLL.php';
require_once dirname(__FILE__) . '/../seguridad/UsuarioBLL.php';
include_once dirname(__FILE__) . '/../Helpers/Notificacion/NotificacionFMC.php';

class NFacturacionFBBLL {

    // LLave de FireBase Message Cloud
    private $KEY = "AAAAFNn5W9o:APA91bH7_K3RYZ28wIlSTGRbPHUWgrAoVsp15svtPwukRRdHhJGv0Cy4S90NSjYdlfEz_M-2ERCfC4HDH2FGpvCW3DltAp7Mq7_Aw4I3MXeSrAawJxP_lCgpiaxDjhxXXnkWP9h42IY0";
    private $datetime;

    public function __construct() {
        $tz_object = new DateTimeZone('America/Bogota');
        $this->datetime = new DateTime();
        $this->datetime->setTimezone($tz_object);
    }

    public function NotificarByUsuarioId($UsuarioId) {
        $Mes = $this->datetime->format('m');
        $Ano = $this->datetime->format('Y');
        $hu = new UsuarioBLL();
        $u = $hu->GetUsuarioById($UsuarioId);
        $this->EnviarNotificaciones($u, $Mes, $Ano);
    }

    public function NotificarALLUsuario() {
        $Mes = $this->datetime->format('m');
        $Ano = $this->datetime->format('Y');
        $hu = new UsuarioBLL();
        $Usuarios = $hu->GetALLFB();
        foreach ($Usuarios as $u) {
            $this->EnviarNotificaciones($u, $Mes, $Ano);
        }
    }

    public function NotificarALLUsuarioByMesAnterior() {
        $Mes = $this->datetime->format('m') - 1;
        $Ano = $this->datetime->format('Y');
        $hu = new UsuarioBLL();
        $Usuarios = $hu->GetALLFB();
        foreach ($Usuarios as $u) {
            $this->EnviarNotificacionesMensuales($u, $Mes, $Ano);
        }
    }

    public function EnviarNotificaciones($u, $Mes, $Ano) {
        if ($u->FCield) {
            $fh = $this->GetData('http://190.131.221.26:8080/Polivalente/api/Facturacion.php?FacturadoHoy=True');
            $fh2 = $this->GetData("http://190.131.221.26:8080/Polivalente/api/Facturacion.php?FacturadoMes_FB=$Mes&FacturadoAnno_FB=$Ano");
            echo "Total MES: $fh2->TotalFacturadoMes \nTotal HOY:" . $fh->FacturadoHoy[0]->valor;
            $nh = new NotificacionFMC("Facturado - CIELD", "Total MES: $fh2->TotalFacturadoMes \nTotal HOY: " . $fh->FacturadoHoy[0]->valor, $this->KEY, "logo_redondo");
            $nh->SendNotificacion($u->TokenFB);
        }
        if ($u->FPrado) {
            $fh = $this->GetData('http://190.242.60.208/intranet-2/api/GraficaPantallaPrado.php?TotalFacturadoHoy=True');
            $fh2 = $this->GetData("http://190.242.60.208/intranet-2/api/GraficaPantallaPrado.php?FacturadoMesFB=$Mes&FacturadoAnnoFB=$Ano");
            $nh = new NotificacionFMC("Facturado - PRADO", "Total MES:" . $fh2->Total . "\nTotal HOY: " . $fh->TotalFacturadoHoy[0]->valor, $this->KEY, "logo_prado");
            $nh->SendNotificacion($u->TokenFB);
        }
    }

    public function EnviarNotificacionesMensuales($u, $Mes, $Ano) {
        $NombreMes = ucwords($this->nombremes($Mes));
        if ($u->FCield) {
            $fh2 = $this->GetData("http://190.131.221.26:8080/Polivalente/api/Facturacion.php?FacturadoMes_FB=$Mes&FacturadoAnno_FB=$Ano");
            echo "Total MES: $fh2->TotalFacturadoMes";
            $nh = new NotificacionFMC("Facturado - CIELD", "Cierre $NombreMes: $fh2->TotalFacturadoMes", $this->KEY, "logo_redondo");
            $nh->SendNotificacion($u->TokenFB);
        }
        if ($u->FPrado) {
            $fh2 = $this->GetData("http://190.242.60.208/intranet-2/api/GraficaPantallaPrado.php?FacturadoMesFB=$Mes&FacturadoAnnoFB=$Ano");
            $nh = new NotificacionFMC("Facturado - PRADO", "Cierre $NombreMes: $fh2->Total", $this->KEY, "logo_prado");
            $nh->SendNotificacion($u->TokenFB);
        }
    }

    private function GetData($url) {
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $result = curl_exec($ch);
            curl_close($ch);
            return json_decode($result);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
        return Null;
    }
    
    function nombremes($mes) {
        setlocale(LC_TIME, 'spanish');
        $nombre = strftime("%B", mktime(0, 0, 0, $mes, 1, 2000));
        return $nombre;
    }

}
