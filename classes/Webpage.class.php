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
     * @var string Option afficher en mode connecter
     */
    private $sidebar = null;

    /**
     * Constructeur
     * @param string $title Le titre de la page
     */
    public function __construct($title="GeekOnLan") {
        $this->title= $title;
        if(!Member::isConnected())
            $auth=<<<HTML
<li><a href="authentification.php">Connexion</a></li>
<li><a href="inscription.php">S'inscrire</a></li>
HTML;
        else 
            $auth=<<<HTML
<li><a href="authentification.php">Deconnexion</a></li>
<li><a href="profile.php">Profile</a></li>
HTML;

        $this->header=<<<HTML
        <header>
            <hr/>
            <img alt="GeekOnLanLogo" src="resources/img/logo.png" />
        </header>
        <nav id="menu">
            <ul>
                <li><a href="index.php">Accueil</a></li>
                <li><a href="lan.php">LAN</a></li>
            </ul>
            <ul>
                $auth
            </ul>
        </nav>
HTML;
        /*if(Member::isConnected()) {*/
            $this->sidebar = <<<HTML
<nav id="sidebar">
    <ul>
        <li><a href="#">Profil</a></li>
        <li><a href="#">LAN</a></li>
        <li><a href="#">Participations</a></li>
    </ul>
</nav>
HTML;
        /*}*/
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
    public function appendCssUrl($url, $media = null) {
        $media = ($media === null) ? "" : "media=\"" . $media . "\"";
        $this->appendToHead(<<<HTML
    <link rel="stylesheet" type="text/css" $media href="{$url}">

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
     * ajoute le css et le javascript de base
     */
    public function appendBasicCSSAndJS(){
        $this->appendCssUrl("style/regular/base.css", "screen and (min-width: 680px");
        $this->appendCssUrl("style/mobile/base.css", "screen and (max-width: 680px)");

        $this->appendJsUrl("http://code.jquery.com/jquery-2.1.4.min.js");
        $this->appendJsUrl("js/base.js");
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
{$this->sidebar}
        <div id="mainframe">
            <button id="sidebarButton" type="button">Insert something here</button>
{$this->header}
{$this->body}
{$this->footer}
        </div>
        <div id="layer"></div>
    </body>
</html>
HTML;
    }

}