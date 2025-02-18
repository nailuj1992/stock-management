<?php

namespace app\models;

use Yii;

/**
 * This is the model class for "KardexDto".
 *
 * @property string $document_id
 * @property string $intended_for
 * @property string $has_other_transaction
 * @property string $transaction_id
 * @property string $transaction
 * @property string $creation_date
 * @property string $linked_transaction_id
 * @property int $unit_value
 * @property string $transaction_item_id_input
 * @property int $amount_input
 * @property int $unit_value_input
 * @property int $total_value_input
 * @property string $transaction_item_id_output
 * @property int $amount_output
 * @property int $unit_value_output
 * @property int $total_value_output
 * @property int $amount_balance
 * @property int $unit_value_balance
 * @property int $total_value_balance
 */
class KardexDto extends \yii\db\ActiveRecord
{
    public $document_id;
    public $intended_for;
    public $has_other_transaction;
    public $transaction_id;
    public $transaction;
    public $creation_date;
    public $linked_transaction_id;

    public $unit_value;

    public $transaction_item_id_input;
    public $amount_input;
    public $unit_value_input;
    public $total_value_input;

    public $transaction_item_id_output;
    public $amount_output;
    public $unit_value_output;
    public $total_value_output;

    public $amount_balance;
    public $unit_value_balance;
    public $total_value_balance;

    public static function tableName()
    {
        return 'transaction_item';
    }

    public function rules()
    {
        return [
        ];
    }

    public function attributeLabels()
    {
        return [
        ];
    }

    public function isIntendedForOutput()
    {
        return $this->intended_for === Constants::DOCUMENT_ACTION_INTENDED_OUTPUT_DB;
    }

    public function isIntendedForInput()
    {
        return $this->intended_for === Constants::DOCUMENT_ACTION_INTENDED_INPUT_DB;
    }

    public function hasOtherTransaction()
    {
        return $this->has_other_transaction === Constants::OPTION_YES_DB;
    }
}