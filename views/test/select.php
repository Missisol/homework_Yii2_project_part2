<?php

/**
 * @var $result array
 * @var $sort array
 * @var $count integer
 * @var $userTask array
 */
?>

<h3>This is my select page</h3>

<?= \yii\helpers\VarDumper::dumpAsString($result, 5, true) ?>
<br>
<?= \yii\helpers\VarDumper::dumpAsString($sort, 5, true) ?>

<div>Count: <?= $count ?></div>

<?= \yii\helpers\VarDumper::dumpAsString($userTask, 5, true) ?>



