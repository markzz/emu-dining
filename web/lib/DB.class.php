<?php

class DB {

	private static $dbh = null;

	public static function connect() {
		if (self::$dbh == null) {

			try {
				$dsn = "mysql:host=45.63.67.185;dbname=emu_menu";
				self::$dbh = new PDO($dsn, "emu_menu", "something rather obscure");
				self::$dbh->exec("SET NAMES 'utf8' COLLATE 'utf8_general_ci';");
			} catch (PDOException $e) {
				die("Failure connecting to DB");
			}

			return self::$dbh;
		}
	}
}