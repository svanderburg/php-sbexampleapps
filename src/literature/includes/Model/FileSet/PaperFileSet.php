<?php
namespace SBExampleApps\Literature\Model\FileSet;

class PaperFileSet
{
	public static function pdfProvided(): bool
	{
		return (array_key_exists("PDF", $_FILES) && array_key_exists("tmp_name", $_FILES["PDF"]) && $_FILES["PDF"]["tmp_name"] !== "");
	}

	private static function composePapersDir(string $targetDir, int $conferenceId): string
	{
		return $targetDir."/".$conferenceId;
	}

	public static function insertOrUpdatePDF(string $targetDir, int $paperId, int $conferenceId): void
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

	public static function removePDF(string $targetDir, int $paperId, int $conferenceId): void
	{
		$papersDir = PaperFileSet::composePapersDir($targetDir, $conferenceId);
		$pdfTarget = $papersDir."/".$paperId.".pdf";

		if(file_exists($pdfTarget))
			unlink($pdfTarget);

		if(file_exists($papersDir))
			rmdir($papersDir);
	}
}
?>
