<?php

require_once 'vendor/autoload.php';

use Swoole\Http\Server;
use Swoole\Http\Request;
use Swoole\Http\Response;

CO::set(['hook_flags' => SWOOLE_HOOK_ALL]);

$servidor = new Server("localhost", 8080);

$servidor->on('request', function(Request $request, Response $response) {
  // var_dump($request->get);
  // $response->header('Content-Type', 'text/html; charset=utf-8');
  // $response->end(printf($request->header, true));
  $channel = new chan(2);

  go(function() use ($channel) {
    $curl = curl_init('http://localhost:8001/servidor.php');
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    
    $conteudo = curl_exec($curl);
    $channel->push($conteudo);
  });

  go(function() use ($channel) {
    $conteudo = file_get_contents('arquivo.txt');
    $channel->push($conteudo);
  });

  go(function() use ($channel, &$response) {
    $primeiraResposta = $channel.pop();
    $segundaResposta = $channel.pop();
    $response->end($primeiraResposta . $segundaResposta);
  });
});

$servidor->start();
