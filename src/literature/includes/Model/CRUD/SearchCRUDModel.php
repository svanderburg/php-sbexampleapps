<?php
namespace SBExampleApps\Literature\Model\CRUD;
use PDO;
use SBData\Model\Form;
use SBData\Model\ReadOnlyForm;
use SBData\Model\Field\DateField;
use SBData\Model\Field\ReadOnlyNaturalNumberkField;
use SBData\Model\Field\MetaDataField;
use SBData\Model\Field\TextField;
use SBData\Model\Field\URLField;
use SBData\Model\Table\DBTable;
use SBCrud\Model\CRUDModel;
use SBCrud\Model\OperationParamPage;
use SBExampleApps\Auth\Model\AuthorizationManager;
use SBExampleApps\Literature\Model\Entity\PaperEntity;

class SearchCRUDModel extends CRUDModel
{
	public PDO $dbh;

	public ?Form $form = null;

	public ?DBTable $table = null;

	public function __construct(OperationParamPage $operationParamPage, PDO $dbh)
	{
		parent::__construct($operationParamPage);
		$this->dbh = $dbh;
	}

	private function constructSearchForm(): void
	{
		$this->form = new Form(array(
			"keyword" => new TextField("Keyword", true, 20, 255),
		));
	}

	private function viewSearchForm(): void
	{
		$this->constructSearchForm();
	}

	private function viewSearchResults(): void
	{
		$this->constructSearchForm();
		$this->form->importValues($_REQUEST);
		$this->form->checkFields();

		if($this->form->checkValid())
		{
			$composePaperLink = function (NaturalNumberKeyLinkField $field, ReadOnlyForm $form): string
			{
				$paperId = $field->exportValue();
				$conferenceId = $form->fields["CONFERENCE_ID"]->exportValue();
				return $_SERVER["SCRIPT_NAME"]."/conferences/".rawurlencode($conferenceId)."/papers/".rawurlencode($paperId);
			};

			$composeConferenceLink = function (NaturalNumberKeyLinkField $field, ReadOnlyForm $form): string
			{
				$conferenceId = $form->fields["CONFERENCE_ID"]->exportValue();
				return $_SERVER["SCRIPT_NAME"]."/conferences/".rawurlencode($conferenceId);
			};

			$composePublisherLink = function (NaturalNumberKeyLinkField $field, ReadOnlyForm $form): string
			{
				$publisherId = $form->fields["PUBLISHER_ID"]->exportValue();
				return $_SERVER["SCRIPT_NAME"]."/publishers/".rawurlencode($publisherId);
			}

			/* Construct a table containing the resulting papers */
			$this->table = new DBTable(array(
				"PAPER_ID" => new NaturalNumberKeyLinkField("Id", $composePaperLink, true),
				"Title" => new TextField("Title", true, 20, 255),
				"Date" => new DateField("Date", true),
				"URL" => new URLField("URL", false),
				"PUBLISHER_ID" => new MetaDataField(true, 255),
				"PublisherName" => new NaturalNumberKeyLinkField("Publisher", $composePublisherLink, true),
				"CONFERENCE_ID" => new MetaDataField(true, 255),
				"ConferenceName" => new NaturalNumberKeyLinkField("Conference", $composeConferenceLink, true)
			));

			$this->table->setStatement(PaperEntity::searchByKeyword($this->dbh, $this->form->fields["keyword"]->exportValue()));
		}
	}

	public function executeOperation(): void
	{
		if(array_key_exists("keyword", $_REQUEST))
			$this->viewSearchResults();
		else
			$this->viewSearchForm();
	}
}
?>
