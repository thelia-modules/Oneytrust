<?php

namespace OneytrustScore\Smarty\Plugins;

use Thelia\Core\Template\Smarty\AbstractSmartyPlugin;
use Thelia\Core\Template\Smarty\SmartyPluginDescriptor;


class OneyTrust extends AbstractSmartyPlugin
{

    /**
     * @return array of SmartyPluginDescriptor
     */
    public function getPluginDescriptors()
    {
        return array(

            new SmartyPluginDescriptor("function", "is_oneytrust_payment_allow", $this, "isPaymentAllow"),

        );
    }


    public function isPaymentAllow($moduleId)
    {
        return in_array($moduleId['moduleId'] ,(new \OneytrustScore\OneytrustScore())->getPaymentAllow());
    }

}
