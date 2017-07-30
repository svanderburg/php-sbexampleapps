<?php
namespace SBExampleApps\Literature\Model\Page;
use PDO;
use SBLayout\Model\Page\Content\Contents;
use SBData\Model\Field\TextField;
use SBCrud\Model\Page\StaticContentCRUDPage;
use SBExampleApps\Auth\Model\AuthorizationManager;
use SBExampleApps\Literature\Model\CRUD\PublisherCRUDModel;

class PublisherCRUDPage extends StaticContentCRUDPage
{
	public $dbh;

	public $authorizationManager;

	public function __construct(PDO $dbh, AuthorizationManager $authorizationManager, array $subPages = null)
	{
		parent::__construct("Publisher",
			/* Key fields */
			array(
				"publisherId" => new TextField("Id", true, 20, 255)
			),
			/* Default contents */
			new Contents("crud/publisher.php"),
			/* Error contents */
			new Contents("crud/error.php"),

			/* Contents per operation */
			array(),
			$subPages);

		$this->dbh = $dbh;
		$this->authorizationManager = $authorizationManager;
	}

	public function constructCRUDModel()
	{
		return new PublisherCRUDModel($this, $this->dbh, $this->authorizationManager);
	}
}
?>
