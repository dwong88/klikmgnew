<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ActiveRecord
 * Develop by Wnw Development Team.
 * For customize CActiveRecord
 *
 */
class ActiveRecord extends CActiveRecord
{
    /**
     * turn this on at inherited class for log user when accessing this record.
     * @var boolean a flag for log create_by,create_dt,update_by,update_dt of 1 single record.
     */
    protected $logRecord=false;
    
    public function __construct($scenario = 'insert')
	{
        parent::__construct($scenario);
        $this->attachEventHandler('onBeforeSave', '');
        // $this->attachEventHandler('onAfterSave', '');
    }

    /**
     * By provide '$this->attachEventHandler('onBeforeSave', '');' at inherited class model,
     * this function will run before the record save to database.
     * By setting {@link CModelEvent::isValid} to be false, the normal {@link save()} process will be stopped.
     * @param CModelEvent $event the event parameter.
     */
    public function onBeforeSave($event)
	{
        //Don't provide 'parent::onBeforeSave($event);' at this function.
        //Because it will call this function multiple times.
        if($this->logRecord)
        {
            if($this->getIsNewRecord())
            {
                if($this->hasAttribute('create_dt'))
                {
                    $this->setAttribute('create_dt', Yii::app()->datetime->getDateTimeNow());
                }
                if($this->hasAttribute('update_dt'))
                {
                    $this->setAttribute('update_dt', Yii::app()->datetime->getDateTimeNow());
                }
            }
            else
            {
                if($this->hasAttribute('update_dt'))
                {
                    $this->setAttribute('update_dt', Yii::app()->datetime->getDateTimeNow());
                }
            }
        }
    }
	
	public function getTotal0($models, $attributeName)
	{
		$format = new Formatter();
		$total = 0;
		foreach ($models as $transaction) {
			$total += $transaction->$attributeName;
		}
		return $format->formatNumber0($total);
	}
}
?>
