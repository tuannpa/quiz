<?php

class TemplateHelper
{
    private static $_filePath;

    public static function setFilePath($filePath)
    {
        self::$_filePath = $filePath;
        return new self;
    }

    public function getFileContent()
    {
        if (file_exists(self::$_filePath)) {
            return file_get_contents(self::$_filePath);
        } else {
            return false;
        }
    }

    public function renderTemplate($placeholderValue = []) {
        if ($this->getFileContent()) {
            return strtr($this->getFileContent(), $placeholderValue);
        } else {
            throw new Exception('File not found!');
        }
    }
}