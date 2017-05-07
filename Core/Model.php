<?php 
namespace Core;
use PDO;
use App\Config;

	abstract class Model{
		// DB PDO Connection
		protected static function getDB($db){
			static $db = null;
			if ($db === null) {
				// $host = Config::DB_HOST
				// $dbname = Config::DB_NAME;
				// $username = Config::DB_USER;
				// $password = Config::DB_PASS;
				try {
					$dsn = 'mysql:host='.Config::DB_HOST.';dbname='.Config::DB_NAME.';charset=utf8';
					$db = new PDO($dsn, Config::DB_USER, Config::DB_PASS);
					$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERROMODE_EXCEPTION);
					return $db;
				} catch (PDOException $e) {
					echo $e->getMessage();
				}
			}
		}
	}