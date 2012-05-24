<?php

/**
 * This is the model class for table "da_reject_conditions".
 *
 * The followings are the available columns in table 'da_reject_conditions':
 * @property integer $id
 * @property string $table
 * @property integer $code
 * @property string $message
 * @property string $record
 * @property integer $import_site_history_id
 * @property string $modified_at
 * @property string $created_at
 *
 * The followings are the available model relations:
 * @property ImportSiteHistories $importSiteHistory
 */
class RejectCondition extends DaActiveRecordModel
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return RejectCondition the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'da_reject_conditions';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('table, import_site_history_id', 'required'),
			array('code, import_site_history_id', 'numerical', 'integerOnly' => true),
			array('table', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'importSiteHistory' => array(self::BELONGS_TO, 'ImportSiteHistory', 'import_site_history_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'table' => 'Table',
			'code' => 'Code',
			'message' => 'Message',
			'import_site_history_id' => 'Import Site History',
			'modified_at' => 'Modified At',
			'created_at' => 'Created At',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('table',$this->table,true);
		$criteria->compare('code',$this->code);
		$criteria->compare('message',$this->message,true);
		$criteria->compare('import_site_history_id',$this->import_site_history_id);
		$criteria->compare('modified_at',$this->modified_at,true);
		$criteria->compare('created_at',$this->created_at,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}