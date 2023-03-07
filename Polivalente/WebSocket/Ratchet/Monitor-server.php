<?php
namespace Ratchet;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use Ratchet\Monitor;
    require dirname(__FILE__) . '\..\autoload.php';

    $Monitor = IoServer::factory(
        new HttpServer(
            new WsServer(
                new Monitor()
            )
        ),
        8088,
            '0.0.0.0',
            "/Monitor"
    );

    $Monitor->run();