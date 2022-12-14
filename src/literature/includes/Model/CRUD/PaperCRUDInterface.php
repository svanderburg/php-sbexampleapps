<?php
namespace SBExampleApps\Literature\Model\CRUD;
use PDO;
use SBLayout\Model\Route;
use SBLayout\Model\PageForbiddenException;
use SBData\Model\Form;
use SBData\Model\Field\DateField;
use SBData\Model\Field\FileField;
use SBData\Model\Field\HiddenField;
use SBData\Model\Field\ReadOnlyNumericIntTextField;
use SBData\Model\Field\TextField;
use SBData\Model\Field\TextAreaField;
use SBData\Model\Field\URLField;
use SBData\Model\Table\Anchor\AnchorRow;
use SBCrud\Model\RouteUtils;
use SBCrud\Model\CRUDForm;
use SBCrud\Model\CRUD\CRUDInterface;
use SBCrud\Model\Page\CRUDPage;
use SBExampleApps\Auth\Model\AuthorizationManager;
use SBExampleApps\Literature\Model\Entity\PaperEntity;
use SBExampleApps\Literature\Model\FileSet\PaperFileSet;

class PaperCRUDInterface extends CRUDInterface
{
	public Route $route;

	public CRUDPage $crudPage;

	public PDO $dbh;

	public CRUDForm $form;

	public bool $hasPDF = false;

	public AuthorizationManager $authorizationManager;

	public function __construct(Route $route, CRUDPage $crudPage, PDO $dbh, AuthorizationManager $authorizationManager)
	{
		parent::__construct($crudPage);
		$this->route = $route;
		$this->crudPage = $crudPage;
		$this->dbh = $dbh;
		$this->authorizationManager = $authorizationManager;
	}

	private function constructPaperForm(): void
	{
		$this->form = new CRUDForm(array(
			"PAPER_ID" => new ReadOnlyNumericIntTextField("Id", false),
			"Title" => new TextField("Title", true, 20, 255),
			"Date" => new DateField("Date", true, 20, 255),
			"Abstract" => new TextAreaField("Abstract", false, 60, 20),
			"URL" => new URLField("URL", false),
			"PDF" => new FileField("PDF", "application/pdf", false),
			"Comment" => new TextField("Comment", false, 20, 255)
		));
	}

	private function createPaper(): void
	{
		$this->constructPaperForm();
		$this->form->setOperation("insert_paper");
	}

	private function viewPaper(): void
	{
		$this->constructPaperForm();
		$this->form->importValues($this->crudPage->entity);
		$this->form->setOperation("update_paper");
		$this->hasPDF = $this->crudPage->entity['hasPDF'] == 1;
	}

	private function insertPaper(): void
	{
		$this->constructPaperForm();
		$this->form->importValues($_REQUEST);
		$this->form->checkFields();

		if($this->form->checkValid())
		{
			$conferenceId = $GLOBALS["query"]["conferenceId"];

			$paper = $this->form->exportValues();
			$paper['hasPDF'] = PaperFileSet::pdfProvided() ? 1 : 0;

			$paperId = PaperEntity::insert($this->dbh, $paper, $conferenceId);

			if(PaperFileSet::pdfProvided())
				PaperFileSet::insertOrUpdatePDF(dirname($_SERVER["SCRIPT_FILENAME"])."/pdf", $paperId, $conferenceId);

			header("Location: ".RouteUtils::composeSelfURL()."/".rawurlencode($paperId));
			exit();
		}
	}

	private function updatePaper(): void
	{
		$this->constructPaperForm();
		$this->form->importValues($_REQUEST);
		$this->form->checkFields();

		if($this->form->checkValid())
		{
			$paper = $this->form->exportValues();
			$paper['hasPDF'] = PaperFileSet::pdfProvided() ? 1 : 0;

			$paperId = $GLOBALS["query"]["paperId"];
			$conferenceId = $GLOBALS["query"]["conferenceId"];

			PaperEntity::update($this->dbh, $paper, $paperId, $conferenceId);

			if(PaperFileSet::pdfProvided())
				PaperFileSet::insertOrUpdatePDF(dirname($_SERVER["SCRIPT_FILENAME"])."/pdf", $paperId, $conferenceId);

			header("Location: ".$this->route->composeParentPageURL($_SERVER["SCRIPT_NAME"])."/".rawurlencode($paper["PAPER_ID"]));
			exit();
		}
	}

	private function deletePaper(): void
	{
		$paperId = $GLOBALS["query"]["paperId"];
		$conferenceId = $GLOBALS["query"]["conferenceId"];

		PaperEntity::remove($this->dbh, $paperId, $conferenceId);
		PaperFileSet::removePDF(dirname($_SERVER["SCRIPT_FILENAME"])."/pdf", $paperId, $conferenceId);
		header("Location: ".$_SERVER['HTTP_REFERER'].AnchorRow::composePreviousRowFragment());
		exit();
	}

	private function deletePaperPDF(): void
	{
		$paperId = $GLOBALS["query"]["paperId"];
		$conferenceId = $GLOBALS["query"]["conferenceId"];

		PaperEntity::removePDF($this->dbh, $paperId, $conferenceId);
		PaperFileSet::removePDF(dirname($_SERVER["SCRIPT_FILENAME"])."/pdf", $paperId, $conferenceId);
		header("Location: ".$_SERVER['HTTP_REFERER']);
		exit();
	}

	public function executeCRUDOperation(?string $operation): void
	{
		if($operation === null)
			$this->viewPaper();
		else
		{
			if($this->authorizationManager->authenticated) // Write operations are only allowed when authenticated
			{
				switch($operation)
				{
					case "create_paper":
						$this->createPaper();
						break;
					case "insert_paper":
						$this->insertPaper();
						break;
					case "update_paper":
						$this->updatePaper();
						break;
					case "delete_paper":
						$this->deletePaper();
						break;
					case "delete_paper_pdf":
						$this->deletePaperPDF();
						break;
				}
			}
			else
				throw new PageForbiddenException("No permissions to modify a paper!");
		}
	}
}
?>
