<?php

/**
 * This is the model class for table "fundings".
 *
 * The followings are the available columns in table 'fundings':
 * @property integer $ID
 * @property string $name
 * @property string $code
 */
class EquipmentUser extends User
{
	/**
	 * @return CDbConnection the database connection used for this class
	 */
	public function tableName()
	{
		return 'ulimsportal.users';
	}

	public function relations()
	{	
       return array(
			'profile'=> array(self::HAS_ONE, 'EquipmentProfile', 'user_id'),
		);
	}

	
}
