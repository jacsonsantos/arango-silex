<?php
/**
 * Created by PhpStorm.
 * User: jacsonsantos
 * Date: 21/01/17
 * Time: 03:04
 */
namespace Exemplo\Provider;

use JSantos\Provider\ConnectionArangoServiceProvider;

class ConnectionArango extends ConnectionArangoServiceProvider
{
    /**
     * Nome de seu Banco de Dados ArangoDB
     * @var string
     */
    protected $database = "meu_banco";
    /**
     * Usuario de seu Banco de Dados ArangoDB
     * @var string
     */
    protected $authUser = "meu_usuario";
    /**
     * Senha de seu Banco de Dados ArangoDB
     * @var string
     */
    protected $authPassword = "minha_senha";
}