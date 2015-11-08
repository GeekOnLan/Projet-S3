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

        //$connected = Member::isConnected();
        $connected = false;

        $this->appendBasicCSSAndJS();
        $this->insertConnexionForm();
        $this->insertGeekOnLanHeader($connected);
        $this->mainframe .= ($connected) ? "<button id='sidebarButton' type='button'></button>" : "";

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
        $this->appendJsUrl("js/cryptageAuthentification.js");
        $this->appendJsUrl("http://crypto-js.googlecode.com/svn/tags/3.1.2/build/rollups/sha256.js");
        $this->appendJsUrl("http://crypto-js.googlecode.com/svn/tags/3.1.2/build/rollups/sha1.js");
    }

    private function insertConnexionForm() {
        $salt = Member::Challenge();

        $this->sidebar .= <<<HTML
        <form id="connexionForm" name="connexion" action="authentification.php" method="post">
			<table>
				<tr>
					<td>Identifiant :</td>
					<td><input type="text" name="login" onfocus="resetInput('login')"></td>
				</tr>
				<tr>
					<td>Mot de Passe :</td>
					<td><input type="password" name="pass" onfocus="resetInput('pass')"></td>
				</tr>
				<tr>
					<td><input type="text" name="hidden" style="display:none" value="{$salt}"></td>
				</tr>
				<tr>
					<td colspan='2'><button type="button" value="submit" onclick="sha256()">Confirmer</button></td>
				</tr>
			</table>
		</form>
HTML;
    }

    /**
     * Ajoute la barre mode connecté de GeekOnLan
     */
    private function insertGeekOnLanSidebar() {
        $this->sidebar = <<<HTML
<nav id="sidebar">
    <ul>
        <li><a href="#">Profil</a></li>
        <li><a href="#">Participations</a></li>
        <li><a href="#">Cree une LAN</a></li>
        <li><a href="#">Mes LAN</a></li>
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