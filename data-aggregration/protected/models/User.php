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
class User extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return User the static model class
	 */
 
  public static $ROLE = array("Admin", "Normal") ;
  public static $STATUS = array("Inactive", "Active");

  
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
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('login, group_id, name, password, active', 'required'),
      array('login, email', "unique"),   
      array('email', "email") ,  
      array('password', 'compare'), 
      array('password_repeat', 'safe'), //allow bulk assignment  
			array('group_id', 'numerical', 'integerOnly'=>true),
			array('login, password, email, salt, name', 'length', 'max'=>255),
			array('created_at, modified_at, last_login_at', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, login, group_id, password, email, salt, name, active, last_login_at', 'safe', 'on'=>'search'),
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
		);
	}
  
  protected function beforeValidate() {
    if(!$this->isNewRecord){
      $this->password_repeat = $this->password;
    }  
    return parent::beforeValidate();
    
  }
  protected function saltPassword($salt){
    return md5($salt);
  }

  protected function afterValidate() {

    if(!$this->hasErrors()){
      if($this->isNewRecord){
        $this->salt = $this->saltPassword( $this->login + "." + time());
        $this->created_at = $this->modified_at = CDbWrapper::now();
      }
      else
        $this->modified_at = CDbWrapper::now();
      
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
			'created_at' => 'Created At',
			'modified_at' => 'Modified At',
			'last_login_at' => 'Last Login At',
        
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('login',$this->login,true);
		$criteria->compare('group_id',$this->group_id);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('salt',$this->salt,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('created_at',$this->created_at,true);
		$criteria->compare('modified_at',$this->modified_at,true);
		$criteria->compare('last_login_at',$this->last_login_at,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}