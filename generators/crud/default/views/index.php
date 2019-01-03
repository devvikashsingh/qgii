<?php
use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();
$nameAttribute = $generator->getNameAttribute();

echo "<?php\n";
?>

use yii\helpers\Html;
use app\components\QHeader;
use app\components\QToolBar;

/* @var $this yii\web\View */
<?= !empty($generator->searchModelClass) ? "/* @var \$searchModel " . ltrim($generator->searchModelClass, '\\') . " */\n" : '' ?>
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = <?= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>;
$this->params['breadcrumbs'][] = $this->title;
?>
<?php echo "<?="?> QHeader::widget(['title' => $this->title])?>


<section class="tables">
	<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-index">
		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-12">
					<div class="card">
						<div class="card-header d-flex align-items-center">
							<h3 class="h4"><?= "<?= " ?> $this->context->action->id?></h3>
						</div>
						<div class="card-body">
							<div class="table-responsive">
<?= "<?= " ?>QToolBar::widget(['buttons' => $this->context->getToolActions()]);?>	
<?php if (!empty($generator->searchModelClass)) { ?>	
<?= "<?= " ?> $this->render('_grid',compact('dataProvider','searchModel'));?>
<?php }else{ ?>
<?= "<?= " ?> $this->render('_grid',compact('dataProvider'));?>
<?php }?>
</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

