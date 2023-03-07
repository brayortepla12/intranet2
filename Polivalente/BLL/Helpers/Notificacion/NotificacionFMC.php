<?php

/**
 * Description of NotificacionFMC
 *
 * @author DESARROLLO2
 */
class NotificacionFMC {

    private $title;
    private $message;
    private $fields;
    private $KEY;
    private $Icono;

    public function __construct($title, $message, $key, $icono) {
        $this->title = $title;
        $this->message = $message;
        $this->KEY = $key;
        $this->Icono = $icono;
    }

    public function SendNotificacion($to) {
        $this->PrepareFields($to);
        try {
            $API_ACCESS_KEY = $this->KEY;
            $headers = array
                (
                'Authorization: key=' . $API_ACCESS_KEY,
                'Content-Type: application/json'
            );
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($this->fields));
            $result = curl_exec($ch);
            curl_close($ch);
            return $result;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    private function PrepareFields($to) {
        $this->fields = array(
            'to' => $to,
            'data' => array(
                'body' => $this->message,
                'title' => $this->title,
                'sound' => 'default',
                'icon' => $this->Icono,
                'mensaje_foreground' => $this->message,
                'click_action' => "FCM_PLUGIN_ACTIVITY",
                'Icono' => $this->Icono
            ),
            'priority' => 'high',
            'notification' => array(
                'body' => $this->message,
                'title' => $this->title,
                'sound' => 'default',
                'icon' => $this->Icono,
                'mensaje_foreground' => $this->message,
                'click_action' => "FCM_PLUGIN_ACTIVITY",
                'Icono' => $this->Icono
            )
        );
    }

}