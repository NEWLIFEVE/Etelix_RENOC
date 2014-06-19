<?php

/**
 * This is the model class for table "profiles_renoc".
 *
 * The followings are the available columns in table 'profiles_renoc':
 * @property integer $id
 * @property string $first_name
 * @property string $last_name
 * @property integer $id_users_renoc
 *
 * The followings are the available model relations:
 * @property UsersRenoc $idUsersRenoc
 */
class ProfilesRenoc extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ProfilesRenoc the static model class
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
		return 'profiles_renoc';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_users_renoc', 'numerical', 'integerOnly'=>true),
			array('first_name, last_name', 'length', 'max'=>128),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, first_name, last_name, id_users_renoc', 'safe', 'on'=>'search'),
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
			'idUsersRenoc' => array(self::BELONGS_TO, 'UsersRenoc', 'id_users_renoc'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'first_name' => 'First Name',
			'last_name' => 'Last Name',
			'id_users_renoc' => 'Id Users Renoc',
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
		$criteria->compare('first_name',$this->first_name,true);
		$criteria->compare('last_name',$this->last_name,true);
		$criteria->compare('id_users_renoc',$this->id_users_renoc);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}