<?php

class Webpage {

    /**
     * @var string Texte compris entre <head> et </head>
     */
    private $head  = null ;

    /**
     * @var string Texte compris entre <title> et </title>
     */
    private $title = null ;

    /**
     * @var string Texte compris entre <body> et </body>
     */
    private $body  = null ;

    /**
     * @var string Header du site compris au debut de la balise <body>
     */
    private $header = null;

    /**
     * @var string Footer du site compris a la fin de la balise <body>
     */
    private $footer = null;

    /**
     * Constructeur
     * @param string $title Le titre de la page
     */
    public function __construct($title="GeekOnLan") {
        $this->title= $title;
        $auth='';
        if(!Member::isConnected())
            $auth=<<<HTML
                <li><a href="authentification.php">Connexion</a></li>
                <li><a href="#">S\'inscrire</a></li>
HTML;
        else 
            $auth=<<<HTML
            '<li><a href="authentification.php">Deconnexion</a></li>
            <li><a href="#">Profile</a></li>
HTML;

        $this->header= <<<HTML
        <header>
            <hr/>
            <img alt="GeekOnLanLogo" src="resources/img/logo.png" />
        </header>
        <nav>
            <ul>
                <li><a href="index.php">Accueil</a></li>
                <li><a href="lan.php">LAN</a></li>
            </ul>
            <ul>
                $auth
            </ul>
        </nav>
HTML;
    }

    /**
     * Protéger les caractères spéciaux pouvant dégrader la page Web
     * @param string $string La chaîne à protéger
     * @return string La chaîne protégée
     */
    public function escapeString($string) {
        return htmlentities($string, ENT_QUOTES | ENT_HTML5, "utf-8");
    }

    /**
     * Ajouter un contenu dans head
     * @param string $string le contenu à ajouter
     */
    public function appendToHead($string) {
        $this->head .= $string;
    }

    /**
     * Ajouter l'URL d'un script CSS dans head
     * @param string $url L'URL du script CSS
     */
    public function appendCssUrl($url) {
        $this->appendToHead(<<<HTML
    <link rel="stylesheet" type="text/css" href="{$url}">

HTML
        ) ;
    }

    /**
     * Ajouter l'URL d'un script JavaScript dans head
     * @param string $url L'URL du script JavaScript
     */
    public function appendJsUrl($url) {
        $this->appendToHead(<<<HTML
    <script type='text/javascript' src='$url'></script>

HTML
        ) ;
    }

    /**
     * Ajouter un contenu dans body
     * @param string $content Le contenu à ajouter
     */
    public function appendContent($content) {
        $this->body .= $content ;
    }

    /**
     * Produire la page Web complète
     * @return string
     */
    public function toHTML() {
        return <<<HTML
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>{$this->title}</title>
{$this->head}
    </head>
    <body>
{$this->header}
{$this->body}
{$this->footer}
    </body>
</html>
HTML;
    }

}