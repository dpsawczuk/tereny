/**
 * Klasa odpowiedzialna za połączenie z bazą danych
 */
class DataBase {
    
    protected $mMysqli; // Uchwyt połączenia z bazą danych
    
    /**
     * Konstruktor klasy DataBase
     */
    public function __construct() {
        // Połączenie z bazą danych
        $this->mMysqli = new mysqli("localhost", "login-do-bazy-danych", "hasło-do-bazy-danych", "nazwa-bazy-danych");
        // Ustawienie kodowania bazy danych na UTF-8
        $this->mMysqli->query('set names utf8');
        // Wyświetla komunikat o błędzie, jeśli baza danych nie nawiązała połączenia
        if (mysqli_connect_error()) {
            printf("Brak połączenia z bazą danych!");
            exit;
        }
    }
} // Koniec klasy DataBase
?>