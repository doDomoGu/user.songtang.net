<?php

use yii\helpers\Html;
use oa\assets\AppMobileAsset;

AppMobileAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?=Yii::$app->id. ($this->title?'_'.Html::encode($this->title):'') ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<div style="height:1000px;">
    <?= $content ?>
</div>
<?=$this->render('tabbar')?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>