<?php

require_once 'vendor/autoload.php';

use Swoole\Http\Server;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\Coroutine\Http\Client;

$servidor = new Server("localhost", 8080);

$servidor->on('request', function(Request $request, Response $response) {
  // var_dump($request->get);
  // $response->header('Content-Type', 'text/html; charset=utf-8');
  // $response->end(printf($request->header, true));
  go(function() {
    $cliente = new Client('localhost', 8001);
    $cliente->get('/servidor.php');
    $conteudo = $cliente->getBody();
  });

  go(function() {
    $conteudo = file_get_contents('arquivo.txt');
  });

  go(function() use (&$response) {
    $primeiraResposta = '';
    $segundaResposta = '';
    $response->end($primeiraResposta . $segundaResposta);
  });
});

$servidor->start();
