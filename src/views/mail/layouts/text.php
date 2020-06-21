<?php

/* @var $this \yii\web\View view component instance */
/* @var $content string */
?>
<?php $this->beginPage() ?>
<?php $this->beginBody() ?>
<?= $content ?>
<?= str_replace('<br>', "\n\r", strip_tags($this->render('_footer'))) ?>
<?php $this->endBody() ?>
<?php $this->endPage() ?>