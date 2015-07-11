<?php

namespace Nats\tests\Util;

require 'vendor/autoload.php';

class ListeningServerStub
{
    protected $client;

    protected $sock;

    protected $addr;

    protected $port;

    public function __construct()
    {
        try {
            if (($this->sock = socket_create_listen(55555)) === false) {
                echo socket_strerror(socket_last_error());
            } else {
                echo "Socket created\n";
            }
            socket_getsockname($this->sock, $this->addr, $this->port);
        
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function close()
    {
        socket_close($this->sock);
    }

    public function getSock()
    {
        return $this->sock;
    }
}

$server = new ListeningServerStub();
$time=20;

while ($time>0) {
    time_nanosleep(1, 100000);
    $clientSocket = socket_accept($server->getSock());


    if (!is_null($clientSocket)) {
        $lll = socket_read($clientSocket, 100000);
        $line = "MSG OK 55966a4463383 10";
        $line = "PING";
        socket_write($clientSocket, $line);
    } else {
        $line = "PING";
        socket_write($server->getSock(), $line);
        time_nanosleep(1, 20000);
        continue;
    
    }
    $time--;
}

$server->close();