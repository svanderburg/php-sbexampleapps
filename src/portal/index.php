<?php
error_reporting(E_STRICT | E_ALL);

set_include_path("./lib/sblayout:./lib/sbdata:./lib/sbcrud:./lib/sbeditor:./lib/sbgallery:./lib/auth:./includes");

require_once("config.inc.php");

require_once("layout/model/Application.class.php");
require_once("layout/model/section/StaticSection.class.php");
require_once("layout/model/section/MenuSection.class.php");
require_once("layout/model/section/ContentsSection.class.php");
require_once("layout/model/page/StaticContentPage.class.php");
require_once("layout/model/page/PageAlias.class.php");
require_once("layout/model/page/HiddenStaticContentPage.class.php");

require_once("auth/model/page/AuthorizationPage.class.php");
require_once("auth/model/AuthorizationManager.class.php");

require_once("model/page/NewsCRUDPage.class.php");
require_once("model/page/NewsMessageCRUDPage.class.php");
require_once("model/page/ChangeLogCRUDPage.class.php");
require_once("model/page/MyGalleryPage.class.php");

require_once("layout/view/html/index.inc.php");
require_once("layout/view/html/baseurl.inc.php");

setBaseURL();

$usersDbh = new PDO($config["usersDbDsn"], $config["usersDbUsername"], $config["usersDbPassword"], array(
	PDO::ATTR_PERSISTENT => true
));

$authorizationManager = new AuthorizationManager($usersDbh, "portal");
$authorizationManager->checkCredentialsIfLoggedIn();

$dbh = new PDO($config["dbDsn"], $config["dbUsername"], $config["dbPassword"], array(
	PDO::ATTR_PERSISTENT => true
));

$application = new Application(
	/* Title */
	"Portal",

	/* CSS stylesheets */
	array(
		$GLOBALS["baseURL"]."/lib/layout/styles/default.css",
		$GLOBALS["baseURL"]."/lib/layout/styles/submenu.css",
		$GLOBALS["baseURL"]."/lib/layout/styles/gallery.css"
	),

	/* Sections */
	array(
		"header" => new StaticSection("header.inc.php"),
		"menu" => new MenuSection(0),
		"submenu" => new MenuSection(1),
		"contents" => new ContentsSection(true)
	),

	/* Pages */
	new PageAlias("Home", "home", array(
		"403" => new HiddenStaticContentPage("Forbidden", new Contents("error/403.inc.php")),
		"404" => new HiddenStaticContentPage("Page not found", new Contents("error/404.inc.php")),

		"home" => new StaticContentPage("Home", new Contents("home.inc.php")),
		"news" => new NewsCRUDPage($dbh, $authorizationManager, new NewsMessageCRUDPage($dbh, $authorizationManager)),
		"changelog" => new ChangeLogCRUDPage($dbh, $authorizationManager),
		"gallery" => new MyGalleryPage($authorizationManager, $dbh),
		"tests" => new StaticContentPage("Tests", new Contents("tests.inc.php"), array(
			"layouttests" => new StaticContentPage("Layout tests", new Contents("tests/layouttests.inc.php"))
		)),
		"features" => new StaticContentPage("Features", new Contents("features.inc.php"), $config["externalApps"]),
		"auth" => new AuthorizationPage($authorizationManager, "Login", "Login status")
	))
);

displayRequestedPage($application);
?>
