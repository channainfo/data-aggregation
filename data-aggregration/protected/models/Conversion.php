<?php

/**
 * This is the model class for table "da_conversion".
 *
 * The followings are the available columns in table 'da_conversion':
 * @property integer $id
 * @property string $date_start
 * @property string $date_end
 * @property integer $status
 * @property integer $job_id
 * @property string $src
 * @property string $des
 * @property string $created_at
 * @property string $modified_at
 * @property string $message
 */
class Conversion extends DaModelStatus
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Conversion the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
  
  public function afterDelete() {
    DJJob::removeJob($this->job_id);
    
    if($this->src)
       @unlink(DaConfig::pathDataStore().$this->src);
    if($this->des)
       @unlink(DaConfig::pathDataStoreExport().$this->des);
    
    return parent::afterDelete();
  }

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'da_conversion';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
      array("src", "fileType", "type" => ".zip")  
		);
	}
  
  public function fileType($attribute, $params){
    $type = "/" . preg_quote($params["type"]). "$/i";
    $result = preg_match($type, $this->attributes[$attribute]);
    if(!$result){
      $this->addError($attribute, " must be in {$params["type"]} format");
      return false;
    }
    return true;
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
			'date_start' => 'Date Start',
			'date_end' => 'Date End',
			'status' => 'Status',
			'job_id' => 'Job',
			'src' => 'Src',
			'des' => 'Des',
			'created_at' => 'Created At',
			'modified_at' => 'Modified At',
		);
	}
}