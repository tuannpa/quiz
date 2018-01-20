<?php
/**
 * @author: Tuan Nguyen
 */

/**
 * Class TemplateHelper
 * This class provides methods for rendering template.
 */
class TemplateHelper
{
    /**
     * The path of the file.
     * @var
     */
    private static $_filePath;

    /**
     * Set the path of the file, return self to allow chaining.
     * @param $filePath
     * @return TemplateHelper
     */
    public static function setFilePath($filePath)
    {
        self::$_filePath = $filePath;
        return new self;
    }

    /**
     * Get HTML content of the file from given file's path.
     * @return bool|string
     */
    public function getFileContent()
    {
        if (file_exists(self::$_filePath)) {
            return file_get_contents(self::$_filePath);
        }

        return false;
    }

    /**
     * Replace the placeholder in the HTML template with the given variable's value.
     * @param array $placeholderValue
     * @return string
     * @throws Exception
     */
    public function renderTemplate($placeholderValue = []) {
        foreach ($placeholderValue as $placeholder => $value) {
            $formattedPlaceholder = sprintf('[%s]', $placeholder);
            $newPlaceholderValue[$formattedPlaceholder] = $value;
        }
        if (!$this->getFileContent()) {
            throw new Exception('File not found!');
        }

        return strtr($this->getFileContent(), $newPlaceholderValue);
    }
}