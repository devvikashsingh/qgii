<?php
use yii\helpers\Inflector;

/**
 * This is the template for generating the model class of a specified table.
 */

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\model\Generator */
/* @var $tableName string full table name */
/* @var $className string class name */
/* @var $queryClassName string query class name */
/* @var $tableSchema yii\db\TableSchema */
/* @var $properties array list of properties (property => [type, name. comment]) */
/* @var $labels string[] list of attribute labels (name => label) */
/* @var $rules string[] list of validation rules */
/* @var $relations array list of relations (name => relation declaration) */

echo "<?php\n";
?>

namespace <?= $generator->ns ?>;

use Yii;
use yii\helpers\ArrayHelper;
use app\components\QActiveRecord;
/**
 * This is the model class for table "<?= $generator->generateTableName($tableName) ?>".
 *
<?php foreach ($properties as $property => $data): ?>
 * @property <?= "{$data['type']} \${$property}"  . ($data['comment'] ? ' ' . strtr($data['comment'], ["\n" => ' ']) : '') . "\n" ?>
<?php endforeach; ?>
<?php if (!empty($relations)): ?>
 *
<?php foreach ($relations as $name => $relation): ?>
 * @property <?= $relation[1] . ($relation[2] ? '[]' : '') . ' $' . lcfirst($name) . "\n" ?>
<?php endforeach; ?>
<?php endif; ?>
 */
class <?= $className ?> extends <?= '\\' . ltrim($generator->baseClass, '\\') . "\n" ?>
{
    const TYPE_ONE = 0;
    const TYPE_TWO = 1;
    const STATE_PENDING = 0;
    const STATE_ACTIVE = 1;
    const STATE_DISCARD = 2;
	public function __toString(){
	 	return $this;
	}
	 /**
     * {@inheritdoc}
     */
    public function beforeValidate()
    {
        if ($this->isNewRecord) {
            if (empty($this->created_on)) {
                $this->created_on = date('Y-m-d H:i:s');
            }
            if (empty($this->created_by_id)) {
                $this->created_by_id = \Yii::$app->user->id;
            }
        } else {
            if (empty($this->updated_on)) {
                $this->updated_on = date('Y-m-d H:i:s');
            }
        }

        return parent::beforeValidate();
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '<?= $generator->generateTableName($tableName) ?>';
    }
<?php if ($generator->db !== 'db'): ?>

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('<?= $generator->db ?>');
    }
<?php endif; ?>

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [<?= empty($rules) ? '' : ("\n            " . implode(",\n            ", $rules) . ",\n        ") ?>];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
<?php foreach ($labels as $name => $label): ?>
            <?= "'$name' => " . $generator->generateString($label) . ",\n" ?>
<?php endforeach; ?>
        ];
    }
<?php foreach ($relations as $name => $relation): ?>

    /**
     * @return \yii\db\ActiveQuery
     */
    public function get<?= $name ?>()
    {
        <?= $relation[0] . "\n" ?>
    }
<?php endforeach; ?>
<?php if ($queryClassName): ?>
<?php
    $queryClassFullName = ($generator->ns === $generator->queryNs) ? $queryClassName : '\\' . $generator->queryNs . '\\' . $queryClassName;
    echo "\n";
?>
    /**
     * {@inheritdoc}
     * @return <?= $queryClassFullName ?> the active query used by this AR class.
     */
    public static function find()
    {
        return new <?= $queryClassFullName ?>(get_called_class());
    }
<?php endif; ?>
	public function getStateList() {
        return [
            self::STATE_PENDING => "Pending",
            self::STATE_ACTIVE => "Active",
            self::STATE_DISCARD => "Discard",
        ];
    }
    public function getTypeList() {
        return [
            self::TYPE_ONE => "Type one",
            self::TYPE_TWO => "Type two"
        ];
    }
    
    
    <?php foreach ($properties as $property => $data): 
    	 if(preg_match('/^((.*?)_id)$/i', $property)) {
    	     if(preg_match('/^(state_id|type_id)$/i', $property)) {
    	         continue;
    	     }
    	     
    	     $name = Inflector::id2camel(Inflector::humanize($property,'_'));
    	     $functionName = "get{$name}List";
            ?>
            public function <?=$functionName?>() {
                return ArrayHelper::map(<?= $name?>::findAll(), 'id', function($data) { return $data; });
            }
    	<?php }?>
    <?php endforeach;?>
    /**
     * Before save.
     * 
     */
    /*public function beforeSave($insert)
    {
        if (parent::beforeSave($insert))
        {
            // add your code here
            return true;
        }
        else
            return false;
    }*/

    /**
     * After save.
     *
     */
    /*public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        // add your code here
    }*/
}
