<?php
error_reporting(E_STRICT | E_ALL);

require_once("vendor/autoload.php");

use SBLayout\Model\Application;
use SBLayout\Model\Page\ExternalPage;
use SBLayout\Model\Page\HiddenStaticContentPage;
use SBLayout\Model\Page\PageAlias;
use SBLayout\Model\Page\StaticContentPage;
use SBLayout\Model\Page\Content\Contents;
use SBLayout\Model\Section\ContentsSection;
use SBLayout\Model\Section\MenuSection;
use SBLayout\Model\Section\StaticSection;
use SBLayout\Model\Section\CompoundSection;
use SBExampleApps\Auth\Model\AuthorizationManager;
use SBExampleApps\Auth\Model\Page\AuthorizationPage;
use SBExampleApps\Portal\Model\Page\NewsCRUDPage;
use SBExampleApps\Portal\Model\Page\NewsMessageCRUDPage;
use SBExampleApps\Portal\Model\Page\ChangeLogCRUDPage;
use SBExampleApps\Portal\Model\Page\MyGalleryPage;

require_once("includes/config.php");

\SBLayout\View\HTML\setBaseURL();

$usersDbh = new PDO($config["usersDbDsn"], $config["usersDbUsername"], $config["usersDbPassword"], array(
	//PDO::ATTR_PERSISTENT => true
));

$authorizationManager = new AuthorizationManager($usersDbh, "portal");
$authorizationManager->checkCredentialsIfLoggedIn();

$dbh = new PDO($config["dbDsn"], $config["dbUsername"], $config["dbPassword"], array(
	//PDO::ATTR_PERSISTENT => true
));

$application = new Application(
	/* Title */
	"Portal",

	/* CSS stylesheets */
	array("default.css", "submenu.css"),

	/* Sections */
	array(
		"header" => new StaticSection("header.php"),
		"menu" => new MenuSection(0),
		"container" => new CompoundSection(array(
			"submenu" => new MenuSection(1),
			"contents" => new ContentsSection(true)
		))
	),

	/* Pages */
	new PageAlias("Home", "home", array(
		"403" => new HiddenStaticContentPage("Forbidden", new Contents("error/403.php")),
		"404" => new HiddenStaticContentPage("Page not found", new Contents("error/404.php")),

		"home" => new StaticContentPage("Home", new Contents("home.php")),
		"news" => new NewsCRUDPage($dbh, $authorizationManager, new NewsMessageCRUDPage($dbh, $authorizationManager)),
		"changelog" => new ChangeLogCRUDPage($dbh, $authorizationManager),
		"gallery" => new MyGalleryPage($authorizationManager, $dbh),
		"tests" => new StaticContentPage("Tests", new Contents("tests.php"), array(
			"layouttests" => new StaticContentPage("Layout tests", new Contents("tests/layouttests.php"))
		)),
		"features" => new StaticContentPage("Features", new Contents("features.php"), $config["externalApps"]),
		"auth" => new AuthorizationPage($authorizationManager, "Login", "Login status")
	))
);

\SBLayout\View\HTML\displayRequestedPage($application);
?>
