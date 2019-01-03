<?php
/**
 * This is the template for generating a CRUD controller class file.
 */

use yii\db\ActiveRecordInterface;
use yii\helpers\StringHelper;


/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$controllerClass = StringHelper::basename($generator->controllerClass);
$modelClass = StringHelper::basename($generator->modelClass);
$searchModelClass = StringHelper::basename($generator->searchModelClass);
if ($modelClass === $searchModelClass) {
    $searchModelAlias = $searchModelClass . 'Search';
}

/* @var $class ActiveRecordInterface */
$class = $generator->modelClass;
$pks = $class::primaryKey();
$urlParams = $generator->generateUrlParams();
$actionParams = $generator->generateActionParams();
$actionParamComments = $generator->generateActionParamComments();

echo "<?php\n";
?>

namespace <?= StringHelper::dirname(ltrim($generator->controllerClass, '\\')) ?>;

use Yii;
use <?= ltrim($generator->modelClass, '\\') ?>;
<?php if (!empty($generator->searchModelClass)): ?>
use <?= ltrim($generator->searchModelClass, '\\') . (isset($searchModelAlias) ? " as $searchModelAlias" : "") ?>;
<?php else: ?>
use yii\data\ActiveDataProvider;
<?php endif; ?>
use <?= ltrim($generator->baseControllerClass, '\\') ?>;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\filters\AccessRule;

/**
 * <?= $controllerClass ?> implements the CRUD actions for <?= $modelClass ?> model.
 */
class <?= $controllerClass ?> extends <?= StringHelper::basename($generator->baseControllerClass) . "\n" ?>
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'ruleConfig' => [
                    'class' => AccessRule::className()
                ],
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'add',
                            'view',
                            'update',
                        ],
                        'allow' => true,
                        'roles' => [
                            '@'
                        ]
                    ],
                    [
                        'actions' => [
                            'view',
                        ],
                        'allow' => true,
                        'roles' => [
                            '?',
                            '*'
                        ]
                    ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => [
                        'POST'
                    ]
                ]
            ]
        ];
    }

    /**
     * Lists all <?= $modelClass ?> models.
     * @return mixed
     */
    public function actionIndex()
    {
<?php if (!empty($generator->searchModelClass)): ?>
        $searchModel = new <?= isset($searchModelAlias) ? $searchModelAlias : $searchModelClass ?>();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', compact('dataProvider','searchModel'));
<?php else: ?>
        $dataProvider = new ActiveDataProvider([
            'query' => <?= $modelClass ?>::find(),
        ]);

        return $this->render('index', compact('dataProvider'));
<?php endif; ?>
    }

    /**
     * Displays a single <?= $modelClass ?> model.
     * <?= implode("\n     * ", $actionParamComments) . "\n" ?>
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView(<?= $actionParams ?>)
    {
    	$model = $this->findModel(<?= $actionParams ?>);
        return $this->render('view', compact('model'));
    }

    /**
     * Creates a new <?= $modelClass ?> model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionAdd()
    {
        $model = new <?= $modelClass ?>();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect($model->getUrl());
        }

        return $this->render('add', compact('model'));
    }

    /**
     * Updates an existing <?= $modelClass ?> model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * <?= implode("\n     * ", $actionParamComments) . "\n" ?>
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate(<?= $actionParams ?>)
    {
        $model = $this->findModel(<?= $actionParams ?>);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect($model->getUrl());
        }

        return $this->render('update', compact('model'));
    }

    /**
     * Deletes an existing <?= $modelClass ?> model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * <?= implode("\n     * ", $actionParamComments) . "\n" ?>
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete(<?= $actionParams ?>)
    {
        $this->findModel(<?= $actionParams ?>)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the <?= $modelClass ?> model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * <?= implode("\n     * ", $actionParamComments) . "\n" ?>
     * @return <?=                   $modelClass ?> the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(<?= $actionParams ?>)
    {
<?php
if (count($pks) === 1) {
    $condition = '$id';
} else {
    $condition = [];
    foreach ($pks as $pk) {
        $condition[] = "'$pk' => \$$pk";
    }
    $condition = '[' . implode(', ', $condition) . ']';
}
?>
        if (($model = <?= $modelClass ?>::findOne(<?= $condition ?>)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(<?= $generator->generateString('Oops! This obviously isn\'t a page you were looking for...') ?>);
    }
    public function getToolActions($model = null)
    {
        switch ($this->action->id) {
            case 'add':
                $this->headerMenu['index'] = [
                    'label' => '<i class="fa fa-tasks"></i>',
                    'url' => [
                        'index'
                    ],
                    'htmlOptions' => [
                        'class' => 'btn btn-primary',
                        'title' => 'Index'
                    ]
                    // 'visible' => false
                ];
                break;
            case 'index':
                $this->headerMenu['add'] = [
                    'label' => '<i class="fa fa-plus"></i>',
                    'url' => [
                        'add'
                    ],
                    'htmlOptions' => [
                        'class' => 'btn btn-primary',
                        'title' => 'Add'
                    ]
                    // 'visible' => false
                ];
                break;
            case 'view':
                $this->headerMenu['index'] = [
                    'label' => '<i class="fa fa-tasks"></i>',
                    'url' => [
                        'index'
                    ],
                    'htmlOptions' => [
                        'class' => 'btn btn-success',
                        'title' => 'Index'
                    ]
                    // 'visible' => false
                ];
                $this->headerMenu['update'] = [
                    'label' => '<i class="fa fa-pencil"></i>',
                    'url' => $model->getUrl('update'),
                    'htmlOptions' => [
                        'class' => 'btn btn-primary',
                        'title' => 'Update'
                    ]
                    // 'visible' => false
                ];
                $this->headerMenu['delete'] = [
                    'label' => '<i class="fa fa-trash"></i>',
                    'url' => $model->getUrl('delete'),
                    'htmlOptions' => [
                        'class' => 'btn btn-danger',
                        'title' => 'Delete',
                        'data' => [
                            'confirm' => 'Are you sure you want to delete this item?',
                            'method' => 'post'
                        ]
                    ]
                    // 'visible' => false
                ];
                break;
            case 'update':
                $this->headerMenu['index'] = [
                    'label' => '<i class="fa fa-tasks"></i>',
                    'url' => [
                        'index'
                    ],
                    'htmlOptions' => [
                        'class' => 'btn btn-primary',
                        'title' => 'Index'
                    ]
                    // 'visible' => false
                ];
                break;
        }

        return $this->headerMenu;
    }
}
