<?php
class Web4pro_Linksgenerator_Model_Resource_Bind_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract {

    public function _construct()
    {
        parent::_construct();
        $this->_init('linksgenerator/bind');
    }

}