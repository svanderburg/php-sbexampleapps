<?php
namespace SBExampleApps\Portal\Model\CRUD;
use Exception;
use PDO;
use PDOStatement;
use SBData\Model\Value\PageValue;
use SBCrud\Model\CRUDModel;
use SBCrud\Model\CRUDPage;
use SBExampleApps\Portal\Model\Entity\NewsMessageEntity;

class NewsCRUDModel extends CRUDModel
{
	public PDO $dbh;

	public PDOStatement $stmt;

	public int $page;

	public function __construct(CRUDPage $crudPage, PDO $dbh)
	{
		parent::__construct($crudPage);
		$this->dbh = $dbh;
	}

	public function executeOperation(): void
	{
		$pageValue = new PageValue();

		if(array_key_exists("page", $_GET))
		{
			$pageValue->value = $_GET["page"];
			if(!$pageValue->checkValue("Page"))
				throw new Exception("Invalid page value provided!");
		}

		$this->page = (int)$pageValue->value;
		$this->stmt = NewsMessageEntity::queryAll($this->dbh, $this->page);
	}
}
?>
