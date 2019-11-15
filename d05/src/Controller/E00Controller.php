<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class E00Controller extends AbstractController
{
    private $database_host;
	private $database_name;
	private $database_user;
	private $database_password;
	private $pdo;
	private function _getConnection()
	{
        $connection = new \PDO("mysql:host=".$this->database_host, $this->database_user, $this->database_password);
		$connection->query("DROP DATABASE IF EXISTS ".$this->database_name.";
							CREATE DATABASE IF NOT EXISTS ".$this->database_name." CHARACTER SET utf8 COLLATE utf8_general_ci");
		$connection->query("use ".$this->database_name);
        return $connection;
	}
	
	private function _createTable($table)
	{
		$sql = "CREATE TABLE $table (
			    id int(11) AUTO_INCREMENT PRIMARY KEY,
			    username VARCHAR(255) UNIQUE,
			    name VARCHAR(255),
			    email VARCHAR(255) UNIQUE,
			    enable BOOLEAN,
		   	    birthdate DATETIME,
		   	    address LONGTEXT)";
	    $this->pdo->exec($sql);
	}
	
    /**
     * @Route("/generate")
     */
    public function generateAction()
    {
    	if ($this->container->hasParameter('database_host'))
			$this->database_host = $this->container->getParameter('database_host');
    	if ($this->container->hasParameter('database_name'))
			$this->database_name = $this->container->getParameter('database_name');
    	if ($this->container->hasParameter('database_user'))
			$this->database_user = $this->container->getParameter('database_user');
    	if ($this->container->hasParameter('database_password'))
			$this->database_password = $this->container->getParameter('database_password');
		$this->pdo = $this->_getConnection();
		$this->_createTable('ex00');
        $this->addFlash(
            'success',
            'Table and base created'
		);
		
        return $this->render('e00/index.html.twig');
	}
	
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        return $this->render('e00/index.html.twig');
    }
}
