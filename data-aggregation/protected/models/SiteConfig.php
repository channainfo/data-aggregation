<?php

/**
 * This is the model class for table "da_site_configs".
 *
 * The followings are the available columns in table 'da_site_configs':
 * @property integer $id
 * @property string $code
 * @property string $name
 * @property string $host
 * @property string $user
 * @property string $password
 * @property string $db
 * @property string $created_at
 * @property string $modified_at
 * @property integer $status
 * @property datetime $last_imported
 * @property datetime $last_restored
 */
class SiteConfig extends DaModelStatus
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return SiteConfigs the static model class
	 */
  
  private $lastBackup = false;
  private $lastImport = false;
  
	public static function model($className=__CLASS__){
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName(){
		return 'da_siteconfigs';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules(){
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user, db, host ', 'required'),
      array("code", "length", "max" => 4),
			array('code, name, host, user, password, db', 'length', 'max'=>255),
			array('created_at, modified_at', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, code, name, host, user, password, db, created_at, modified_at', 'safe', 'on'=>'search'),
		);
	}
  /**
   * return array as key with site id and value with site code combined with site name
   * @return array 
   */
  public static function siteListBox(){
    $sites = SiteConfig::model()->findAll();
    $data = array();
    foreach($sites as $site){
      $data[$site->id] = "{$site->code} - {$site->name}" ; 
    }
    return $data;
  } 
  /**
   *
   * @param string $separator default is -
   * @return type return "{sitecode} {separator} {sitename}" 
   */
  public function fullName($separator="-"){
    return "{$this->code}".$separator."{$this->name}" ;
  }
  /**
   * @return boolean return if the status is PENDING
   */
  public function isImporting(){
    return SiteConfig::PENDING == $this->status ;
  }
  /**
   * @return boolean return if the status is START
   */
  public function isImportable(){   
    return SiteConfig::START == $this->status;
  }
  
  /**
   *
   * @param boolean $cache
   * @return Backup 
   */
  
  public function lastBackup($cache = true){
    if($cache == true && $this->lastBackup !== false)
      return $this->lastBackup;
    
    $model = new Backup();

    $criteria = new CDbCriteria();
    $criteria->order = " id DESC ";
    $criteria->condition = " siteconfig_id = " . $this->id; 
    
    $this->lastBackup = $model->findByAttributes(array(),$criteria);
    return $this->lastBackup ;
  }
  /**
   * 
   */
  public function updateSiteAttributes(){
    $db = DaDbMsSqlConnect::connect($this->host, $this->db, $this->user, $this->password);
    $table = "tblClinic";
    $sql = "SELECT TOP 1 * FROM {$table}   " ;
    $command = $db->createCommand($sql);
    try{
      $row = $command->queryRow();
      
      $this->name = trim($row["Clinic"]);
      $this->code = trim($row["ART"]);
      
      //Update site import and restore
      $this->status = SiteConfig::INIT ;
      $this->last_restored = DaDbWrapper::now();
      $this->last_imported = NULL ;
      $this->save();
    }
    
    
    
    catch(CException $ex){
      throw new DaInvalidSiteDatabaseException("Could not find any site from host: [{$this->host}] , db: [{$this->db}]  in table: [{$table}] ");
    }
    
  }
  
  
  /**
   *
   * @param boolean $cache
   * @return ImportSiteHistory
   */
  public function lastImport($cache = true){
    if($cache == true && $this->lastImport !== false)
      return $this->lastImport;
    
    $model = new ImportSiteHistory();

    $criteria = new CDbCriteria();
    $criteria->order = " id DESC ";
    $criteria->condition = " siteconfig_id = " . $this->id; 
    
    $this->lastImport = $model->findByAttributes(array(),$criteria);
    return $this->lastImport ;
  }

  /**
	 * @return array relational rules.
	 */
	public function relations(){
		return array(
        "backups" => array(self::HAS_MANY, "Backup", "siteconfig_id"),
        "importSiteHistorys" => array(self::HAS_MANY, "ImportSiteHistory", "siteconfig_id" ),
		);
	}
  
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels(){
		return array(
			'id' => 'ID',
			'code' => 'Code',
			'name' => 'Name',
			'host' => 'Host',
			'user' => 'User',
			'password' => 'Password',
			'db' => 'Database name',
			'created_at' => 'Created At',
			'modified_at' => 'Modified At',
		);
	}

}