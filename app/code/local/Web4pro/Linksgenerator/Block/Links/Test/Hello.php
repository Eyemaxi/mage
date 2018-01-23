<?php
/**
 * Created by PhpStorm.
 * User: dev01
 * Date: 06.12.17
 * Time: 15:58
 */

class Web4pro_Linksgenerator_Block_Links_Test_Hello extends Mage_Core_Block_Template {

    public function getHello(){
        echo get_class($this);
    }

}