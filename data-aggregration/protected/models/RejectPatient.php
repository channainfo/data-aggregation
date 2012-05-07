<?php

/**
 * This is the model class for table "da_reject_patients".
 *
 * The followings are the available columns in table 'da_reject_patients':
 * @property integer $id
 * @property string $tableName
 * @property string $name
 * @property integer $code
 * @property string $message
 * @property string $record
 * @property string $err_records
 * @property integer $import_site_history_id
 * @property string $modified_at
 * @property string $created_at
 */
class RejectPatient extends DaActiveRecordModel
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return RejectPatient the static model class
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
		return 'da_reject_patients';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('import_site_history_id', 'required'),
			array('code, import_site_history_id', 'numerical', 'integerOnly'=>true),
			array('tableName, name', 'length', 'max'=>255),
			array('message, record, modified_at, created_at', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, tableName, name, code, message, record, import_site_history_id, modified_at, created_at', 'safe', 'on'=>'search'),
		);
	}
  
  public function patientType(){
    $table = array("tblaimain" => "Adult", "tblcimain" => "Child", "tbleimain" => "Expo");
    if(isset($table[$this->tableName]))
      return $table[$this->tableName] ;
    return "";
  }
  
  public function errRecord(){
    if($this->err_record){
      return unserialize($this->err_record);
    }
    return array();
  }
  
  public function patientRecord(){
    if($this->record)
      return unserialize ($this->record);
    return array();
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
			'tableName' => 'Table Name',
			'name' => 'Name',
			'code' => 'Code',
			'message' => 'Message',
			'record' => 'Record',
			'import_site_history_id' => 'Import Site History',
			'modified_at' => 'Modified At',
			'created_at' => 'Created At',
		);
	}
}