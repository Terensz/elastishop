<?php
namespace framework\kernel\DbManager\entity;

use framework\kernel\component\Kernel;

class DbConnection
{
	private $id;
	private $driver;
	private $host;
    private $port;
	private $socket;
    private $name;
	private $username;
	private $password;

	public function __construct()
	{

	}

	public function setId($id)
	{
		$this->id = $id;
	}

	public function getId()
	{
		return $this->id;
	}

	public function setDriver($driver)
	{
		$this->driver = $driver;
	}

	public function getDriver()
	{
		return $this->driver;
	}

	public function setHost($host)
	{
		$this->host = $host;
	}

	public function getHost()
	{
		return $this->host;
	}

	public function setPort($port)
	{
		$this->port = $port;
	}

	public function getPort()
	{
		return $this->port;
	}

	public function setSocket($socket)
	{
		$this->socket = $socket;
	}

	public function getSocket()
	{
		return $this->socket;
	}

	public function setName($name)
	{
		$this->name = $name;
	}

	public function getName()
	{
		return $this->name;
	}

	public function setUsername($username)
	{
		$this->username = $username;
	}

	public function getUsername()
	{
		return $this->username;
	}

	public function setPassword($password)
	{
		$this->password = $password;
	}

	public function getPassword()
	{
		return $this->password;
	}
}
