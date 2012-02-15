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
 
  /**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return User the static model class
	 */
  public $password_repeat = null ;
  
  
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
			array('login, group_id, name, password, active', 'required'),
      array('login, email', "unique"),   
      array('email', "email") ,  
      array('password', 'compare'), 
      array('password_repeat', 'safe'), //allow bulk assignment  
			array('group_id', 'numerical', 'integerOnly'=>true),
			array('login, password, email, salt, name', 'length', 'max'=>255),
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
        "group" => array(self::BELONGS_TO, "Group", "group_id")
		);
	}
  
  protected function beforeValidate() {  
    return parent::beforeValidate();
  }
  protected function saltPassword($salt){
    return md5($salt);
  }

  protected function afterValidate(){
    if(!$this->hasErrors()){
      if($this->isNewRecord){
        $this->salt = $this->saltPassword( $this->login + "." + time());
        //$this->created_at = $this->modified_at = DaDbWrapper::now();    
      }
      //else
      //  $this->modified_at = DaDbWrapper::now();
      $this->password = $this->encrypt($this->password, $this->salt);
    }
    
    return parent::afterValidate();
  }

  public  function encrypt($data, $salt){
    return md5("{$data}.{$salt}");
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