<?php
namespace SBExampleApps\Literature\Model\CRUD;
use PDO;
use SBLayout\Model\Route;
use SBLayout\Model\PageForbiddenException;
use SBData\Model\Form;
use SBData\Model\Field\ComboBoxField\DBComboBoxField;
use SBData\Model\Field\HiddenField;
use SBData\Model\Field\ReadOnlyNumericIntTextField;
use SBData\Model\Field\TextField;
use SBData\Model\Field\URLField;
use SBCrud\Model\CRUDForm;
use SBCrud\Model\CRUD\CRUDInterface;
use SBCrud\Model\Page\CRUDPage;
use SBExampleApps\Auth\Model\AuthorizationManager;
use SBExampleApps\Literature\Model\Entity\ConferenceEntity;

class ConferenceCRUDInterface extends CRUDInterface
{
	public CRUDPage $crudPage;

	public PDO $dbh;

	public CRUDForm $form;

	public AuthorizationManager $authorizationManager;

	public function __construct(Route $route, CRUDPage $crudPage, PDO $dbh, AuthorizationManager $authorizationManager)
	{
		parent::__construct($crudPage);
		$this->route = $route;
		$this->crudPage = $crudPage;
		$this->dbh = $dbh;
		$this->authorizationManager = $authorizationManager;
	}

	private function constructConferenceForm(): void
	{
		$this->form = new CRUDForm(array(
    			"CONFERENCE_ID" => new ReadOnlyNumericIntTextField("Id", false),
			"Name" => new TextField("Name", true, 20, 255),
			"Homepage" => new URLField("Homepage", false),
			"PUBLISHER_ID" => new DBComboBoxField("Publisher", $this->dbh, "SBExampleApps\\Literature\\Model\\Entity\\PublisherEntity::querySummary", "SBExampleApps\\Literature\\Model\\Entity\\PublisherEntity::queryOneSummary", true),
			"Location" => new TextField("Location", true, 20, 255)
		));
	}

	private function createConference(): void
	{
		$this->constructConferenceForm();
		$this->form->setOperation("insert_conference");
	}

	private function insertConference(): void
	{
		$this->constructConferenceForm();
		$this->form->importValues($_REQUEST);
		$this->form->checkFields();

		if($this->form->checkValid())
		{
			$conference = $this->form->exportValues();
			$conferenceId = ConferenceEntity::insert($this->dbh, $conference);
			header("Location: ".$_SERVER["PHP_SELF"]."/".rawurlencode($conferenceId));
			exit();
		}
	}

	private function viewConferenceProperties(): void
	{
		/* Query the properties of the requested conference and construct a form from it */
		$this->constructConferenceForm();
		$this->form->importValues($this->crudPage->entity);
		$this->form->setOperation("update_conference");
	}

	private function viewConference(): void
	{
		$this->viewConferenceProperties();
	}

	private function updateConference(): void
	{
		$this->constructConferenceForm();
		$this->form->importValues($_REQUEST);
		$this->form->checkFields();

		if($this->form->checkValid())
		{
			$conference = $this->form->exportValues();
			ConferenceEntity::update($this->dbh, $conference, $GLOBALS["query"]["conferenceId"]);
			header("Location: ".$this->route->composeParentPageURL($_SERVER["SCRIPT_NAME"])."/".rawurlencode($conference['CONFERENCE_ID']));
			exit();
		}
	}

	private function deleteConference(): void
	{
		ConferenceEntity::remove($this->dbh, $GLOBALS["query"]["conferenceId"]);
		header("Location: ".$this->route->composeParentPageURL($_SERVER["SCRIPT_NAME"]).AnchorRow::composePreviousRowFragment());
		exit();
	}

	public function executeCRUDOperation(?string $operation): void
	{
		if($operation === null)
			$this->viewConference();
		else
		{
			if($this->authorizationManager->authenticated) // Write operations are only allowed when authenticated
			{
				switch($operation)
				{
					case "create_conference":
						$this->createConference();
						break;
					case "insert_conference":
						$this->insertConference();
						break;
					case "update_conference":
						$this->updateConference();
						break;
					case "delete_conference":
						$this->deleteConference();
						break;
				}
			}
			else
				throw new PageForbiddenException("No permissions to modify a conference!");
		}
	}
}
?>
