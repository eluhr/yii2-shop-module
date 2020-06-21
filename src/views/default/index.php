<?php
/**
 * @var ActiveDataProvider $dataProvider
 * @var View $this
 * @var Filter[] $filters
 * @var FilterForm $filterForm
 */

use hrzg\widget\widgets\Cell;
use eluhr\shop\models\Filter;
use eluhr\shop\models\form\Filter as FilterForm;
use eluhr\shop\models\ShopSettings;
use yii\data\ActiveDataProvider;
use yii\web\View;
use yii\widgets\ListView;

?>

<?= Cell::widget(['id' => 'shop-top-global'])?>
<div class="items-view">
    <?php
    if (ShopSettings::shopGeneralShowFilters()) {
        echo $this->render('_filter', ['filters' => $filters, 'filterForm' => $filterForm]);
    }
    ?>

    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'layout' => "{items}\n{summary}\n{pager}",
        'itemOptions' => [
            'class' => 'list-item'
        ],
        'itemView' => function ($product) {
            return $this->render('_product_thumbnail', ['product' => $product]);
        }
    ]) ?>
</div>
<?= Cell::widget(['id' => 'shop-bottom-global'])?>

