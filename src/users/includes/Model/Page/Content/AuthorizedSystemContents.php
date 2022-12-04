<?php
namespace SBExampleApps\Users\Model\Page\Content;
use SBLayout\Model\Page\Content\Contents;

class AuthorizedSystemContents extends Contents
{
	public function __construct()
	{
		parent::__construct("users/user/authorizedsystems.php", "users/user/authorizedsystems.php");
	}
}
?>
