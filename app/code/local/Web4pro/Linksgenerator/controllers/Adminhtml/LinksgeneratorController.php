<?php
class Web4pro_Linksgenerator_Adminhtml_LinksgeneratorController extends Mage_Adminhtml_Controller_Action {

    public function indexAction()
    {
        $this->loadLayout();
        $this->_setActiveMenu('linksgenerator');

        //echo $contentBlock = $this->getLayout()->createBlock('linksgenerator/adminhtml_linksgenerator')->toHtml();
        //$this->_addContent($contentBlock);
        $this->renderLayout();

        //echo '<h1>It is Module</h1>';
    }

    public function newAction() {

        $this->_forward('edit');
    }

    public function editAction()
    {
        $id = (int) $this->getRequest()->getParam('id');
        $model = Mage::getModel('linksgenerator/link');

        if($data = Mage::getSingleton('adminhtml/session')->getFormData()){
            $model->setData($data)->setId($id);
        } else {
            $model->load($id);
        }

        Mage::register('current_linksgenerator', $model);

        $this->loadLayout()->_setActiveMenu('linksgenerator');
        $this->_addLeft($this->getLayout()->createBlock('linksgenerator/adminhtml_linksgenerator_edit_tabs'));
        $this->_addContent($this->getLayout()->createBlock('linksgenerator/adminhtml_linksgenerator_edit'));
        $this->renderLayout();
    }

    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost()) {
            $linkId = $this->getRequest()->getParam('id');
            $linkText = $data['link_text'];
            $linkState = $data['link_state'];

            $arrPageIds = $data['page_ids'];

            /**
             * Save LinksGenerator Table
             */
            if (!empty($linkId)) {
                $tableLinks = array('link_id' => $linkId, 'link_text' => $linkText, 'link_state' => $linkState);
            } else {
                $tableLinks = array('link_text' => $linkText, 'link_state' => $linkState);
            }
            /** @var Web4pro_Linksgenerator_Model_Link $linkEntity */
            $linkEntity = Mage::getModel('linksgenerator/link')->setData($tableLinks);
            $linkEntity->setPageIds($arrPageIds);

            $linkEntity->save();

            /**
             * Save Bind Table (`link_page`)
             *
             * Give `bind_id` and `page_id`
             */

            /*$linkId = $linkEntity->getId();
            $collection_bind = Mage::getModel('linksgenerator/bind')->getCollection();
            $collection_bind->addFieldToFilter('link_id', $linkId);
            $arrBindIdToPageId = [];

            foreach ($collection_bind as $bindItem){
                    $arrBindIdToPageId[$bindItem->getBindId()] = $bindItem->getPageId();
            }

            if(!empty($arrPageIds)) {
                $arrBindsIdDelete = array_diff($arrBindIdToPageId, $arrPageIds);
                $arrBindsIdAdd = array_diff($arrPageIds, $arrBindIdToPageId);
            }
            else {
                $arrBindsIdDelete = $arrBindIdToPageId;
            }
            //$arrBindsIdUpdate = array_intersect($arrPageIds, $arrBindIdToPageId);

            /**
             * Foreach, who add news pages
             */
            /*foreach ($arrBindsIdAdd as $pageId) {
                    $tableBind = array('link_id' => $linkId, 'page_id' => $pageId);

                    $model = Mage::getModel('linksgenerator/bind')->setData($tableBind);

                    $model->save();
            }

            /**
             * Foreach, who delete pages
             */
            /*foreach ($arrBindsIdDelete as $bindId => $pageId) {
                //$tableBind = array( 'link_id' => $linkId, 'page_id' => $pageId);

                $model = Mage::getModel('linksgenerator/bind')->setId($bindId);

                $model->delete();
            }*/

            $this->_redirect('*/*/');


            /*try {
                $model = Mage::getModel('linksgenerator/link');
                $model->setData($data)->setId($this->getRequest()->getParam('id'));

                $model->save();

                Mage::getSingleton('adminhtml/session')->addSuccess($this->__('Link was saved successfully'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);*/
            #$this->_redirect('*/*/');
            /*} catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);*/
            #$this->_redirect('*/*/edit', array(
            /*'id' => $this->getRequest()->getParam('id')
        ));*/
        }
        //return;
        //}
        #Mage::getSingleton('adminhtml/session')->addError($this->__('Unable to find item to save'));
        #$this->_redirect('*/*/');
    }

    public function deleteAction()
    {
        if ($id = $this->getRequest()->getParam('id')) {
            try {
                Mage::getModel('linksgenerator/link')->setId($id)->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess($this->__('Link was deleted successfully'));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $id));
            }
        }
        $this->_redirect('*/*/');
    }

    public function massDeleteAction(){  }


}