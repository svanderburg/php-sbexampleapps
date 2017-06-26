<?php
require_once("crud/model/CRUDModel.class.php");
require_once("model/entities/NewsMessageEntity.class.php");

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
