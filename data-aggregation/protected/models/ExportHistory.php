<?php
 Yii::import("application.vendors.*");
 require_once "djjob/DJJobConfig.php";
/**
 * This is the model class for table "da_export_history".
 *
 * The followings are the available columns in table 'da_export_history':
 * @property integer $id
 * @property string $date_start
 * @property string $date_end
 * @property integer $reversable
 * @property string $sites
 * @property integer $status
 * @property string $file
 * @property string $created_at
 * @property string $modified_at
 * @property string $table_list
 * @property integer $job_id
 * @property integer $all_site
 * @property integer $all_table
 * @property string $site_text
 * @property string $message
 * @property string $group
 * 
 * 
 */
class ExportHistory extends DaModelStatus{
	
  const NORMAL = 0 ;
  const ANONYM_REVERSABLE =1 ;
  const ANONYM_NOT_REVERSABLE = 2 ;
  
  
  /**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ExportHistory the static model class
	 */
  public static $REVERSABLE_TYPES = array(
     self::NORMAL => "Normal",
     self::ANONYM_REVERSABLE => "Anonymization Reversible",
     self::ANONYM_NOT_REVERSABLE => "Anonymization not reversible"  
   );
  
  
  public function setData($attrs){
       $tables = $attrs["table_list"]["tables"];
       $tableList = array();
       
       foreach($tables as $table => $value){
         $tableList[$table] = $attrs["table_list"]["columns"][$table];
       }
       if(isset($attrs["group"]))
          $this->group = md5($attrs["group"]);
       $this->reversable = $attrs["reversable"];
       $this->date_start = DaDbWrapper::now();
       $this->all_site   = $attrs["all_site"];
       $this->all_table  = $attrs["all_table"];
       $this->site_text  = implode( "<br /> ", $attrs["site_list"]);
       $this->setTableList($tableList);
       $this->setSites($attrs["site_list"]);
  }
  
  
  
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
  
  /**
   *  
   */
  public function getReversibleText(){
    return self::$REVERSABLE_TYPES[(int)$this->reversable];
  }
  
  public static function ReversableText($reversable){
    return self::$REVERSABLE_TYPES[(int)$reversable];
  }
  

	/**
	 * @return string the associated database table name
	 */
	public function tableName(){
		return 'da_export_history';
	}
  /**
   * 
   */
  public function djjobLink(){
      DJJob::enqueue(new DaExportSiteJob($this->id), DaConfig::QUEUE_CONVERSION_N_EXPORT );
      $job_id = DJJob::lastInsertedJob();
      $this->job_id = $job_id ;
      $this->status = ImportSiteHistory::START ;
      $this->save();
  }
  
  public function saveAsSeparate($params){
    if(isset($params["separate"]) && $params["separate"] == "1"){
      $params["all_site"] = 0 ;
      unset($params["separate"]);
      $attributes = $params;
      $result = false ;
      $group = rand(0, 5000).time();
      foreach($params["site_list"] as $index => $site){
        unset($attributes["site_list"]);
        $attributes["site_list"][$index] = $site ;
        $attributes["group"] = $group ;
        $exportHistory = new ExportHistory();
        $exportHistory->setData($attributes);
        $result = $exportHistory->save();
        if($result)
          $exportHistory->djjobLink();
      }
    }
    else{
        $exportHistory = new ExportHistory();
        $exportHistory->setData($params);
        $result = $exportHistory->save();
        $exportHistory->djjobLink();
    }
    return $result ;
  }
  
  public function setTableList($tableList){
    $tableList = serialize($tableList);
    $this->table_list = $tableList;
  }
  
  public function  getTableList(){
     if(!$this->attributes["table_list"])
       return false;
     return unserialize($this->attributes["table_list"]);
       
  }
  
  public function setSites($sites){
    $sites = serialize($sites);
    $this->sites =  $sites;
  }
  /**
   *
   * @return array 
   */
  public function  getSites(){
    $sites = array();
    if($this->attributes["sites"]){
     $sites = unserialize($this->attributes["sites"]);
    }
    return $sites;
  }
  /**
   *
   * @param type $glue
   * @return type 
   */
  public function getSiteText($glue=""){
     $sites = $this->getSites(); 
     return implode($glue , $sites);
  }

  /**
   * return array of table with key as table name and value as an array of its columns
   * @return array 
   */
  public static function tableList(){
    $configs = DaConfig::importConfig();
    $tables = array_merge($configs["tables"], $configs["fixed"]);
    return $tables ;
  }
  
  public function afterDelete() {
    DJJob::removeJob($this->job_id);
    if($this->file)
       @unlink(DaConfig::pathDataStoreExport().$this->file);
    return parent::afterDelete();
  } 

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules(){
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('reversable', 'numerical', 'integerOnly'=>true),
			array('status, file', 'length', 'max'=>255),
			array('date_start, date_end, site_text', 'safe'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations(){
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels(){
		return array(
			'id' => 'ID',
			'date_start' => 'Date Start',
			'date_end' => 'Date End',
			'reversable' => 'Export type',
			'sites' => 'Site',
			'status' => 'Status',
			'file' => 'File',
			'created_at' => 'Created At',
			'modified_at' => 'Modified At',
      'all_site' => 'Site to export',
      'all_table' => 'Table to export'  
        
		);
	}
}