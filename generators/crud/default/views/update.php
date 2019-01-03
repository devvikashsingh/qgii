<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;


/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();

echo "<?php\n";
?>

use yii\helpers\Html;
use app\components\ToolBar;

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */

$this->title = <?= strtr($generator->generateString('Update ' .
    Inflector::camel2words(StringHelper::basename($generator->modelClass)) .
    ': {nameAttribute}', ['nameAttribute' => '{nameAttribute}']), [
    '{nameAttribute}\'' => '\' . $model->' . $generator->getNameAttribute()
]) ?>;
$this->params['breadcrumbs'][] = ['label' => <?= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>, 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model-><?= $generator->getNameAttribute() ?>, 'url' => ['view', <?= $urlParams ?>]];
$this->params['breadcrumbs'][] = <?= $generator->generateString('Update') ?>;
?>
<?= '<?= ' ?>QHeader::widget(compact('model'));?>

<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-update">
   <section class="forms">
		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-12">
					<div class="card">
						<div class="card-header d-flex align-items-center">
							<h3 class="h4"><?= '<?= ' ?>$this->context->action->id . $model ?></h3>
						</div>
						<div class="card-body">
						<?= "<?= " ?>QToolBar::widget(['buttons' => $this->context->getToolActions($model)]);?>
    <?= '<?= ' ?>$this->render('_form', compact('model')) ?>
</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>