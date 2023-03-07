<?php
namespace Ratchet;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use Ratchet\Chat;
    require dirname(__FILE__) . '\..\autoload.php';

    $solicitud_mantenimiento = IoServer::factory(
        new HttpServer(
            new WsServer(
                new Chat()
            )
        ),
        8089,
            '0.0.0.0',
            "/solicitud_mantenimiento"
    );

    $solicitud_mantenimiento->run();