<?php

final class MyPDO {
    /**
     * myPDO $_PDOInstance Instance unique.
     */
    private static $_PDOInstance = null ;

    /**
     * string $_DSN DSN pour la connexion BD.
     */
    private static $_DSN = null ;
    /**
     * string $_username Nom d'utilisateur pour la connexion BD.
     */
    private static $_username = null ;
    /**
     * string $_password Mot de passe pour la connexion BD.
     */
    private static $_password = null ;
    /**
     * array $_driverOptions Options du pilote BD.
     */
    private static $_driverOptions = array(
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    );

    /**
     * Constructeur privé.
     */
    private function __construct(){}

    /**
     * Point d'accès à l'instance unique.
     * L'instance est créée au premier appel et réutilisée aux appels suivants.
     * @throws Exception si la configuration n'a pas été effectuée.
     *
     * @return myPDO instance unique
     */
    public static function getInstance() {
        if (is_null(self::$_PDOInstance)){
            self::$_PDOInstance = new PDO(self::$_DSN, self::$_username, self::$_password, self::$_driverOptions) ;
        }
        return self::$_PDOInstance ;
    }

    /**
     * Fixer la configuration de la connexion à la BD.
     * @param string $dsn DNS pour la connexion BD.
     * @param string $username Utilisateur pour la connexion BD.
     * @param string $password Mot de passe pour la connexion BD.
     * @param array $driver_options Options du pilote BD.
     *
     * @return void
     */
    public static function setConfiguration($dsn, $username='', $password='', array $driver_options=array()) {
        self::$_DSN           = $dsn ;
        self::$_username      = $username ;
        self::$_password      = $password ;
        self::$_driverOptions = $driver_options + self::$_driverOptions ;
    }
}