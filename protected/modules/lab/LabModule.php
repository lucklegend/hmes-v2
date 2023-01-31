<?php

class LabModule extends CWebModule
{
	public $db;
	private $_assetsUrl;
	private $_labPriviledges;
	
	public function isLabAdmin()
	{
		$prev = Yii::app()->getModule('user')->getRoles();
		return $prev['Lab - System Manager'];
	}
	public function isAdmin()
	{
		$prev = Yii::app()->getModule('user')->getRoles();
		return $prev['Admin'];
	}
	
	/**
	* @return string the base URL that contains all published asset files of this module.
	*/
	public function getAssetsUrl()
	{
		if($this->_assetsUrl===null)
			$this->_assetsUrl=Yii::app()->getAssetManager()->publish(
				Yii::getPathOfAlias('lab.assets') );
		return $this->_assetsUrl;
	}
	
	/**
	* @param string the base URL that contains all published asset files of this module.
	*/
	public function setAssetsUrl($value)
	{
		$this->_assetsUrl=$value;
	}
	
	public function registerCss($file, $media='all')
	{
		$href = $this->getAssetsUrl().'/css/'.$file;
		return '<link rel="stylesheet" type="text/css" href="'.$href.'" media="'.$media.'" />';
	}
	
	public function registerImage($file)
	{
		return $this->getAssetsUrl().'/images/'.$file;
	}
	
	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		// import the module-level models and components
		$this->setImport(array(
			'lab.models.*',
			'lab.components.*',
		));
	}

	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action))
		{
			// this method is called before any module controller action is performed
			// you may place customized code here
			if(Yii::app()->controller->id == 'accomplishments' || Yii::app()->controller->id == 'statistic')
				$controller->layout = 'column1';
			else
				$controller->layout = 'column2';
				
			return true;
		}
		else
			return false;
	}
}
