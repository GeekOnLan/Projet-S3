<?php

class GeekOnLanWebpage extends Webpage {

    /**
     * @var string texte de la fenêtre principale
     */
    private $mainframe = null;

    /**
     * @var string texte de la sidebar (mode connecté)
     */
    private $sidebar = null;

    /**
     * Construit une page Web sur le modèle de GeekOnLan
     * @param string $title le titre de la page
     */
    public function __construct($title) {
        parent::__construct($title);

        $connected = Member::isConnected();

        $this->appendBasicCSSAndJS();
        $this->insertGeekOnLanHeader($connected);
        $this->mainframe .= ($connected) ? "<button id='sidebarButton' type='button'>Insert something here</button>" : "";

        if($connected)
            $this->insertGeekOnLanSidebar();
    }

    /**
     * Ajoute un contenu à la fenêtre principale
     * @param string $content
     */
    public function appendContent($content) {
        $this->mainframe .= $content;
    }

    /**
     * Ajoute le css et le javascript de base
     */
    private function appendBasicCSSAndJS(){
        $this->appendCssUrl("style/regular/base.css", "screen and (min-width: 680px");
        $this->appendCssUrl("style/mobile/base.css", "screen and (max-width: 680px)");

        $this->appendJsUrl("http://code.jquery.com/jquery-2.1.4.min.js");
        $this->appendJsUrl("js/base.js");
    }

    /**
     * Ajoute la barre mode connecté de GeekOnLan
     */
    private function insertGeekOnLanSidebar() {
        $this->sidebar = <<<HTML
<nav id="sidebar">
    <ul>
        <li><a href="#">Profil</a></li>
        <li><a href="#">LAN</a></li>
        <li><a href="#">Participations</a></li>
    </ul>
</nav>
HTML;
    }

    /**
     * Ajoute le header GeekOnLan
     * @param $memberConnected, le joueur est-il connecté
     */
    private function insertGeekOnLanHeader($memberConnected) {
        if(!$memberConnected)
            $auth=<<<HTML
<li><a href="authentification.php">Connexion</a></li>
<li><a href="inscription.php">S'inscrire</a></li>
HTML;
        else
            $auth=<<<HTML
<li><a href="authentification.php">Deconnexion</a></li>
<li><a href="profile.php">Profile</a></li>
HTML;

        $this->appendContent(<<<HTML
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
HTML
        );
    }

    /**
     * Produit le code HTML de la page
     * @return string htmlcode
     */
    public function toHTML() {
        parent::appendContent(<<<HTML
{$this->sidebar}
        <div id="mainframe">
{$this->mainframe}
        </div>
        <div id="layer"></div>
HTML
        );

        return parent::toHTML();
    }
}