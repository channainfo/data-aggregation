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
class Backup extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Backup the static model class
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
		return 'da_backups';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('filename, status, siteconfig_id', 'required'),
			array('filename', 'length', 'max'=>255),
      array("filename","file", "types" => "bak")  
		);
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