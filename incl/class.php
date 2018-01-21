<?php_egg_logo_guid
/**
 * Klasa odpowiedzialna za połączenie z bazą danych
 */
class db {
    
    protected $dbSrv; // Uchwyt połączenia z bazą danych
    
    /**
     * Konstruktor klasy DataBase
     */
    public function __construct() {
        // Połączenie z bazą danych
        $this->dbSrv = new mysqli("localhost", "ter_db_oper", "s2QZxM7CpnzBTSLA");
        // Ustawienie kodowania bazy danych na UTF-8
        $this->dbSrv->query('set names utf8');
        // Wyświetla komunikat o błędzie, jeśli baza danych nie nawiązała połączenia
        if (mysqli_connect_error()) {
            printf("Brak połączenia z bazą danych!");
            exit;
        }
    }
	
	public function baza($nazwa){
		$dbSrv->select_db($nazwa);		
	}
	
	public function pytanie($wyraz,$wartosci){ //$wartości -> array
		$podanie = $dbSrv->prepare($wyraz);	
		//for() http://www.pontikis.net/blog/dynamically-bind_param-array-mysqli   http://php.net/manual/pl/mysqli-stmt.bind-param.php
		// Bind parameters. Types: s = string, i = integer, d = double,  b = blob 
		$podanie->bind_param('ss', $user, $md5_pass);
		$podanie->execute();
		$wynik = $podanie->get_result();														
		return $wynik->fetch_assoc();		
	}
	
} // Koniec klasy DataBase
?>