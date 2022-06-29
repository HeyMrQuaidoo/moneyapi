<?php
namespace App\Http\Controllers\Auth;

use \OAuth2\Storage;
use \OAuth2\Storage\UserCredentialsInterface;

class DoLoginPdo extends \OAuth2\Storage\Pdo
{
    public function __construct($connection, $config = array())
    {
        parent::__construct($connection, $config);
        $this->config['user_table'] = 'clients';
    }
}