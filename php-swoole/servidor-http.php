<?php

use Swoole\Http\Server;
use Swoole\Http\Request;
use Swoole\Http\Response;

$servidor = new Server("localhost", 8080);

$servidor->on('request', function(Request $request, Response $response) {
  // var_dump($request->get);
  $response->header('Content-Type', 'text/html; charset=utf-8');
  $response->end(printf($request->header, true));
});

$servidor->start();
