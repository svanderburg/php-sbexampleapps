<?php
error_reporting(E_STRICT | E_ALL);

require_once("vendor/autoload.php");

use SBLayout\Model\Application;
use SBLayout\Model\Page\HiddenStaticContentPage;
use SBLayout\Model\Page\PageAlias;
use SBLayout\Model\Page\StaticContentPage;
use SBLayout\Model\Page\Content\Contents;
use SBLayout\Model\Section\ContentsSection;
use SBLayout\Model\Section\MenuSection;
use SBLayout\Model\Section\StaticSection;
use SBExampleApps\Auth\Model\AuthorizationManager;
use SBExampleApps\Auth\Model\Page\AuthorizationPage;
use SBExampleApps\Users\Model\Page\UsersCRUDPage;
use SBExampleApps\Users\Model\Page\UserCRUDPage;
use SBExampleApps\Users\Model\Page\SystemsCRUDPage;
use SBExampleApps\Users\Model\Page\SystemCRUDPage;

require_once("includes/config.php");

\SBLayout\View\HTML\setBaseURL();

$dbh = new PDO($config["dbDsn"], $config["dbUsername"], $config["dbPassword"], array(
	PDO::ATTR_PERSISTENT => true
));

$authorizationManager = new AuthorizationManager($dbh, "users");
$authorizationManager->checkCredentialsIfLoggedIn();

$application = new Application(
	/* Title */
	"User management",

	/* CSS stylesheets */
	array("default.css"),

	/* Sections */
	array(
		"header" => new StaticSection("header.php"),
		"menu" => new MenuSection(0),
		"contents" => new ContentsSection(true)
	),

	/* Pages */
	new PageAlias("Home", "home", array(
		"403" => new HiddenStaticContentPage("Forbidden", new Contents("error/403.php")),
		"404" => new HiddenStaticContentPage("Page not found", new Contents("error/404.php")),

		"home" => new StaticContentPage("Home", new Contents("home.php")),
		"users" => new UsersCRUDPage($dbh, $authorizationManager, new UserCRUDPage($dbh, $authorizationManager)),
		"systems" => new SystemsCRUDPage($dbh, $authorizationManager, new SystemCRUDPage($dbh, $authorizationManager)),
		"auth" => new AuthorizationPage($authorizationManager, "Login", "Login status")
	))
);

\SBLayout\View\HTML\displayRequestedPage($application);
?>
