<?php
class Web4pro_Linksgenerator_Model_Resource_Bind extends Mage_Core_Model_Resource_Db_Abstract
{
    public function _construct()
    {
        $this->_init('linksgenerator/bind', 'bind_id');
    }

    public function savePageRelations(Web4pro_Linksgenerator_Model_Link $link, array $pageIds)
    {
        if (!is_array($pageIds)) {
            $pageIds = array();
        }

        $bind = array(
            ':link_id' => (int)$link->getId()
        );

        $adapter = $this->_getWriteAdapter();

        $select = $adapter->select()
            ->from($this->getMainTable(), array('bind_id', 'page_id'))
            ->where('link_id = :link_id');

        $pagesInBind = $adapter->fetchPairs($select, $bind);

        $deleteIds = array();
        foreach ($pagesInBind as $bindId => $pageId) {
            if (!in_array($pageId, $pageIds, true)) {
                $deleteIds[] = (int)$bindId;
            }
        }

        if (!empty($deleteIds)) {
            $adapter->delete($this->getMainTable(), array(
                'bind_id IN (?)' => $deleteIds,
            ));
        }

        foreach ($pageIds as $pageId) {
            $bindId = null;
            if (in_array($pageId, $pagesInBind, true)) {
                $bindId = array_search($pageId, $pagesInBind);
                unset($pagesInBind[$bindId]);
            } else {
                $bind = array(
                    'link_id' => $link->getId(),
                    'page_id' => $pageId
                );
                $adapter->insert($this->getMainTable(), $bind);
                //$bindId = $adapter->lastInsertId($this->getMainTable());
            }
        }
        return $this;
    }
}