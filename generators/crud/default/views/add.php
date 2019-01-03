<?php
use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

echo "<?php\n";
?>

use yii\helpers\Html;
use app\components\QHeader;
use app\components\QToolBar;

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */

$this->title = <?= $generator->generateString('Add ' . Inflector::camel2words(StringHelper::basename($generator->modelClass))) ?>;
$this->params['breadcrumbs'][] = ['label' => <?= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?= "<?= " ?> QHeader::widget(['title' => $this->title]);?>
<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-create">
	<section class="forms">
		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-12">
					<div class="card">
						<div class="card-header d-flex align-items-center">
							<h3 class="h4"><?= "<?= " ?>Html::encode($this->context->action->id) ?></h3>
						</div>
						<div class="card-body">
							<?= "<?= " ?>QToolBar::widget(['buttons' => $this->context->getToolActions()]);?>
                            <?= "<?= " ?>$this->render('_form',compact('model')) ?>
    					</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
