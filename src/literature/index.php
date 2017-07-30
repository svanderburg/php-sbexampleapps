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
use SBExampleApps\Literature\Model\Page\AuthorsCRUDPage;
use SBExampleApps\Literature\Model\Page\AuthorCRUDPage;
use SBExampleApps\Literature\Model\Page\ConferencesCRUDPage;
use SBExampleApps\Literature\Model\Page\ConferenceCRUDPage;
use SBExampleApps\Literature\Model\Page\PaperCRUDPage;
use SBExampleApps\Literature\Model\Page\PublishersCRUDPage;
use SBExampleApps\Literature\Model\Page\PublisherCRUDPage;
use SBExampleApps\Literature\Model\Page\SearchCRUDPage;

require_once("includes/config.php");

\SBLayout\View\HTML\setBaseURL();

$usersDbh = new PDO($config["usersDbDsn"], $config["usersDbUsername"], $config["usersDbPassword"], array(
	PDO::ATTR_PERSISTENT => true
));

$authorizationManager = new AuthorizationManager($usersDbh, "literature");
$authorizationManager->checkCredentialsIfLoggedIn();

$dbh = new PDO($config["dbDsn"], $config["dbUsername"], $config["dbPassword"], array(
	PDO::ATTR_PERSISTENT => true
));

$application = new Application(
	/* Title */
	"Literature",

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
		"authors" => new AuthorsCRUDPage($dbh, $authorizationManager, new AuthorCRUDPage($dbh, $authorizationManager)),
		"publishers" => new PublishersCRUDPage($dbh, $authorizationManager, new PublisherCRUDPage($dbh, $authorizationManager)),
		"conferences" => new ConferencesCRUDPage($dbh, $authorizationManager, new ConferenceCRUDPage($dbh, $authorizationManager, array(
			"papers" => new DynamicContentPage("Papers", "paperId", new Contents("conferences/papers.php"), new PaperCRUDPage($dbh, $authorizationManager, array(
				"reference" => new HiddenStaticContentPage("Reference", new Contents("conferences/papers/reference.php", null, null))
			)))
		))),
		"search" => new SearchCRUDPage($dbh),

		"auth" => new AuthorizationPage($authorizationManager, "Login", "Login status")
	))
);

\SBLayout\View\HTML\displayRequestedPage($application);
?>
