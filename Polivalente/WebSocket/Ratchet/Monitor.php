<?php

namespace Ratchet;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Monitor implements MessageComponentInterface {

    protected $clients;

    public function __construct() {
        $this->clients = [];
    }

    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection to send messages to later
//        $this->clients[$conn->resourceId] = $conn;
//        echo "New connection! ({$conn->resourceId})\n";
        // Store the new connection so we can send messages to it later

        $this->clients[$conn->resourceId] = [
            'connection' => $conn
        ];
    }

    public function onMessage(ConnectionInterface $from, $msg) {
//        $numRecv = count($this->clients) - 1;
//        echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
//            , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');
//
//        foreach ($this->clients as $key => $client) {
//            if ($from !== $client) {
//                // The sender is not the receiver, send to each client connected
//                $client->send($msg);
//            }
//        }
//        // Send a message to a known resourceId (in this example the sender)
//        $client = $this->clients[$from->resourceId];
//        $client->send("Message successfully sent to $numRecv users.");
        $data = json_decode($msg);
        $valid_functions = ['pick', 'reset', 'connect', 'foradmin'];
        if (in_array($data->event, $valid_functions)) {
            $functionName = 'event' . $data->event;
            $this->$functionName($from, $data);
        } else {
            foreach ($this->clients as $client) {
                if ($data->UsuarioId === $client['UsuarioId']) {
                    // The sender is not the receiver, send to each client connected
                    $send_data = [
                        'event' => 'result',
                        'msg' => $data->msg,
                        'Nombres' => $data->Envia,
                        'clients' => $this->clients
                    ];
                    $client['connection']->send(json_encode($send_data));
                }
            }
            // Send a message to a known resourceId (in this example the sender)
//            $client = $this->clients[$from->resourceId];
//            $client->send("Message successfully sent to $numRecv users.");
        }
    }

    public function onClose(ConnectionInterface $conn) {
        // The connection is closed, remove it, as we can no longer send it messages
        unset($this->clients[$conn->resourceId]);
        $send_data = [
            'event' => 'connect',
            'clients' => $this->clients,
        ];
        $this->sendMessageToAll($send_data);
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }

    private function eventconnect(ConnectionInterface $from, $data) {
        if (!$this->IsInArray($data)) {
            $this->clients[$from->resourceId]['UsuarioId'] = $data->UsuarioId != NULL ? $data->UsuarioId : -9;
            $this->clients[$from->resourceId]['IP'] = $data->IP;
            $this->clients[$from->resourceId]['Maquina'] = $data->Maquina;
            $this->clients[$from->resourceId]['Aplicacion'] = $data->Aplicacion;
            $this->clients[$from->resourceId]['Usuario'] = $data->Usuario;
            $this->clients[$from->resourceId]['CurrentState'] = $data->CurrentState;
            $this->clients[$from->resourceId]['NombreCompleto'] = $data->NombreCompleto;
        }
        $send_data = [
            'event' => 'connect',
            'clients' => $this->clients,
        ];

        $this->sendMessageToAll($send_data);
    }

    function IsInArray($data) {
        foreach ($this->clients as $c) {
            if ($c['UsuarioId'] === $data->UsuarioId && $c['Aplicacion'] === $data->Aplicacion) {
                $c['CurrentState'] = $data->CurrentState;
                return true;
            }
        }
        return false;
    }

    private function eventforadmin(ConnectionInterface $from, $data) {
        $send_data = [
            'event' => 'result',
            'clients' => $this->clients,
            'Nombres' => $data->Envia,
            'msg' => $data->msg,
        ];
        foreach ($this->clients as $client) {
            if ($client['is_admin'] == true) {
                $client['connection']->send(json_encode($send_data));
            }
        }
    }

    private function sendMessageToAll($msg) {
        if (is_object($msg) || is_array($msg)) {
            $msg = json_encode($msg);
        }
        foreach ($this->clients as $client) {
//            echo print_r($client);
            $client['connection']->send($msg);
        }
    }

}
