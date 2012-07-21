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
 * @property integer $reject_type
 * @property string $modified_at
 * @property string $created_at
 */
class RejectPatient extends DaActiveRecordModel
{
  const TYPE_STRICT = 1;
  const TYPE_WARNING = 2 ;
  
  
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return RejectPatient the static model class
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
  
  public function getRejectTypeString(){
    return self::getRejectType($this->reject_type);  
  }
  
  public static function getRejectType($type){
    $types = array(self::TYPE_STRICT => "Rejected", self::TYPE_WARNING => "Warning");
     return $types[$type];
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
		);
	}
  
  
  public static function patientType($tableName){
    $table = array("tblaimain" => "Adult", "tblcimain" => "Child", "tbleimain" => "Expo");
    if(isset($table[$tableName]))
      return $table[$tableName] ;
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
      'reject_type' => "Reject Type" ,  
			'modified_at' => 'Modified At',
			'created_at' => 'Created At',
		);
	}
}