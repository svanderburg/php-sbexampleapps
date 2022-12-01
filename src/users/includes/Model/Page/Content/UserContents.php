<?php
namespace SBExampleApps\Users\Model\Page\Content;
use SBLayout\Model\Page\Content\Contents;

class UserContents extends Contents
{
	public function __construct()
	{
		parent::__construct("users/user.php", "users/user.php");
	}
}
?>
