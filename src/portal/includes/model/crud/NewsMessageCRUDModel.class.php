<?php
require_once("data/model/Form.class.php");
require_once("data/model/field/ReadOnlyNumericIntTextField.class.php");
require_once("data/model/field/TextField.class.php");
require_once("data/model/field/HiddenField.class.php");
require_once("data/model/field/DateField.class.php");
require_once("editor/model/HTMLEditorField.class.php");
require_once("crud/model/CRUDModel.class.php");
require_once("auth/model/AuthorizationManager.class.php");
require_once("model/entities/NewsMessageEntity.class.php");

class NewsMessageCRUDModel extends CRUDModel
{
	public $dbh;

	public $form = null;

	public $authorizationManager;

	public function __construct(CRUDPage $crudPage, PDO $dbh, AuthorizationManager $authorizationManager)
	{
		parent::__construct($crudPage);
		$this->dbh = $dbh;
		$this->authorizationManager = $authorizationManager;
	}

	private function constructNewsMessageForm()
	{
		$baseURL = Page::computeBaseURL();

		$this->form = new Form(array(
			"__operation" => new HiddenField(true),
			"MESSAGE_ID" => new ReadOnlyNumericIntTextField("Id", false),
			"Date" => new DateField("Date", true, true),
			"Title" => new TextField("Title", true, 20, 255),
			"Message" => new HTMLEditorField("editor1", "Message", $baseURL."/iframepage.html", $baseURL."/lib/sbeditor/editor/image", false)
		));
	}

	private function createNewsMessage()
	{
		$this->constructNewsMessageForm();

		$row = array(
			"__operation" => "insert_newsmessage"
		);
		$this->form->importValues($row);
	}

	private function insertNewsMessage()
	{
		$this->constructNewsMessageForm();
		$this->form->importValues($_REQUEST);
		$this->form->checkFields();

		if($this->form->checkValid())
		{
			$newsMessage = $this->form->exportValues();
			$messageId = NewsMessageEntity::insert($this->dbh, $newsMessage);
			header("Location: ".$_SERVER["SCRIPT_NAME"]."/news/".$messageId);
			exit();
		}
	}

	private function viewNewsMessage()
	{
		/* Query the properties of the requested news message and construct a form from it */
		$this->constructNewsMessageForm();

		$stmt = NewsMessageEntity::queryOne($this->dbh, $this->keyFields['messageId']->value);

		if(($row = $stmt->fetch()) === false)
		{
			header("HTTP/1.1 404 Not Found");
			throw new Exception("Cannot find new message with this id!");
		}
		else
		{
			$row['__operation'] = "update_newsmessage";
			$this->form->importValues($row);
		}
	}

	private function updateNewsMessage()
	{
		$this->constructNewsMessageForm();
		$this->form->importValues($_REQUEST);
		$this->form->checkFields();

		if($this->form->checkValid())
		{
			$newsMessage = $this->form->exportValues();
			NewsMessageEntity::update($this->dbh, $newsMessage, $this->keyFields['messageId']->value);
			header("Location: ".$_SERVER["SCRIPT_NAME"]."/news/".$this->keyFields['messageId']->value);
			exit();
		}
	}

	private function removeNewsMessage()
	{
		NewsMessageEntity::remove($this->dbh, $this->keyFields['messageId']->value);
		header("Location: ".$_SERVER['HTTP_REFERER']);
		exit();
	}

	public function executeOperation()
	{
		if(array_key_exists("__operation", $_REQUEST))
		{
			if($this->authorizationManager->authenticated) // Write operations are only allowed when authenticated
			{
				switch($_REQUEST["__operation"])
				{
					case "create_newsmessage":
						$this->createNewsMessage();
						break;
					case "insert_newsmessage":
						$this->insertNewsMessage();
						break;
					case "update_newsmessage":
						$this->updateNewsMessage();
						break;
					case "remove_newsmessage":
						$this->removeNewsMessage();
						break;
					default:
						$this->viewNewsMessage();
				}
			}
			else
			{
				header("HTTP/1.1 403 Forbidden");
				throw new Exception("No permissions to modify a news message!");
			}
		}
		else
			$this->viewNewsMessage();
	}
}
?>
