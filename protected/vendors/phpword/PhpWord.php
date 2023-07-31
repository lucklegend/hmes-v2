<?php
Yii::import('application.extensions.phpword.src.PhpWord');
spl_autoload_unregister(array('YiiBase','autoload'));
Yii::import('ext.phpword.src.PhpWord', true);
$PHPWord = new PHPWord();
spl_autoload_register(array('YiiBase','autoload'));
class PhpWord extends PhpWord
{
    // Add any customizations or additional code you may need
    
}