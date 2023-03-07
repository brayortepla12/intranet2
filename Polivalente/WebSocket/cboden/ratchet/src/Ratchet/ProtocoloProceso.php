<?php
namespace Ratchet;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class ProtocoloProceso implements MessageComponentInterface {

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
                    if ($data->UsuarioId === $client['UsuarioId'] || $client["is_admin"] == true) {
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
        $this->clients[$from->resourceId]['UsuarioId'] = $data->UsuarioId != NULL ? $data->UsuarioId : -9;
        $this->clients[$from->resourceId]['is_admin'] = $data->is_admin;
        $send_data = [
            'event' => 'connect',
            'clients' => $this->clients,
        ];
        $this->sendMessageToAll($send_data);
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
            $client['connection']->send($msg);
        }
    }

}
