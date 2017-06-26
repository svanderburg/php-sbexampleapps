<?php
error_reporting(E_STRICT | E_ALL);

set_include_path("./lib/sblayout:./lib/sbdata:./lib/sbcrud:./lib/sbbiblio:./lib/auth:./includes");

require_once("config.inc.php");

require_once("layout/model/Application.class.php");
require_once("layout/model/section/StaticSection.class.php");
require_once("layout/model/section/MenuSection.class.php");
require_once("layout/model/section/ContentsSection.class.php");
require_once("layout/model/page/StaticContentPage.class.php");
require_once("layout/model/page/PageAlias.class.php");
require_once("layout/model/page/HiddenStaticContentPage.class.php");
require_once("layout/model/page/DynamicContentPage.class.php");

require_once("model/page/AuthorsCRUDPage.class.php");
require_once("model/page/AuthorCRUDPage.class.php");
require_once("model/page/PublishersCRUDPage.class.php");
require_once("model/page/PublisherCRUDPage.class.php");
require_once("model/page/ConferencesCRUDPage.class.php");
require_once("model/page/ConferenceCRUDPage.class.php");
require_once("model/page/PaperCRUDPage.class.php");
require_once("model/page/SearchCRUDPage.class.php");
require_once("auth/model/page/AuthorizationPage.class.php");

require_once("auth/model/AuthorizationManager.class.php");

require_once("layout/view/html/index.inc.php");
require_once("layout/view/html/baseurl.inc.php");

setBaseURL();

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
	array($GLOBALS["baseURL"]."/lib/layout/styles/default.css"),

	/* Sections */
	array(
		"header" => new StaticSection("header.inc.php"),
		"menu" => new MenuSection(0),
		"contents" => new ContentsSection(true)
	),

	/* Pages */
	new PageAlias("Home", "home", array(
		"403" => new HiddenStaticContentPage("Forbidden", new Contents("error/403.inc.php")),
		"404" => new HiddenStaticContentPage("Page not found", new Contents("error/404.inc.php")),

		"home" => new StaticContentPage("Home", new Contents("home.inc.php")),
		"authors" => new AuthorsCRUDPage($dbh, $authorizationManager, new AuthorCRUDPage($dbh, $authorizationManager)),
		"publishers" => new PublishersCRUDPage($dbh, $authorizationManager, new PublisherCRUDPage($dbh, $authorizationManager)),
		"conferences" => new ConferencesCRUDPage($dbh, $authorizationManager, new ConferenceCRUDPage($dbh, $authorizationManager, array(
			"papers" => new DynamicContentPage("Papers", "paperId", new Contents("conferences/papers.inc.php"), new PaperCRUDPage($dbh, $authorizationManager, array(
				"reference" => new HiddenStaticContentPage("Reference", new Contents("conferences/papers/reference.inc.php", null, null))
			)))
		))),
		"search" => new SearchCRUDPage($dbh),

		"auth" => new AuthorizationPage($authorizationManager, "Login", "Login status")
	))
);

displayRequestedPage($application);
?>
