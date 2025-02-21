<?php

namespace app\models;

use Yii;


/**
 * This is the model class for "ExistencesDto".
 *
 * @property string $product_id
 * @property string $product
 * @property string $warehouse_id
 * @property string $warehouse
 * @property string $cutoff_date
 * @property int $amountInput
 * @property int $amountOutput
 * @property int $amountDifference
 */
class ExistencesDto extends \yii\db\ActiveRecord
{
    public $product_id;
    public $product;
    public $warehouse_id;
    public $warehouse;
    public $cutoff_date;
    public $amountInput;
    public $amountOutput;
    public $amountDifference;

    public static function tableName()
    {
        return 'transaction_item';
    }

    public function rules()
    {
        return [
            [['product_id', 'cutoff_date'], 'required'],
            [['product_id'], 'integer'],
            [['cutoff_date'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'product_id' => Yii::t(TextConstants::PRODUCT, TextConstants::PRODUCT_MODEL_ID),
            'product' => Yii::t(TextConstants::PRODUCT, TextConstants::PRODUCT_MODEL_ID),
            'warehouse_id' => Yii::t(TextConstants::WAREHOUSE, TextConstants::WAREHOUSE_MODEL_ID),
            'warehouse' => Yii::t(TextConstants::WAREHOUSE, TextConstants::WAREHOUSE_MODEL_ID),
            'cutoff_date' => Yii::t('app', 'Cut-off Date'),
            'amountInput' => Yii::t('app', 'Amount Input'),
            'amountOutput' => Yii::t('app', 'Amount Output'),
            'amountDifference' => Yii::t('app', 'Amount'),
        ];
    }

    /**
     * Gets the existence of a product on a warehouse, at a certain date.
     * @param mixed $company_id
     * @param mixed $product_id
     * @param mixed $warehouse_id
     * @param string $date
     * @return ExistencesDto[]
     */
    public static function getExistences($company_id, $product_id, $warehouse_id = '', string $date = null): array
    {
        $sql = "SELECT p.product_id, CONCAT(p.code, ' - ', p.name) AS product, w.warehouse_id, CONCAT(w.code, ' - ', w.name) AS warehouse, "
            . "SUM(CASE WHEN d.intended_for = :intendedInput THEN it.amount ELSE 0 end) AS amountInput, "
            . "SUM(CASE WHEN d.intended_for = :intendedOutput THEN it.amount ELSE 0 end) AS amountOutput "
            . "FROM transaction_item it "
            . "LEFT JOIN transaction t ON it.transaction_id = t.transaction_id AND t.status = :status "
            . "LEFT JOIN document d ON t.document_id = d.document_id AND d.status = :status "
            . "LEFT JOIN product p ON it.product_id = p.product_id AND p.status = :status "
            . "LEFT JOIN warehouse w ON it.warehouse_id = w.warehouse_id AND w.status = :status "
            . "WHERE it.company_id = :companyId "
            . "AND p.product_id = :productId ";
        if (!isset($warehouse_id) || $warehouse_id === '') {
            $sql .= "AND w.warehouse_id IS NULL ";
        } elseif ($warehouse_id !== Constants::OPTION_ALL_SELECT) {
            $sql .= "AND w.warehouse_id = :warehouseId ";
        }
        if (isset($date)) {
            $sql .= "AND t.creation_date <= :date ";
        }
        $sql .= "GROUP BY p.product_id, w.warehouse_id";

        $connection = Yii::$app->getDb();
        $command = $connection->createCommand($sql, [
            ':intendedInput' => Constants::DOCUMENT_ACTION_INTENDED_INPUT_DB,
            ':intendedOutput' => Constants::DOCUMENT_ACTION_INTENDED_OUTPUT_DB,
            ':status' => Constants::STATUS_ACTIVE_DB,
            ':companyId' => $company_id,
            ':productId' => $product_id,
            ':warehouseId' => $warehouse_id,
            ':date' => $date,
        ]);
        $result = $command->queryAll();

        $existencesList = [];
        if (isset($result) && !empty($result)) {
            foreach ($result as $item) {
                $existencesDto = new ExistencesDto();
                $existencesDto->product_id = $item['product_id'];
                $existencesDto->product = $item['product'];
                $existencesDto->warehouse_id = $item['warehouse_id'];
                $existencesDto->warehouse = $item['warehouse'];
                $existencesDto->amountInput = isset($item['amountInput']) ? $item['amountInput'] : 0;
                $existencesDto->amountOutput = isset($item['amountOutput']) ? $item['amountOutput'] : 0;
                $existencesDto->amountDifference = $existencesDto->amountInput - $existencesDto->amountOutput;
                $existencesList[] = $existencesDto;
            }
        }
        return $existencesList;
    }

}