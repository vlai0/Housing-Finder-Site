<?php
	class Database {
		const DB_DEBUG = true;
		public $pdo;
		
		public function __construct($databaseUser, $databaseName) {
			// Initialize variables
			$this->pdo = null;
			$databasePassword = '';
			
			$dsn = 'mysql:host=webdb.uvm.edu;dbname=' . $databaseName;
			
			$userLastSeven = substr($databaseUser, (strlen($databaseUser) - 7));
			
			include 'password.php';
			
			// Check if username has _reader or _writer at the end
			if ($userLastSeven == "_reader") {
				$databasePassword = $reader;
			} else if($userLastSeven == "_writer") {
				$databasePassword = $writer;
			}
			
			try {
				$this->pdo = new PDO($dsn, $databaseUser, $databasePassword);
				
				if (!$this->pdo) {
					if (self::DB_DEBUG) {
						print PHP_EOL . '<!-- NOT Connected -->' . PHP_EOL;
					}
					$this->pdo = 0;
				} else {
					if (self::DB_DEBUG) {
						print PHP_EOL . '<!-- Connected -->' . PHP_EOL;
					}
				}
			} catch (PDOException $e) {
				$error_message = $e->getMessage();
				if (self::DB_DEBUG) {
					print PHP_EOL . '<!-- Error connecting : ' . $error_message . ' -->' . PHP_EOL;
				}
			}
			
			return $this->pdo;
		}
		
		public function select($query, $values = '') {
			$statement = $this->pdo->prepare($query);
			
			if(is_array($values)){
				$statement->execute($values);
			} else {
				$statement->execute();
			}
			
			$recordSet = $statement->fetchAll(PDO::FETCH_ASSOC);
			$statement->closeCursor();
			return $recordSet;
		}

		public function displaySQL($sql, $data = '') {
			$sqlText = $sql;
			foreach ($data as $value) {
				$pos = strpos($sqlText, $values);
				if ($pos !== false) {
					$sqlText = substr_replace($sqlText, '"' . $value . '"', $pos, strlen($sqlText));
				}
			}
		
			print '<p>' . $sqlText . '</p>';
		}

		public function insert($query, $values = '') {
			$statement = $this->pdo->prepare($query);
			$goodRecord = false;
	
			if(is_array($values)){
				$goodRecord = $statement->execute($values);
			} else {
				$statement->execute();
			}
		
			$statement->closeCursor();
			return $goodRecord;
		}

		public function update($query, $values = '') {
			$statement = $this->pdo->prepare($query);
			$goodRecord = false;
	
			if(is_array($values)) {
				$goodRecord = $statement->execute($values);
			} else {
				$statement->execute();
			}
		
			$statement->closeCursor();
			return $goodRecord;
		}

		public function delete($query, $values = '') {
			$statement = $this->pdo->prepare($query);
			$goodRecord = false;
	
			if(is_array($values)) {
				$goodRecord = $statement->execute($values);
			} else {
				$statement->execute();
			}
		
			$statement->closeCursor();
			return $goodRecord;
		}

		public function lastInsert() {
			$primaryKey = -1;
			$query = 'SELECT LAST_INSERT_ID()';
			$statement = $this->pdo->prepare($query);
			$statement->execute();
			$recordSet = $statement->fetchAll();
			$statement->closeCursor();

			if($recordSet) {
				$primaryKey = $recordSet[0]['LAST_INSERT_ID()'];
			}

			return $primaryKey;
		}
	}
?>
