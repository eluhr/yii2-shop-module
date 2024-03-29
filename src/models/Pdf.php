<?php
/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2020 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace eluhr\shop\models;

use Dompdf\Dompdf;
use Dompdf\Options;
use Yii;

class Pdf
{

    /**
     * @var Dompdf
     */
    private $_pdf;

    /**
     * Pdf constructor.
     */
    public function __construct()
    {
        $this->_pdf = new Dompdf();
        $options = new Options();
        $options->setChroot(Yii::getAlias('@runtime/tmp/download'));
        $this->_pdf->setOptions($options);

    }

    public function saveAs($filename)
    {
        $this->_pdf->render();
        return file_put_contents($filename, $this->_pdf->output());
    }

    public function addPage($html)
    {
        $this->_pdf->loadHtml($html);
    }
}
