<?php
/**
 * Created by PhpStorm.
 * User: dev01
 * Date: 06.12.17
 * Time: 15:58
 */

class Web4pro_Linksgenerator_Block_Links extends Mage_Core_Block_Template {

    /**
     * @return Web4pro_Linksgenerator_Model_Resource_Link_Collection
     */
    public function getCollectionLinks()
    {
        $cmsPageId = $this->getRequest()
            ->getParam('page_id');
        //TODO: get links assigned to $cmsPageId

        $collection_bind = Mage::getModel('linksgenerator/bind')->getCollection();
        $collection_bind->addFieldToFilter('page_id', $cmsPageId);

        $arrLinkIds = [];

        foreach ($collection_bind as $bindItem){
            array_push($arrLinkIds, $bindItem->getLinkId());
        }

        $collection_link = Mage::getModel('linksgenerator/link')->getCollection();
        $collection_link->addFieldToFilter('link_id', $arrLinkIds)
            ->addFieldToFilter('link_state', 1);

        return $collection_link;
    }
}