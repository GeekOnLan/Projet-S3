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
     * @var string texte au-dessus de tout
     */
    private $foreground = null;

    /**
     * Construit une page Web sur le modèle de GeekOnLan
     * @param string $title le titre de la page
     */
    public function __construct($title) {
        parent::__construct($title);

        $connected = Member::isConnected();

        $this->appendBasicCSSAndJS();
        $this->appendToHead("<link rel='icon' type='image/png' href='resources/img/icon.png' />");
        $this->insertGeekOnLanHeader($connected);
        $this->mainframe .= ($connected) ? "<button id='sidebarButton' type='button'></button>" : "";

        if($connected)
            $this->insertGeekOnLanSidebar(Member::getInstance());
        else
            $this->insertConnexionForm();
    }

    /**
     * Ajoute un contenu à la fenêtre principale
     * @param string $content
     */
    public function appendContent($content) {
        $this->mainframe .= $content;
    }

    /**
     * Ajoute un contenu au premier plan
     * @param $content
     */
    public function appendForeground($content) {
        $this->foreground .= $content;
    }

    /**
     * Ajoute le css et le javascript de base
     */
    private function appendBasicCSSAndJS(){
        $this->appendCssUrl("style/regular/base.css", "screen and (min-width: 680px");
        $this->appendCssUrl("style/mobile/base.css", "screen and (max-width: 680px)");
        $this->appendCssUrl("style/regular/parallax.css");
        $this->appendJsUrl("http://code.jquery.com/jquery-2.1.4.min.js");
        $this->appendJsUrl("js/base.js");
        $this->appendJsUrl("js/parallax.js");
        if(!Member::isConnected()) {
            $this->appendJsUrl("js/authentification.js");
            $this->appendJsUrl("http://crypto-js.googlecode.com/svn/tags/3.1.2/build/rollups/sha256.js");
            $this->appendJsUrl("http://crypto-js.googlecode.com/svn/tags/3.1.2/build/rollups/sha1.js");
        }
    }

    private function insertConnexionForm() {
        $challenge = Member::challenge();
        $this->foreground .= <<<HTML
        <form id="connexionForm" name="connexion" action="authentification.php" method="post">
            <h2>Connexion</h2>
            <a href="#">x</a>
            <table>
                <tr>
                    <td class="connexionIcon"><img src="resources/img/Contact.png" alt="login" /></td>
                    <td><input id="login" type="text" name="login" onfocus="resetInputAuth('login')" placeholder="Pseudo"></td>
                </tr>
                <tr>
                    <td class="connexionIcon"><img src="resources/img/Lock.png" alt="password" /></td>
                    <td><input id="pass" type="password" name="pass" onfocus="resetInputAuth('pass')" placeholder="Mot de passe"></td>
                </tr>
            </table>

            <input type="hidden" name="hiddenCrypt" value={$challenge}>

            <div>
                <a href="inscription.php">S'inscrire</a>
                <button type="button" onclick="sha256()">Confirmer</button>
            </div>
		</form>
HTML;
    }

    /**
     * Ajoute la barre mode connecté de GeekOnLan
     */
    private function insertGeekOnLanSidebar(Member $m) {
    	$nbNotif=$m->getNbNotif();
    	if($nbNotif==0)
    		$nbNotif="";
        else{
            $nbNotif = '<div id="notifNumber">'.$nbNotif.'</div>';
        }
        $this->sidebar = <<<HTML
<nav id="sidebar">
    <h2>{$m->getPseudo()}</h2>
    <ul>
        <li><a href="profil.php">Profil</a></li>
        <li><a href="notification.php">Mes notifications {$nbNotif}</a></li>
        <li><a href="participer.php">Participations</a></li>
        <li><a href="creeLan.php">Créer une LAN</a></li>
        <li><a href="listeLansMembre.php">Mes LAN</a></li>
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
<li><a href="#" id="connexionButton">Connexion</a></li>
<li><a href="inscription.php">S'inscrire</a></li>
HTML;
        else
            $auth=<<<HTML
<li><a href="authentification.php">Deconnexion</a></li>
<li><a href="profil.php">Profil</a></li>
HTML;

        $this->appendContent(<<<HTML
        <header>
            <hr/>
            <img alt="GeekOnLanLogo" src="resources/img/logo.png" />
        </header>
        <nav id="menu">
            <ul>
                <li><a href="index.php">Accueil</a></li>
                <li><a href="listeLans.php">LAN</a></li>
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
        <div id="foreground">
{$this->foreground}
        </div>
        <div id="mainframe">
            <article>
            {$this->mainframe}
            </article>
            <div id="parallax"></div>
        </div>
        <div id="layer"></div>
HTML
        );

        return parent::toHTML();
    }
}
