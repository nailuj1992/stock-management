<?php

namespace app\models;

use Yii;

/**
 * This is the model class for "KardexSearchDto".
 *
 * @property string $product_id
 * @property string $product
 * @property string $warehouse_id
 * @property string $warehouse
 * @property string $cutoff_date
 * @property KardexDto[] $transaction_items
 */
class KardexSearchDto extends \yii\db\ActiveRecord
{
    public $product_id;
    public $product;
    public $warehouse_id;
    public $warehouse;
    public $cutoff_date;
    public $transaction_items;

    public static function tableName()
    {
        return 'transaction_item';
    }

    public function rules()
    {
        return [
            [['product_id', 'cutoff_date'], 'required'],
            [['product_id', 'warehouse_id'], 'integer'],
            [['cutoff_date'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'product_id' => Yii::t('app', 'Product'),
            'product' => Yii::t('app', 'Product'),
            'warehouse_id' => Yii::t('app', 'Warehouse'),
            'warehouse' => Yii::t('app', 'Warehouse'),
            'cutoff_date' => Yii::t('app', 'Cut-off Date'),
        ];
    }

    /**
     * Gets the Kardex for a product on a warehouse, at a certain date.
     * @param mixed $company_id
     * @param mixed $product_id
     * @param mixed $warehouse_id
     * @param string $date
     * @return KardexDto[]
     */
    public static function getKardex($company_id, $product_id, $warehouse_id = '', string $date = null): array
    {
        $sql = "SELECT d.document_id, d.intended_for, d.has_other_transaction, t.transaction_id, CONCAT(d.code, ' - ', t.num_transaction) AS transaction, t.creation_date, t.linked_transaction_id, "
            . "CASE WHEN d.intended_for = :intendedInput THEN it.transaction_item_id END as transaction_item_id_input, "
            . "CASE WHEN d.intended_for = :intendedInput THEN it.amount END as amount_input, "
            . "CASE WHEN d.intended_for = :intendedInput THEN it.unit_value END as unit_value_input, "
            . "CASE WHEN d.intended_for = :intendedOutput THEN it.transaction_item_id END as transaction_item_id_output, "
            . "CASE WHEN d.intended_for = :intendedOutput THEN it.amount END as amount_output, "
            . "CASE WHEN d.intended_for = :intendedOutput THEN it.unit_value END as unit_value_output "
            . "FROM transaction_item it "
            . "LEFT JOIN transaction t ON it.transaction_id = t.transaction_id AND t.status = :status "
            . "LEFT JOIN document d ON t.document_id = d.document_id AND d.status = :status "
            . "LEFT JOIN product p ON it.product_id = p.product_id AND p.status = :status "
            . "LEFT JOIN warehouse w ON it.warehouse_id = w.warehouse_id AND w.status = :status "
            . "WHERE it.company_id = :companyId "
            . "AND p.product_id = :productId ";
        if (!isset($warehouse_id) || $warehouse_id === '') {
            $sql .= "AND w.warehouse_id IS NULL ";
        } else {
            $sql .= "AND w.warehouse_id = :warehouseId ";
        }
        if (isset($date)) {
            $sql .= "AND t.creation_date <= :date ";
        }
        $sql .= "ORDER BY t.creation_date, t.created_at";

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

        $kardex = [];
        if (isset($result) && !empty($result)) {
            for ($i = 0; $i < count($result); $i++) {
                $item = $result[$i];
                $kardexItem = new KardexDto();

                $kardexItem->document_id = $item['document_id'];
                $kardexItem->intended_for = $item['intended_for'];
                $kardexItem->has_other_transaction = $item['has_other_transaction'];
                $kardexItem->transaction_id = $item['transaction_id'];
                $kardexItem->transaction = $item['transaction'];
                $kardexItem->creation_date = Utils::formatDate($item['creation_date']);
                $kardexItem->linked_transaction_id = $item['linked_transaction_id'];

                $kardexItem->transaction_item_id_input = $item['transaction_item_id_input'];
                $kardexItem->amount_input = $item['amount_input'];
                $kardexItem->unit_value_input = $item['unit_value_input'];

                $kardexItem->transaction_item_id_output = $item['transaction_item_id_output'];
                $kardexItem->amount_output = $item['amount_output'];
                $kardexItem->unit_value_output = $item['unit_value_output'];

                if ($i === 0) {// First row of the kardex
                    if ($kardexItem->isIntendedForInput()) {
                        $kardexItem->unit_value = $kardexItem->unit_value_input;
                        $kardexItem->total_value_input = $kardexItem->amount_input * $kardexItem->unit_value_input;

                        $kardexItem->amount_balance = $kardexItem->amount_input;
                        $kardexItem->unit_value_balance = $kardexItem->unit_value_input;
                        $kardexItem->total_value_balance = $kardexItem->amount_balance * $kardexItem->unit_value_balance;
                    }
                    if ($kardexItem->isIntendedForOutput()) {
                        $kardexItem->unit_value = $kardexItem->unit_value_output;
                        $kardexItem->total_value_output = $kardexItem->amount_output * $kardexItem->unit_value_output;

                        $kardexItem->amount_balance = $kardexItem->amount_output * -1;
                        $kardexItem->unit_value_balance = $kardexItem->unit_value_output;
                        $kardexItem->total_value_balance = $kardexItem->amount_balance * $kardexItem->unit_value_balance;
                    }
                } else {// Link to the video where the algorithm is explained (in spanish): https://www.youtube.com/watch?v=CU2cFYH3yMM&t=972s
                    $previousItem = $kardex[$i - 1];
                    if ($kardexItem->isIntendedForInput()) {
                        if (!$kardexItem->hasOtherTransaction()) {// Register an input
                            $kardexItem->total_value_input = $kardexItem->amount_input * $kardexItem->unit_value_input;

                            $sumValues = $previousItem->total_value_balance + $kardexItem->total_value_input;
                            $sumAmounts = $previousItem->amount_balance + $kardexItem->amount_input;
                            $kardexItem->unit_value = $sumValues / $sumAmounts;

                            $kardexItem->amount_balance = $sumAmounts;
                            $kardexItem->unit_value_balance = $kardexItem->unit_value;
                            $kardexItem->total_value_balance = $kardexItem->amount_balance * $kardexItem->unit_value_balance;
                        } else {// Register a devolution in output
                            $kardexItem->transaction_item_id_output = $kardexItem->transaction_item_id_input;
                            $kardexItem->amount_output = $kardexItem->amount_input;
                            $kardexItem->unit_value_output = $kardexItem->unit_value_input;

                            $kardexItem->transaction_item_id_input = null;
                            $kardexItem->amount_input = null;
                            $kardexItem->unit_value_input = null;

                            $j = self::searchLinkedTransactionDevolutionUsingBinary($kardex, 0, $i - 1, $kardexItem);
                            if ($j === -1) {
                                break;
                            }
                            $linkedItem = $kardex[$j];

                            $kardexItem->total_value_output = $kardexItem->amount_output * $linkedItem->unit_value;

                            $sumValues = $previousItem->total_value_balance + $kardexItem->total_value_output;
                            $sumAmounts = $previousItem->amount_balance + $kardexItem->amount_output;
                            $kardexItem->unit_value = $sumValues / $sumAmounts;

                            $kardexItem->amount_balance = $sumAmounts;
                            $kardexItem->unit_value_balance = $kardexItem->unit_value;
                            $kardexItem->total_value_balance = $kardexItem->amount_balance * $kardexItem->unit_value_balance;
                        }
                    }
                    if ($kardexItem->isIntendedForOutput()) {
                        if (!$kardexItem->hasOtherTransaction()) {// Register an output
                            $kardexItem->unit_value = $previousItem->unit_value;
                            $kardexItem->total_value_output = $kardexItem->amount_output * $kardexItem->unit_value;

                            $kardexItem->amount_balance = $previousItem->amount_balance - $kardexItem->amount_output;
                            $kardexItem->unit_value_balance = $kardexItem->unit_value;
                            $kardexItem->total_value_balance = $kardexItem->amount_balance * $kardexItem->unit_value_balance;
                        } else {// Register a devolution in input
                            $kardexItem->transaction_item_id_input = $kardexItem->transaction_item_id_output;
                            $kardexItem->amount_input = $kardexItem->amount_output;
                            $kardexItem->unit_value_input = $kardexItem->unit_value_output;

                            $kardexItem->transaction_item_id_output = null;
                            $kardexItem->amount_output = null;
                            $kardexItem->unit_value_output = null;

                            $kardexItem->total_value_input = $kardexItem->amount_input * $kardexItem->unit_value_input;

                            $sumValues = $previousItem->total_value_balance - $kardexItem->total_value_input;
                            $sumAmounts = $previousItem->amount_balance - $kardexItem->amount_input;
                            $kardexItem->unit_value = $sumValues / $sumAmounts;

                            $kardexItem->amount_balance = $sumAmounts;
                            $kardexItem->unit_value_balance = $kardexItem->unit_value;
                            $kardexItem->total_value_balance = $kardexItem->amount_balance * $kardexItem->unit_value_balance;
                        }
                    }
                }

                $kardex[] = $kardexItem;
            }
        }
        return $kardex;
    }

    private static function searchLinkedTransactionDevolutionUsingBinary(array $kardex, int $left, int $right, KardexDto $kardexItem)
    {
        if ($left > $right) {
            return -1;
        }

        $mid = $left + ($right - $left) / 2;

        if ($kardex[$mid]->transaction_id === $kardexItem->linked_transaction_id) {
            return $mid;
        } elseif ($kardex[$mid]->transaction_id < $kardexItem->linked_transaction_id) {
            return self::searchLinkedTransactionDevolutionUsingBinary($kardex, $mid + 1, $right, $kardexItem);
        } else {
            return self::searchLinkedTransactionDevolutionUsingBinary($kardex, $left, $mid - 1, $kardexItem);
        }
    }
}