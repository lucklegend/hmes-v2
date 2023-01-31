<?php
class Xtable extends CFormModel
{
    public $id;
    public $lastName;
    public $firstName;
    public $mi;

    public function rules()
    {
        return array(
            array('lastName, firstName, mi', 'required'),
            array('lastName, firstName', 'length', 'max'=>25),
            array('mi', 'length', 'max'=>2),
        );
    }
    
    public function attributeLabels()
    {
        return array(
            'id'=>'ID',
            'lastName'=>'Last Name',
            'firstName'=>'First Name',
            'mi'=>'Middle Initial',
        );
    }
           
}
