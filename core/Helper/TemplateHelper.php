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
     * @throws Exception
     * @return bool|string
     */
    public function getFileContent()
    {
        if (!file_exists(self::$_filePath)) {
            throw new Exception('File not found!');
        }

        return file_get_contents(self::$_filePath);
    }

    /**
     * Replace the placeholder in the HTML template with the given variable's value.
     * @param array $placeholderValue
     * @return string
     * @throws Exception
     */
    public function renderTemplate($placeholderValue)
    {
        $newPlaceholderValue = [];
        foreach ($placeholderValue as $placeholder => $value) {
            $formattedPlaceholder = sprintf('[%s]', $placeholder);
            $newPlaceholderValue[$formattedPlaceholder] = $value;
        }

        try {
            $this->getFileContent();
        } catch (Exception $e) {
            return $e->getMessage();
        }

        return strtr($this->getFileContent(), $newPlaceholderValue);
    }
}