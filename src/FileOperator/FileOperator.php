<?php

namespace YG\Phalcon\FileOperator;

class FileOperator implements FileOperatorInterface
{
    public function moveFromTemp(array $file, string $targetDir, string $fileName): ResultMove
    {
        $tmpName = $file['tmp_name'];
        $fileInfo = pathinfo(basename($file['name']));
        $extension = $fileInfo['extension'];
        $targetFileName = $this->cleaningCharacter($fileName) . '.' . $extension;
        $targetFile = $targetDir . '/' . $targetFileName;

        if (move_uploaded_file($tmpName, $targetFile))
            return ResultMove::success($targetFileName);

        return ResultMove::fail();
    }

    private function cleaningCharacter(string $text)
    {
        $find = array("/Ğ/", "/Ü/", "/Ş/", "/İ/", "/Ö/", "/Ç/", "/ğ/", "/ü/", "/ş/", "/ı/", "/ö/", "/ç/");
        $change = array("G", "U", "S", "I", "O", "C", "g", "u", "s", "i", "o", "c");
        $text = preg_replace("/[^0-9a-zA-ZÄzÜŞİÖÇğüşıöç]/", " ", $text);
        $text = preg_replace($find, $change, $text);
        $text = preg_replace("/ +/", " ", $text);
        $text = preg_replace("/ /", "-", $text);
        $text = preg_replace("/\s/", "", $text);
        $text = strtolower($text);
        $text = preg_replace("/^-/", "", $text);
        return preg_replace("/-$/", "", $text);
    }
}