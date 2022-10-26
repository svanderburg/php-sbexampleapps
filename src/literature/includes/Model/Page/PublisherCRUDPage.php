<?php
namespace SBExampleApps\Literature\Model\Page;
use PDO;
use SBLayout\Model\Page\Content\Contents;
use SBData\Model\ParameterMap;
use SBData\Model\Value\Value;
use SBCrud\Model\CRUDModel;
use SBCrud\Model\Page\StaticContentCRUDPage;
use SBExampleApps\Auth\Model\AuthorizationManager;
use SBExampleApps\Literature\Model\CRUD\PublisherCRUDModel;

class PublisherCRUDPage extends StaticContentCRUDPage
{
	public PDO $dbh;

	public AuthorizationManager $authorizationManager;

	public function __construct(PDO $dbh, AuthorizationManager $authorizationManager, array $subPages = array())
	{
		parent::__construct("Publisher",
			/* Key parameters */
			new ParameterMap(array(
				"publisherId" => new Value(true, 255)
			)),
			/* Request parameters */
			new ParameterMap(),
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

	public function constructCRUDModel(): CRUDModel
	{
		return new PublisherCRUDModel($this, $this->dbh, $this->authorizationManager);
	}
}
?>
