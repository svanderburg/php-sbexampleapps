<?php
namespace SBExampleApps\Portal\Model\CRUD;
use Exception;
use PDO;
use SBLayout\Model\Page\Page;
use SBData\Model\Form;
use SBData\Model\Field\DateField;
use SBData\Model\Field\HiddenField;
use SBData\Model\Field\ReadOnlyNumericIntTextField;
use SBData\Model\Field\TextField;
use SBEditor\Model\Field\HTMLEditorField;
use SBCrud\Model\CRUDModel;
use SBCrud\Model\CRUDPage;
use SBExampleApps\Auth\Model\AuthorizationManager;
use SBExampleApps\Portal\Model\Entity\NewsMessageEntity;

class NewsMessageCRUDModel extends CRUDModel
{
	public PDO $dbh;

	public ?Form $form = null;

	public AuthorizationManager $authorizationManager;

	public function __construct(CRUDPage $crudPage, PDO $dbh, AuthorizationManager $authorizationManager)
	{
		parent::__construct($crudPage);
		$this->dbh = $dbh;
		$this->authorizationManager = $authorizationManager;
	}

	private function constructNewsMessageForm(): void
	{
		$baseURL = Page::computeBaseURL();

		$this->form = new Form(array(
			"__operation" => new HiddenField(true),
			"MESSAGE_ID" => new ReadOnlyNumericIntTextField("Id", false),
			"Date" => new DateField("Date", true, true),
			"Title" => new TextField("Title", true, 20, 255),
			"Message" => new HTMLEditorField("editor1", "Message", $baseURL."/iframepage.html", $baseURL."/image/editor", false)
		));
	}

	private function createNewsMessage(): void
	{
		$this->constructNewsMessageForm();

		$row = array(
			"__operation" => "insert_newsmessage"
		);
		$this->form->importValues($row);
	}

	private function insertNewsMessage(): void
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

	private function viewNewsMessage(): void
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

	private function updateNewsMessage(): void
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

	private function removeNewsMessage(): void
	{
		NewsMessageEntity::remove($this->dbh, $this->keyFields['messageId']->value);
		header("Location: ".$_SERVER['HTTP_REFERER']);
		exit();
	}

	public function executeOperation(): void
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
