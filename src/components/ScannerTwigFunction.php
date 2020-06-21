<?php
/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2020 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace eluhr\shop\components;

use lajax\translatemanager\services\scanners\ScannerFile;
use yii\helpers\Console;

class ScannerTwigFunction extends ScannerFile
{

    /**
     * Extension of PHP files.
     */
    const EXTENSION = '*.twig';

    public $twigTranslators = ['t'];

    /**
     * Start scanning PHP files.
     *
     * @param string $route
     * @param array $params
     * @inheritdoc
     */
    public function run($route, $params = [])
    {
        $this->scanner->stdout('Detect TwigFunction - BEGIN', Console::FG_CYAN);
        foreach (self::$files[static::EXTENSION] as $file) {
            if ($this->containsTranslator($this->twigTranslators, $file)) {
                $this->extractMessages($file, [
                    'translator' => (array) $this->twigTranslators,
                    'begin' => '(',
                    'end' => ')',
                ]);
            }
        }

        $this->scanner->stdout('Detect TwigFunction - END', Console::FG_CYAN);
    }


    protected function getLanguageItem($buffer)
    {
        if (isset($buffer[0][0], $buffer[1], $buffer[2][0]) && $buffer[0][0] === T_CONSTANT_ENCAPSED_STRING && $buffer[1] === ',' && $buffer[2][0] === T_CONSTANT_ENCAPSED_STRING) {
            // is valid call we can extract
            $category = stripcslashes($buffer[0][1]);
            $category = mb_substr($category, 1, -1);
            if (!$this->isValidCategory($category)) {
                return null;
            }

            $message = implode('', $this->concatMessage($buffer));

            return [
                [
                    'category' => $category,
                    'message' => $message,
                ],
            ];
        }

        return null;
    }

    /**
     * Recursice concatenation of multiple-piece language elements.
     *
     * @param array $buffer Array to store language element pieces.
     *
     * @return array Sorted list of language element pieces.
     */
    protected function concatMessage($buffer)
    {
        $messages = [];
        $buffer = array_slice($buffer, 2);
        $message = stripcslashes($buffer[0][1]);
        $messages[] = mb_substr($message, 1, -1);
        if (isset($buffer[1], $buffer[2][0]) && $buffer[1] === '.' && $buffer[2][0] == T_CONSTANT_ENCAPSED_STRING) {
            $messages = array_merge_recursive($messages, $this->concatMessage($buffer));
        }

        return $messages;
    }
}
