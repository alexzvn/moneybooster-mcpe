<?php

namespace Alexzvn\MoneyBooster\Web;

use Alexzvn\MoneyBooster\Web\Exception;
use sekjun9878\RequestParser\Request;
use sekjun9878\RequestParser\RequestParser;

class Server 
{
    /**
     * The current host
     *
     * @var string
     */
    protected string $host;

    /**
     * The current port
     *
     * @var int
     */
    protected int $port;

    /**
     * The bind socket
     * 
     * @var resource
     */
    protected $socket = null;

    /**
     * Construct new Server instance
     * 
     * @param string $host
     * @param int $port
     * @return void
     */
    public function __construct(string $host, int $port)
    {
        $this->host = $host;
        $this->port = $port;

        // create a socket
        $this->createSocket();

        // bind the socket
        $this->bind();
    }

    /**
     *  Create new socket resource 
     *
     * @return void
     */
    protected function createSocket(): void
    {
        $this->socket = socket_create(AF_INET, SOCK_STREAM, 0);
    }
    
    /**
     * Bind the socket resource
     *
     * @throws \Exception
     * @return void
     */
    protected function bind()
    {
        if (! socket_bind($this->socket, $this->host, $this->port)) {
            throw new \Exception("Could not bind: $this->post:$this->port - ".socket_strerror(socket_last_error()));
        }
    }
    
    /**
     * Listen for requests 
     *
     * @param \Closure $callback
     * @return void 
     */
    public function listen(\Closure $callback): void
    {
        while (true) {

            socket_listen($this->socket);

            if (! $client = socket_accept($this->socket)) {
                socket_close( $client ); continue;
            }

            // create new request instance with the clients header.
            // In the real world of course you cannot just fix the max size to 1024..
            $request = $this->createRequest(socket_read($client, 1024));

            $response = $callback($request);

            if ( !$response || !$response instanceof Response) {
                $response = Response::error(404);
            }

            $response = (string) $response;

            // write the response to the client socket
            socket_write( $client, $response, strlen( $response ) );

            socket_close($client);
        }
    }

    protected function createRequest(string $raw): Request
    {
        $parser = new RequestParser;

        $parser->addData($raw);

        return Request::create($parser->exportRequestState());
    }
}
