<?php
error_reporting(E_STRICT | E_ALL);

set_include_path("./lib/sblayout:./lib/sbdata:./lib/sbcrud:./lib/auth:./includes");

require_once("config.inc.php");

require_once("layout/model/Application.class.php");
require_once("layout/model/section/StaticSection.class.php");
require_once("layout/model/section/MenuSection.class.php");
require_once("layout/model/section/ContentsSection.class.php");
require_once("layout/model/page/StaticContentPage.class.php");
require_once("layout/model/page/PageAlias.class.php");
require_once("layout/model/page/HiddenStaticContentPage.class.php");
require_once("layout/model/page/DynamicContentPage.class.php");

require_once("model/page/UsersCRUDPage.class.php");
require_once("model/page/UserCRUDPage.class.php");
require_once("model/page/SystemsCRUDPage.class.php");
require_once("model/page/SystemCRUDPage.class.php");
require_once("auth/model/page/AuthorizationPage.class.php");

require_once("auth/model/AuthorizationManager.class.php");

require_once("layout/view/html/index.inc.php");
require_once("layout/view/html/baseurl.inc.php");

setBaseURL();

$dbh = new PDO($config["dbDsn"], $config["dbUsername"], $config["dbPassword"], array(
	PDO::ATTR_PERSISTENT => true
));

$authorizationManager = new AuthorizationManager($dbh, "users");
$authorizationManager->checkCredentialsIfLoggedIn();

$application = new Application(
	/* Title */
	"User management",

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
		"users" => new UsersCRUDPage($dbh, $authorizationManager, new UserCRUDPage($dbh, $authorizationManager)),
		"systems" => new SystemsCRUDPage($dbh, $authorizationManager, new SystemCRUDPage($dbh, $authorizationManager)),
		"auth" => new AuthorizationPage($authorizationManager, "Login", "Login status")
	))
);

displayRequestedPage($application);
?>
