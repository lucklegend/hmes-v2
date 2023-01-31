<?php

class EquipmentProfile extends Profile
{
	
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ulimsportal.profiles';
	}

	public function getFullName()
	{
		return $this->firstname.' '.$this->mi.' '.$this->lastname;
		
	}

	
}