<?php

namespace OneytrustScore\Form;


use OneytrustScore\Config\OneytrustConst;
use OneytrustScore\OneytrustScore;
use Thelia\Form\BaseForm;

class OneytrustConfigurationForm extends BaseForm
{
    protected function buildForm()
    {
        $this->formBuilder
            ->add(OneytrustConst::ONEYTRUST_CONFIG_KEY_SHOP_NAME, "text", [
                'data' => OneytrustScore::getConfigValue(OneytrustConst::ONEYTRUST_CONFIG_KEY_SHOP_NAME)
            ])
            ->add(OneytrustConst::ONEYTRUST_CONFIG_KEY_PAYMENT_ALLOW_LIST, "text", [
                'data' => OneytrustScore::getConfigValue(OneytrustConst::ONEYTRUST_CONFIG_KEY_PAYMENT_ALLOW_LIST)
            ])
            ->add(OneytrustConst::ONEYTRUST_CONFIG_KEY_SITE_ID_CMC, "text", [
                'data' => OneytrustScore::getConfigValue(OneytrustConst::ONEYTRUST_CONFIG_KEY_SITE_ID_CMC)
            ])
            ->add(OneytrustConst::ONEYTRUST_CONFIG_KEY_SITE_ID_FAC, "text", [
                'data' => OneytrustScore::getConfigValue(OneytrustConst::ONEYTRUST_CONFIG_KEY_SITE_ID_FAC)
            ])
            ->add(OneytrustConst::ONEYTRUST_CONFIG_KEY_PAID_STATUS, "text", [
                'data' => OneytrustScore::getConfigValue(OneytrustConst::ONEYTRUST_CONFIG_KEY_PAID_STATUS)
            ])
            ->add(OneytrustConst::ONEYTRUST_CONFIG_KEY_HOST, "text", [
                'data' => OneytrustScore::getConfigValue(OneytrustConst::ONEYTRUST_CONFIG_KEY_HOST)
            ])
        ;
    }

    public function getName()
    {
        return "oneytrust_configuration_form";
    }
}