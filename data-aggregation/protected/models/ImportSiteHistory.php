<?php

/**
 * This is the model class for table "da_import_site_histories".
 *
 * The followings are the available columns in table 'da_import_site_histories':
 * @property integer $id
 * @property integer $status
 * @property integer $siteconfig_id
 * @property double $duration
 * @property string $reason
 * @property string $modified_at
 * @property string $created_at
 * @property integer $job_id
 * @property string $info
 * @property string importing_table
 * @property integer total_record
 * @property integer current_record
 * @property integer importing_record
 * @property string site_code
 * @property string site_name
 *
 * The followings are the available model relations:
 * @property Siteconfigs $siteconfig
 */
class ImportSiteHistory extends DaModelStatus {
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ImportSiteHistory the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
  public function setImportingRecord($record){
    $this->importing_record = serialize($record);
  }
  public function getImportingRecord(){
    if($this->importing_record){
      return unserialize($this->importing_record);
    }
    return array();
  }
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'da_import_site_histories';
	}

  public function afterDelete() {
    DJJob::removeJob($this->job_id);
    return parent::afterDelete();
  }
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('siteconfig_id', 'required'),
			array('status, siteconfig_id', 'numerical', 'integerOnly'=>true),
			array('duration', 'numerical'),
			array('reason, modified_at, created_at', 'safe'),
		);
	}
  
  public function getInfo(){
    if($this->info){
      return unserialize($this->info);
    }
    return false;
  } 

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'siteconfig' => array(self::BELONGS_TO, 'SiteConfig', 'siteconfig_id'),
      'rejectConditions' => array(self::HAS_MANY, 'RejectCondition', 'import_site_history_id' )  
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'status' => 'Status',
			'siteconfig_id' => 'Siteconfig',
			'duration' => 'Duration',
			'reason' => 'Reason',
		);
	}
  /**
   *
   * @return boolean 
   */
  public function restorable(){
    return $this->status == self::START ;
  }
  
  public function inProgress(){
    if($this->status == self::PENDING )
      return true;
    return false;
  }
}