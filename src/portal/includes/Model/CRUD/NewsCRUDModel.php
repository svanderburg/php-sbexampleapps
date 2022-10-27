<?php
namespace SBExampleApps\Portal\Model\CRUD;
use Exception;
use PDO;
use PDOStatement;
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
		$this->page = (int)$this->requestParameterMap->values["page"]->value;
		$this->stmt = NewsMessageEntity::queryAll($this->dbh, $this->page);
	}
}
?>
