<?php
namespace SBExampleApps\Homework\Model\CRUD;
use Exception;
use PDO;
use SBLayout\Model\Route;
use SBData\Model\Form;
use SBData\Model\Field\CheckBoxField;
use SBData\Model\Field\HiddenField;
use SBData\Model\Field\TextField;
use SBCrud\Model\CRUDForm;
use SBCrud\Model\CRUD\CRUDInterface;
use SBCrud\Model\Page\CRUDPage;
use SBExampleApps\Homework\Model\Entity\TestEntity;

class TestCRUDInterface extends CRUDInterface
{
	public Route $route;

	public CRUDPage $crudPage;

	public PDO $dbh;

	public CRUDForm $form;

	public function __construct(Route $route, CRUDPage $crudPage, PDO $dbh)
	{
		parent::__construct($crudPage);
		$this->route = $route;
		$this->crudPage = $crudPage;
		$this->dbh = $dbh;
	}

	private function constructTestForm(): void
	{
		$this->form = new CRUDForm(array(
			"__operation" => new HiddenField(true),
			"TEST_ID" => new TextField("Id", true, 20, 255),
			"Title" => new TextField("Title", true, 20, 255)
		));
	}

	private function createTest(): void
	{
		$this->constructTestForm();
		$this->form->setOperation("insert_test");
	}

	private function insertTest(): void
	{
		$this->constructTestForm();
		$this->form->importValues($_REQUEST);
		$this->form->checkFields();

		if($this->form->checkValid())
		{
			$test = $this->form->exportValues();
			TestEntity::insert($this->dbh, $test);
			header("Location: ".$_SERVER["PHP_SELF"]."/".rawurlencode($test["TEST_ID"]));
			exit();
		}
	}

	private function viewTest(): void
	{
		/* Query the properties of the requested test and construct a form and table from it */
		$this->constructTestForm();
		$this->form->importValues($this->crudPage->entity);
		$this->form->setOperation("update_test");
	}

	private function updateTest(): void
	{
		$this->constructTestForm();
		$this->form->importValues($_REQUEST);
		$this->form->checkFields();

		if($this->form->checkValid())
		{
			$testId = $GLOBALS["query"]["testId"];
			$test = $this->form->exportValues();
			TestEntity::update($this->dbh, $test, $testId);
			header("Location: ".$this->route->composeParentPageURL($_SERVER["SCRIPT_NAME"])."/".rawurlencode($test['TEST_ID']));
			exit();
		}
	}

	private function deleteTest(): void
	{
		TestEntity::remove($this->dbh, $GLOBALS["query"]["testId"]);
		header("Location: ".$this->route->composeParentPageURL($_SERVER["SCRIPT_NAME"]).AnchorRow::composePreviousRowFragment());
		exit();
	}

	public function executeCRUDOperation(?string $operation): void
	{
		if($operation === null)
			$this->viewTest();
		else
		{
			switch($operation)
			{
				case "create_test":
					$this->createTest();
					break;
				case "insert_test":
					$this->insertTest();
					break;
				case "update_test":
					$this->updateTest();
					break;
				case "delete_test":
					$this->deleteTest();
					break;
			}
		}
	}
}
?>
