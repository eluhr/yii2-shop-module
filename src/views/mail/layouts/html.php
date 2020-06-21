<?php
/* @var $this \yii\web\View view component instance */
/* @var $content string */
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml" lang="<?= Yii::$app->language ?>">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::$app->charset ?>">
        <style type="text/css">

            img {
                width: 12em;
                margin: 2em auto;
                display: block;
            }
            .informative-text {
                text-align: center;
                margin-bottom: 2em;
                padding-bottom: 2em;
            }

            .first-pad {
                padding-top: 2em;
            }

            main {
                font-size: 16px;
                font-weight: 300;
                max-width: 900px;
                margin: 0 auto;
            }

            section {
                background-color: #ffffff;
                padding: 1em 2em 2em;
                margin: 0 1em;
            }

        </style>
        <?php $this->head() ?>
    </head>
    <body>
    <?php $this->beginBody() ?>
    <main>
        <section>
            <?=$this->render('_header')?>
            <?= $content ?>
        </section>
        <footer>
            <?=$this->render('_footer')?>
        </footer>
    </main>
    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>