<?php

class Database {
	private $host = 'localhost';
	private $dbname = 'project_management_db';
	private $username = 'root';
	private $password = '';
	private $pdo;

	public function connect() {
		if ($this->pdo === null) {
			try {
				$this->pdo = new PDO(
					"mysql:host={$this->host};dbname={$this->dbname};charset=utf8mb4",
					$this->username,
					$this->password,
					[
						PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
						PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
					]
				);
			} catch (PDOException $e) {
				die("Database connection failed.");
			}
		}

		return $this->pdo;
	}
}