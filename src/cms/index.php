<?php
error_reporting(E_STRICT | E_ALL);

set_include_path("./lib/sblayout:./lib/sbdata:./lib/sbcrud:./lib/sbgallery:./lib/auth:./lib/sbeditor:./lib/sbpagemanager:./includes");

require_once("config.inc.php");

require_once("layout/model/Application.class.php");
require_once("layout/model/section/StaticSection.class.php");
require_once("layout/model/section/MenuSection.class.php");
require_once("layout/model/section/ContentsSection.class.php");
require_once("layout/model/page/StaticContentPage.class.php");
require_once("layout/model/page/PageAlias.class.php");
require_once("layout/model/page/HiddenStaticContentPage.class.php");
require_once("layout/model/page/DynamicContentPage.class.php");

require_once("model/page/MyGalleryPage.class.php");
require_once("auth/model/page/AuthorizationPage.class.php");
require_once("auth/model/AuthorizationManager.class.php");
require_once("pagemanager/model/page/PageManager.class.php");
require_once("model/MyPagePermissionChecker.class.php");

require_once("layout/view/html/index.inc.php");
require_once("layout/view/html/baseurl.inc.php");

setBaseURL();

$usersDbh = new PDO($config["usersDbDsn"], $config["usersDbUsername"], $config["usersDbPassword"], array(
	PDO::ATTR_PERSISTENT => true
));

$authorizationManager = new AuthorizationManager($usersDbh, "cms");
$authorizationManager->checkCredentialsIfLoggedIn();

$checker = new MyPagePermissionChecker($authorizationManager);

$dbh = new PDO($config["dbDsn"], $config["dbUsername"], $config["dbPassword"], array(
	PDO::ATTR_PERSISTENT => true
));

$application = new Application(
	/* Title */
	"Simple Content Management System",

	/* CSS stylesheets */
	array(
		$GLOBALS["baseURL"]."/lib/layout/styles/default.css",
		$GLOBALS["baseURL"]."/lib/layout/styles/submenu.css",
		$GLOBALS["baseURL"]."/lib/layout/styles/gallery.css",
		"cms.css"
	),

	/* Sections */
	array(
		"header" => new StaticSection("header.inc.php"),
		"menu" => new StaticSection("menu.inc.php"),
		"submenu" => new StaticSection("submenu.inc.php"),
		"contents" => new ContentsSection(true)
	),

	/* Pages */
	new PageManager($dbh, 2, $checker, array(
		"403" => new HiddenStaticContentPage("Forbidden", new Contents("error/403.inc.php")),
		"404" => new HiddenStaticContentPage("Page not found", new Contents("error/404.inc.php")),
		"auth" => new AuthorizationPage($authorizationManager, "Login", "Login status"),
		"gallery" => new MyGalleryPage($authorizationManager, $dbh)
	))
);

displayRequestedPage($application);
?>
