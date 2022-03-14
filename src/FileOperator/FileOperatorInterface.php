<?php

namespace YG\Phalcon\FileOperator;

interface FileOperatorInterface
{
    public function moveFromTemp(array $file, string $targetDir, string $fileName): ResultMove;
}