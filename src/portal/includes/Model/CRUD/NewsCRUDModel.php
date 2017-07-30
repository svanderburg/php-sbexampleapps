<?php
namespace SBExampleApps\Portal\Model\CRUD;
use Exception;
use PDO;
use SBData\Model\Field\NumericIntTextField;
use SBCrud\Model\CRUDModel;
use SBCrud\Model\CRUDPage;
use SBExampleApps\Portal\Model\Entity\NewsMessageEntity;

class NewsCRUDModel extends CRUDModel
{
	public $dbh;

	public $stmt;

	public $page;

	public function __construct(CRUDPage $crudPage, PDO $dbh)
	{
		parent::__construct($crudPage);
		$this->dbh = $dbh;
	}

	public function executeOperation()
	{
		$pageField = new NumericIntTextField("Page", true);

		if(array_key_exists("page", $_GET))
		{
			$pageField->value = $_GET["page"];
			if(!$pageField->checkField("Page"))
				throw new Exception("Invalid page value provided!");
		}
		else
			$pageField->value = 0;

		$this->page = $pageField->value;
		$this->stmt = NewsMessageEntity::queryAll($this->dbh, $this->page);
	}
}
?>
