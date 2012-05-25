<?php

/**
 * This is the model class for table "da_backups".
 *
 * The followings are the available columns in table 'da_backups':
 * @property integer $id
 * @property string $filename
 * @property integer $status
 * @property integer $siteconfig_id
 * @property string $modified_at
 * @property string $created_at
 */
class Backup extends DaModelStatus
{
  /**
   *
   * @return boolean 
   */
  public function restorable(){
    return $this->status == self::START ;
  }
  
	public static function model($className=__CLASS__){
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName(){
		return 'da_backups';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('filename, siteconfig_id', 'required'),
			array('filename', 'length', 'max'=>255),
      array("filename, siteconfig_id, status", "safe" ),
      array("filename", "fileType", "type" => ".bak")  
      //array("filename","file", "types" => "bak")  
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
  
  public function afterDelete() {
    if($this->filename)
       @unlink(DaConfig::pathDataStore().$this->filename);
    return parent::afterDelete();
  }
  
  /**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
        "siteConfig" => array(self::BELONGS_TO, "SiteConfig", "siteconfig_id")
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'filename' => 'Filename',
			'status' => 'Status',
			'siteconfig_id' => 'Site',
		);
	}
}