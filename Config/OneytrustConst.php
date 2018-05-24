<?php

namespace OneytrustScore\Config;


use OneytrustScore\OneytrustScore;

class OneytrustConst
{
    /** Key for the name of the shop */
    const ONEYTRUST_CONFIG_KEY_SHOP_NAME                = "oneytrust_shop_name";

    /** Key for the list of payment module IDs to be accepted by OneyTrust */
    const ONEYTRUST_CONFIG_KEY_PAYMENT_ALLOW_LIST       = "oneytrust_payment_list";

    /** Oneytrust client unique ID key  */
    const ONEYTRUST_CONFIG_KEY_SITE_ID_FAC              = "oneytrust_site_id_fac";

    /** Oneytrust shop ID key */
    const ONEYTRUST_CONFIG_KEY_SITE_ID_CMC              = "oneytrust_site_id_cmc";

    /** Thelia paid status ID key  */
    const ONEYTRUST_CONFIG_KEY_PAID_STATUS              = "oneytrust_paid_status";

    /** Oneytrust Host Url key  */
    const ONEYTRUST_CONFIG_KEY_HOST                     = "oneytrust_host";

    /** Oneytrust post prescore URL -- UNUSED */
    public function postPreScoreUrl ()
    {
        return "https://" . OneytrustScore::getConfigValue(OneytrustConst::ONEYTRUST_CONFIG_KEY_HOST) . "/prescores/";
    }

    /** Oneytrust post score URL */
    public function postScoreUrl ()
    {
        return "https://" . OneytrustScore::getConfigValue(OneytrustConst::ONEYTRUST_CONFIG_KEY_HOST) . "/stacks/";
    }

    /** Oneytrust get evaluation URL */
    public function getEvalUrl ()
    {
        return "https://" . OneytrustScore::getConfigValue(OneytrustConst::ONEYTRUST_CONFIG_KEY_HOST) . "/idcmcs/";
    }

    /** Oneytrust Visucheck URL  */
    public function getVisucheckUrl ()
    {
        return "https://" . OneytrustScore::getConfigValue(OneytrustConst::ONEYTRUST_CONFIG_KEY_HOST) . "/record/idcmcs/";
    }
}