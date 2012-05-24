<?php

/**
 * This is the model class for table "da_djjobs".
 *
 * The followings are the available columns in table 'da_djjobs':
 * @property integer $id
 * @property string $handler
 * @property string $queue
 * @property integer $attempts
 * @property string $run_at
 * @property string $locked_at
 * @property string $locked_by
 * @property string $failed_at
 * @property string $error
 * @property string $created_at
 */
class DJJob extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return DJJob the static model class
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
		return 'da_djjobs';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('attempts', 'numerical', 'integerOnly'=>true),
			array('handler, queue, locked_by', 'length', 'max'=>255),
			array('run_at, locked_at, failed_at, error, created_at', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, handler, queue, attempts, run_at, locked_at, locked_by, failed_at, error, created_at', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'handler' => 'Handler',
			'queue' => 'Queue',
			'attempts' => 'Attempts',
			'run_at' => 'Run At',
			'locked_at' => 'Locked At',
			'locked_by' => 'Locked By',
			'failed_at' => 'Failed At',
			'error' => 'Error',
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
		$criteria->compare('handler',$this->handler,true);
		$criteria->compare('queue',$this->queue,true);
		$criteria->compare('attempts',$this->attempts);
		$criteria->compare('run_at',$this->run_at,true);
		$criteria->compare('locked_at',$this->locked_at,true);
		$criteria->compare('locked_by',$this->locked_by,true);
		$criteria->compare('failed_at',$this->failed_at,true);
		$criteria->compare('error',$this->error,true);
		$criteria->compare('created_at',$this->created_at,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}