<?php
error_reporting(E_STRICT | E_ALL);

require_once("vendor/autoload.php");

use SBLayout\Model\Application;
use SBLayout\Model\Page\HiddenStaticContentPage;
use SBLayout\Model\Page\StaticContentPage;
use SBLayout\Model\Page\Content\Contents;
use SBLayout\Model\Section\ContentsSection;
use SBLayout\Model\Section\StaticSection;
use SBLayout\Model\Section\CompoundSection;
use SBLayout\Model\Section\MenuSection;
use SBPageManager\Model\Page\PageManager;
use SBExampleApps\Auth\Model\AuthorizationManager;
use SBExampleApps\Auth\Model\Page\AuthorizationPage;
use SBExampleApps\CMS\Model\MyPagePermissionChecker;
use SBExampleApps\CMS\Model\Page\MyGalleryPage;

require_once("includes/config.php");

\SBLayout\View\HTML\setBaseURL();

$usersDbh = new PDO($config["usersDbDsn"], $config["usersDbUsername"], $config["usersDbPassword"], array(
	//PDO::ATTR_PERSISTENT => true
));

$authorizationManager = new AuthorizationManager($usersDbh, "cms");
$authorizationManager->checkCredentialsIfLoggedIn();

$checker = new MyPagePermissionChecker($authorizationManager);

$dbh = new PDO($config["dbDsn"], $config["dbUsername"], $config["dbPassword"], array(
	//PDO::ATTR_PERSISTENT => true
));

$application = new Application(
	/* Title */
	"Simple Content Management System",

	/* CSS stylesheets */
	array("default.css", "submenu.css", "cms.css"),

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
	new PageManager($dbh, 2, $checker, array(
		"400" => new HiddenStaticContentPage("Bad request", new Contents("error/400.php")),
		"403" => new HiddenStaticContentPage("Forbidden", new Contents("error/403.php")),
		"404" => new HiddenStaticContentPage("Page not found", new Contents("error/404.php")),
		"auth" => new AuthorizationPage($authorizationManager, "Login", "Login status"),
		"gallery" => new MyGalleryPage($authorizationManager, $dbh)
	))
);

\SBLayout\View\HTML\displayRequestedPage($application);
?>
