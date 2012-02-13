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
 */
class SiteConfig extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return SiteConfigs the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'da_site_configs';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('code, user, db, host', 'required'),
      array("code", "length","max" => 4),
			array('code, name, host, user, password, db', 'length', 'max'=>255),
			array('created_at, modified_at', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, code, name, host, user, password, db, created_at, modified_at', 'safe', 'on'=>'search'),
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

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'code' => 'Code',
			'name' => 'Name',
			'host' => 'Host',
			'user' => 'User',
			'password' => 'Password',
			'db' => 'Db',
			'created_at' => 'Created At',
			'modified_at' => 'Modified At',
		);
	}
}