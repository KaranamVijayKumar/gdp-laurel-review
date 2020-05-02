<?php
/**
 * File: Parser.php
 * Created: 31-03-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Support\StoryEngine;

use StoryEngine\ParserInterface;
use StoryEngine\PhpParser;

/**
 * Class Parser
 * @package Project\Support\StoryEngine
 */
class Parser extends PhpParser implements ParserInterface
{
    /**
     * Opening tag
     */
    const OPENING_TAG = '{{';
    /**
     * Closing tag
     */
    const CLOSING_TAG = '}}';

    /**
     * Execute the php code if all the functions are allowed
     *
     * @param       $input
     * @param array $data
     *
     * @return bool
     */
    public function execute(&$input, &$data = array())
    {

        $input = $this->compileString((string) $input);

        return parent::execute($input, $data);
    }

    /**
     * Compiles the string to valid php echo. This parser is based on laravel's
     * echo compile functions from the blade parser.
     *
     * @param $input
     * @return mixed
     */
    public function compileString($input)
    {
        $pattern = sprintf('/%s\s*(.+?)\s*%s/s', static::OPENING_TAG, static::CLOSING_TAG);

        $callback = function ($matches) {
            return '<?php echo ' .
            preg_replace('/^(?=\$)(.+?)(?:\s+or\s+)(.+?)$/s', 'isset($1) ? $1 : $2', $matches[1]) .
            '; ?>';
        };

        return  preg_replace_callback($pattern, $callback, $input);
    }
}
