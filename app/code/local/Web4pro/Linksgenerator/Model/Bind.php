<?php
class Web4pro_Linksgenerator_Model_Bind extends Mage_Core_Model_Abstract {

    public function _construct()
    {
        parent::_construct();
        $this->_init('linksgenerator/bind');
    }

    public function saveLinksRelations(Web4pro_Linksgenerator_Model_Link $link)
    {
        $pageIds = $link->getPageIds();
        $this->_getResource()->savePageRelations($link, $pageIds);
    }
}