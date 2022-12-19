<?php
namespace SBExampleApps\Portal\Model\CRUD;
use Exception;
use PDO;
use SBLayout\Model\Route;
use SBLayout\Model\PageForbiddenException;
use SBLayout\Model\Page\Page;
use SBData\Model\Form;
use SBData\Model\Field\DateField;
use SBData\Model\Field\HiddenField;
use SBData\Model\Field\ReadOnlyNaturalNumberTextField;
use SBData\Model\Field\TextField;
use SBEditor\Model\Field\HTMLEditorField;
use SBCrud\Model\CRUDForm;
use SBCrud\Model\RouteUtils;
use SBCrud\Model\CRUD\CRUDInterface;
use SBCrud\Model\Page\CRUDPage;
use SBExampleApps\Auth\Model\AuthorizationManager;
use SBExampleApps\Portal\Model\Entity\NewsMessageEntity;

class NewsMessageCRUDInterface extends CRUDInterface
{
	public Route $route;

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

	private function constructNewsMessageForm(): void
	{
		$baseURL = Page::computeBaseURL();

		$this->form = new CRUDForm(array(
			"MESSAGE_ID" => new ReadOnlyNaturalNumberTextField("Id", false),
			"Date" => new DateField("Date", true, true),
			"Title" => new TextField("Title", true, 20, 255),
			"Message" => new HTMLEditorField("editor1", "Message", $baseURL."/iframepage.html", $baseURL."/image/editor", false)
		));
	}

	private function createNewsMessage(): void
	{
		$this->constructNewsMessageForm();
		$this->form->setOperation("insert_newsmessage");
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
			header("Location: ".RouteUtils::composeSelfURLWithParameters(null, "/".rawurlencode($messageId)));
			exit();
		}
	}

	private function viewNewsMessage(): void
	{
		$this->constructNewsMessageForm();
		$this->form->importValues($this->crudPage->entity);
		$this->form->setOperation("update_newsmessage");
	}

	private function updateNewsMessage(): void
	{
		$this->constructNewsMessageForm();
		$this->form->importValues($_REQUEST);
		$this->form->checkFields();

		if($this->form->checkValid())
		{
			$messageId = $GLOBALS["query"]["messageId"];
			$newsMessage = $this->form->exportValues();
			NewsMessageEntity::update($this->dbh, $newsMessage, $messageId);
			header("Location: ".RouteUtils::composePreviousURLWithParameters($this->route, "/".rawurlencode($messageId)));
			exit();
		}
	}

	private function removeNewsMessage(): void
	{
		$messageId = $GLOBALS["query"]["messageId"];
		NewsMessageEntity::remove($this->dbh, $messageId);
		header("Location: ".RouteUtils::composePreviousURLWithParameters($this->route));
		exit();
	}

	public function executeCRUDOperation(?string $operation): void
	{
		if($operation === null)
			$this->viewNewsMessage();
		else
		{
			if($this->authorizationManager->authenticated) // Write operations are only allowed when authenticated
			{
				switch($operation)
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
				}
			}
			else
				throw new PageForbiddenException("No permissions to modify a news message!");
		}
	}
}
?>
