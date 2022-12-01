<?php
error_reporting(E_STRICT | E_ALL);

require_once("vendor/autoload.php");

use SBLayout\Model\Application;
use SBLayout\Model\Page\DynamicContentPage;
use SBLayout\Model\Page\HiddenStaticContentPage;
use SBLayout\Model\Page\PageAlias;
use SBLayout\Model\Page\StaticContentPage;
use SBLayout\Model\Page\Content\Contents;
use SBLayout\Model\Section\ContentsSection;
use SBLayout\Model\Section\MenuSection;
use SBLayout\Model\Section\StaticSection;
use SBExampleApps\Auth\Model\AuthorizationManager;
use SBExampleApps\Auth\Model\Page\AuthorizationPage;
use SBExampleApps\Homework\Model\Page\TestsCRUDPage;
use SBExampleApps\Homework\Model\Page\ExamsCRUDPage;

require_once("includes/config.php");

\SBLayout\View\HTML\setBaseURL();

$usersDbh = new PDO($config["usersDbDsn"], $config["usersDbUsername"], $config["usersDbPassword"], array(
	//PDO::ATTR_PERSISTENT => true
));

$authorizationManager = new AuthorizationManager($usersDbh, "homework");
$authorizationManager->checkCredentialsIfLoggedIn();

$dbh = new PDO($config["dbDsn"], $config["dbUsername"], $config["dbPassword"], array(
	//PDO::ATTR_PERSISTENT => true
));

$application = new Application(
	/* Title */
	"Homework assistant",

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
		"400" => new HiddenStaticContentPage("Bad request", new Contents("error/400.php")),
		"403" => new HiddenStaticContentPage("Forbidden", new Contents("error/403.php")),
		"404" => new HiddenStaticContentPage("Page not found", new Contents("error/404.php")),

		"home" => new StaticContentPage("Home", new Contents("home.php")),
		"tests" => new TestsCRUDPage($dbh, $authorizationManager),
		"exams" => new ExamsCRUDPage(),
		"auth" => new AuthorizationPage($authorizationManager, "Login", "Login status")
	))
);

\SBLayout\View\HTML\displayRequestedPage($application);
?>
