<?php
require_once("layout/model/page/ExternalPage.class.php");

$config = array(
	/* Connection settings for the user database */
	"usersDbDsn" => "mysql:host=localhost;dbname=users",
	"usersDbUsername" => "root",
	"usersDbPassword" => "admin",

	/* Connection settings for the application database */
	"dbDsn" => "mysql:host=localhost;dbname=portal",
	"dbUsername" => "root",
	"dbPassword" => "admin",

	/* External application configuration */
	"externalApps" => array(
		"homework" => new ExternalPage("Homework", "http://localhost/homework"),
		"literature" => new ExternalPage("Literature", "http://localhost/literature"),
		"users" => new ExternalPage("Users", "http://localhost/users"),
		"cms" => new ExternalPage("CMS", "http://localhost/cms"),
		"cmsgallery" => new ExternalPage("CMS gallery", "http://localhost/cmsgallery")
	)
);
?>
