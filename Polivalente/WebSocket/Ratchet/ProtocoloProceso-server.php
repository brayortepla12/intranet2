<?php
namespace Ratchet;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use Ratchet\ProtocoloProceso;
    require dirname(__FILE__) . '\..\autoload.php';

    $ProtocoloProceso = IoServer::factory(
        new HttpServer(
            new WsServer(
                new ProtocoloProceso()
            )
        ),
        8090,
            '0.0.0.0',
            "/protocolo_proceso"
    );

    $ProtocoloProceso->run();