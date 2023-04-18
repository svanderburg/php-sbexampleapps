<?php
namespace SBExampleApps\CMS\Model\Page\Settings;
use SBGallery\Model\Page\Settings\GalleryPageSettings;

class MyGalleryPageSettings extends GalleryPageSettings
{
	public function __construct()
	{
		parent::__construct("gallery");
	}
}
?>
