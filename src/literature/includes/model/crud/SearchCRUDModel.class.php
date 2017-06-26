<?php
require_once("data/model/Form.class.php");
require_once("data/model/field/TextField.class.php");
require_once("data/model/field/MetaDataField.class.php");
require_once("crud/model/CRUDModel.class.php");
require_once("model/entities/PaperEntity.class.php");

class SearchCRUDModel extends CRUDModel
{
	public $dbh;

	public $form = null;

	public $table = null;

	public function __construct(CRUDPage $crudPage, PDO $dbh)
	{
		parent::__construct($crudPage);
		$this->dbh = $dbh;
	}

	private function constructSearchForm()
	{
		$this->form = new Form(array(
			"keyword" => new TextField("Keyword", true, 20, 255),
		));
	}

	private function viewSearchForm()
	{
		$this->constructSearchForm();
	}

	private function viewSearchResults()
	{
		$this->constructSearchForm();
		$this->form->importValues($_REQUEST);
		$this->form->checkFields();

		if($this->form->checkValid())
		{
			function composePaperLink(KeyLinkField $field, Form $form)
			{
				return $_SERVER["SCRIPT_NAME"]."/conferences/".$form->fields["CONFERENCE_ID"]->value."/papers/".$field->value;
			}

			function composeConferenceLink(KeyLinkField $field, Form $form)
			{
				return $_SERVER["SCRIPT_NAME"]."/conferences/".$form->fields["CONFERENCE_ID"]->value;
			}

			function composePublisherLink(KeyLinkField $field, Form $form)
			{
				return $_SERVER["SCRIPT_NAME"]."/publishers/".$form->fields["PUBLISHER_ID"]->value;
			}

			/* Construct a table containing the resulting papers */
			$this->table = new DBTable(array(
				"PAPER_ID" => new KeyLinkField("Id", "composePaperLink", true),
				"Title" => new TextField("Title", true, 20, 255),
				"Date" => new DateField("Date", true),
				"URL" => new URLField("URL", false),
				"PUBLISHER_ID" => new MetaDataField(true, 20, 255),
				"PublisherName" => new KeyLinkField("Publisher", "composePublisherLink", true),
				"CONFERENCE_ID" => new MetaDataField(true, 20, 255),
				"ConferenceName" => new KeyLinkField("Conference", "composeConferenceLink", true)
			));

			$this->table->stmt = PaperEntity::searchByKeyword($this->dbh, $this->form->fields["keyword"]->value);
		}
	}

	public function executeOperation()
	{
		if(array_key_exists("keyword", $_REQUEST))
			$this->viewSearchResults();
		else
			$this->viewSearchForm();
	}
}
?>
