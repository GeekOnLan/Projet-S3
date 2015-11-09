<?php

class Webpage {

    /**
     * @var string Texte compris entre <head> et </head>
     */
    protected $head  = null ;

    /**
     * @var string Texte compris entre <title> et </title>
     */
    protected $title = null ;

    /**
     * @var string Texte compris entre <body> et </body>
     */
    protected $body  = null ;

    /**
     * Constructeur
     * @param string $title Le titre de la page
     */
    public function __construct($title="GeekOnLan") {
        $this->title= $title;
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
        $this->body .= $content;
    }

    /**
     * Produire la page Web complète
     * @return string htmlcode
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
{$this->body}
    </body>
</html>
HTML;
    }

}