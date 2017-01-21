<?php
/**
 * Created by PhpStorm.
 * User: jacsonsantos
 * Date: 21/01/17
 * Time: 01:49
 */

namespace JSantos\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use triagens\ArangoDb\Connection as ArangoConnection;
use triagens\ArangoDb\ConnectionOptions as ArangoConnectionOptions;
use triagens\ArangoDb\Exception as ArangoException;
use triagens\ArangoDb\UpdatePolicy as ArangoUpdatePolicy;

abstract class ConnectionArangoServiceProvider implements ServiceProviderInterface
{
    /**
     * @var string
     */
    protected $database = (string) '__system';
    /**
     * @var string
     */
    protected $endpoint = (string) 'tcp://127.0.0.1:8529';
    /**
     * @var string
     */
    protected $authType = (string) 'Basic';
    /**
     * @var string
     */
    protected $authUser = (string) 'root';
    /**
     * @var string|int
     */
    protected $authPassword = (string) '';
    /**
     * @var string
     */
    protected $connection = (string) 'Keep-Alive';
    /**
     * @var int
     */
    protected $timeout = (int) 3;
    /**
     * @var bool
     */
    protected $reconnect = (boolean) true;
    /**
     * @var bool
     */
    protected $create = (boolean) true;
    /**
     * @var string
     */
    protected $updatePolicy = ArangoUpdatePolicy::LAST;

    /**
     * @param Container $app
     */
    public function register(Container $app)
    {
        $pimple['connection'] = function () {
            // set up some basic connection options
            $connectionOptions = [
                // database name
                ArangoConnectionOptions::OPTION_DATABASE => $this->database,
                // server endpoint to connect to
                ArangoConnectionOptions::OPTION_ENDPOINT => $this->endpoint,
                // authorization type to use (currently supported: 'Basic')
                ArangoConnectionOptions::OPTION_AUTH_TYPE => $this->authType,
                // user for basic authorization
                ArangoConnectionOptions::OPTION_AUTH_USER => $this->authUser,
                // password for basic authorization
                ArangoConnectionOptions::OPTION_AUTH_PASSWD => $this->authPassword,
                // connection persistence on server. can use either 'Close' (one-time connections) or 'Keep-Alive' (re-used connections)
                ArangoConnectionOptions::OPTION_CONNECTION => $this->connection,
                // connect timeout in seconds
                ArangoConnectionOptions::OPTION_TIMEOUT => $this->timeout,
                // whether or not to reconnect when a keep-alive connection has timed out on server
                ArangoConnectionOptions::OPTION_RECONNECT => $this->reconnect,
                // optionally create new collections when inserting documents
                ArangoConnectionOptions::OPTION_CREATE => $this->create,
                // optionally create new collections when inserting documents
                ArangoConnectionOptions::OPTION_UPDATE_POLICY => $this->updatePolicy,
            ];
// turn on exception logging (logs to whatever PHP is configured)
            ArangoException::enableLogging();
            return new ArangoConnection($connectionOptions);
        };
    }
}