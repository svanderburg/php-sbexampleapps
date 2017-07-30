<?php
namespace SBExampleApps\Literature\Model\FileSet;

class PaperFileSet
{
	public static function pdfProvided()
	{
		return (array_key_exists("PDF", $_FILES) && array_key_exists("tmp_name", $_FILES["PDF"]) && $_FILES["PDF"]["tmp_name"] !== "");
	}

	private static function composePapersDir($targetDir, $conferenceId)
	{
		return $targetDir."/".$conferenceId;
	}

	public static function insertOrUpdatePDF($targetDir, $paperId, $conferenceId)
	{
		if(array_key_exists("PDF", $_FILES))
		{
			$papersDir = PaperFileSet::composePapersDir($targetDir, $conferenceId);

			if(!file_exists($papersDir))
			{
				mkdir($papersDir);
				chmod($papersDir, 0777);
			}

			$pdfTarget = $papersDir."/".$paperId.".pdf";
			move_uploaded_file($_FILES["PDF"]["tmp_name"], $pdfTarget);
			chmod($pdfTarget, 0777);
		}
	}

	public static function removePDF($targetDir, $paperId, $conferenceId)
	{
		$papersDir = PaperFileSet::composePapersDir($targetDir, $conferenceId);
		$pdfTarget = $papersDir."/".$paperId.".pdf";

		if(file_exists($pdfTarget))
			unlink($pdfTarget);

		rmdir($papersDir);
	}
}
?>
