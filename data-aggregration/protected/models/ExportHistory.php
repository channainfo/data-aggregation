<?php

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
 * 
 * 
 */
class ExportHistory extends DaModelStatus{
	
  public $listSites = array()  ;
  
  
 
  
  /**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ExportHistory the static model class
	 */
  public static $REVERSABLE_TYPES = array(
     0 => "Normal",
     1 => "Anonymization Reversable",
     2 => "Anonymization not reversable"  
   );
  
  
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
  
  /**
   *  
   */
  public function getReversableText(){
    return self::$REVERSABLE_TYPES[(int)$this->reversable];
  }
  

	/**
	 * @return string the associated database table name
	 */
	public function tableName(){
		return 'da_export_history';
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
    if(empty($this->listSites)){
     if(empty($this->attributes["sites"]))
        return $this->listSites;
     
     $sites = unserialize($this->attributes["sites"]);
     
     if(count($sites)){
        $ids = array() ;
        foreach($sites as $siteId => $enable)
          $ids[] = $siteId ;
        
        if(count($ids))
           $this->listSites = SiteConfig::model()->findAllByPk($ids);
     }
    }
    return $this->listSites ;
  }
  /**
   *
   * @param type $glue
   * @return type 
   */
  public function getSiteText($glue=""){
     $this->getSites(); // get and load site ;
     return $this->_calSiteText($glue);
  }
  
//  public function beforeSave() {
//    $this->site_text = $this->getStatusText("<br />");
//    return parent::beforeSave();
//  }
  
  public function _calSiteText($glue = " "){
     $siteStr = array() ;
     if(!empty($this->listSites)){
       foreach($this->listSites as $site){
         $siteStr[] = "{$site->code} - {$site->name}" ;
       }
     }
     return implode($glue , $siteStr );
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