<?php

/**
 * This is the model class for table "binding_account".
 *
 * The followings are the available columns in table 'binding_account':
 * @property integer $binding_account_id
 * @property integer $arq_id
 * @property string $third_party_id
 * @property string $third_party
 * @property string $date_created
 * @property string $date_modified
 */
class BindingAccount extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'binding_account';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('arq_id', 'length', 'max'=>500),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('arq_id, fb_id', 'safe', 'on'=>'search'),
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
			'binding_account_id' => 'Binding Account Id',
			'arq_id' => 'Arq Id',
			'third_party_id' => 'Third Party Id',
			'third_party' => 'Third Party',
			'date_created' => 'Date Created',
			'date_modified' => 'Date Modified',
		);
	}


	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Image the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

}
