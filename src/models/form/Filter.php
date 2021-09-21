<?php
/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2020 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace eluhr\shop\models\form;

use yii\base\Model;

/**
 *
 * @property bool $isFiltered
 */
class Filter extends Model
{
    public $tag = [];
    public $q;

    public function formName()
    {
        return '';
    }

    public function rules()
    {
        $rules = parent::rules();
        $rules[] = [[
            'tag',
            'q'
        ],'safe'];
        return $rules;
    }

    public function tagIds()
    {
        $tagIds = [];

        foreach ($this->tag as $tags) {
            if (is_array($tags)) {
                foreach ($tags as $tag) {
                    $tagIds[] = $tag;
                }
            }
        }

        return $tagIds;
    }

    public function getIsFiltered()
    {
        return !empty($this->tag) || !empty($this->q);
    }
}