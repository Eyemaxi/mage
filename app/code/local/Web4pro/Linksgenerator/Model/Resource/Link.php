<?php
class Web4pro_Linksgenerator_Model_Resource_Link extends Mage_Core_Model_Resource_Db_Abstract{


    public function _construct()
    {
        $this->_init('linksgenerator/link', 'link_id');
    }

}