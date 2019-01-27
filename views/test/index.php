<?php

/**
 * @var $model \app\models\Product
 */

?>

  <div>
    <h3>This is my test page</h3>
    <hr>
    <p>
      Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
      dolore magna aliqua.
    </p>
    <hr>
    <p>
      Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
      ex ea commodo consequat.
    </p>
  </div>
  <hr>
<?= \yii\widgets\DetailView::widget(['model' => $model]) ?>
<hr>

