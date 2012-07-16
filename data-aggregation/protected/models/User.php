<?php

/**
 * This is the model class for table "tbl_users".
 *
 * The followings are the available columns in table 'tbl_users':
 * @property integer $id
 * @property string $login
 * @property integer $group_id
 * @property string $password
 * @property string $email
 * @property string $salt
 * @property string $name
 * @property string $created_at
 * @property string $modified_at
 * @property string $last_login_at
 * @property boolean $active
 */
class User extends DaActiveRecordModel
{
  public static $ROLE = array("Admin", "Normal") ;
  public static $STATUS = array("Inactive", "Active");
  const ADMIN = "1" ;
  const VIEWER = "2" ;
  const INACTIVE = 0;
  const ACTIVE = 1;
  
 
  /**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return User the static model class
	 */
  public $password_repeat = null ;
  public $pwdChanged = false ;
  
  
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'da_users';
	}
  
  public function getActive(){
    return User::$STATUS[$this->active];
  }
  
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('login, group_id, name,active', 'required'),
      array("password", "required",  "on" => "insert" ),
      array('login, email', "unique"),   
      array('email', "email") ,  
      array('password', 'compare', "on" => "insert" ), 
      array('password_repeat', 'safe'), //allow bulk assignment  
			array('group_id', 'numerical', 'integerOnly'=>true),
			array('login, password, email, salt, name', 'length', 'max'=>255),
		);
	}
  
  public function isAdmin(){
    return User::isUserAdmin($this->group_id); 
  }
  
  public function isViewer(){
    return User::isUserViewer($this->group_id) ; 
  }
  
  public static function isUserViewer($group){
    return User::VIEWER == $group ;
  }
  
  public static function isUserAdmin($group){
    return User::ADMIN == $group ;
  }
  

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
        "group" => array(self::BELONGS_TO, "Group", "group_id")
		);
	}
  
  protected function saltPassword($salt){
    return md5($salt);
  }
  
  public function setAttributes($attributes, $safe = true ) {
    foreach($attributes as $name => $value){
      $this->setAttribute($name, $value);
    }
    return true;
  }
  public function setAttribute($name, $value) {
    if($name == "password")
       $this->pwdChanged = true ;
    parent::setAttribute($name, $value);
  }

  protected function beforeSave() {
    if($this->isNewRecord){
        $this->password = $this->createPassword();
    }
    elseif($this->pwdChanged){
      $this->password = $this->createPassword();
      $this->modified_at = DaDbWrapper::now();
    }
    return parent::beforeSave();
  }
    
  public function createPassword(){
    $this->salt = $this->saltPassword($this->login .DaConfig::SALT);
    return $this->encrypt($this->password, $this->salt);
  } 
  
  public  function encrypt($rawPwd, $salt){
    return md5("{$rawPwd}.{$salt}");
  }

  /**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'login' => 'Login',
			'group_id' => 'Group',
			'password' => 'Password',
			'password_repeat' => 'Password Repeat',
      'email' => 'Email',
			'salt' => 'Salt',
			'name' => 'Name',
		);
	}	
}