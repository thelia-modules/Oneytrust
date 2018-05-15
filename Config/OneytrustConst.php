<?php

namespace OneytrustScore\Config;


class OneytrustConst
{
    /** Name of the shop */
    const SHOP_NAME                 = "Your shop name here";

    /** List of payment module IDs to be accepted by OneyTrust */
    const PAYMENT_ALLOW_LIST        = [30,8,9,3,89];

    /** Oneytrust client unique ID  */
    const SITE_ID_FAC               = "0000";

    /** Oneytrust shop ID */
    const SITE_ID_CMC               = "0000";

    /** Oneytrust host */
    const ONEYTRUST_HOST            = "api.sellsecure.com";                                 //PROD Settings
    //const ONEYTRUST_HOST            = "api-ppr.sellsecure.com";                           //PRE-PROD Settings

    /** Oneytrust post prescore URL -- UNUSED */
    const ONEYTRUST_POST_PRESCORE   = "https://api.sellsecure.com/prescores/";              //PROD Settings
    //const ONEYTRUST_POST_PRESCORE   = "https://api-ppr.sellsecure.com/prescores/";        //PRE-PROD Settings

    /** Oneytrust post score URL */
    const ONEYTRUST_SCORE           = "https://api.sellsecure.com/stacks/";                 //PROD Settings
    //const ONEYTRUST_SCORE           = "https://api-ppr.sellsecure.com/stacks/";           //PRE-PROD Settings

    /** Oneytrust get evaluation URL */
    const ONEYTRUST_GET_EVAL        = "https://api.sellsecure.com/idcmcs/";                 //PROD Settings
    //const ONEYTRUST_GET_EVAL        = "https://api-ppr.sellsecure.com/idcmcs/";           //PRE-PROD Settings

    /** Oneytrust Visucheck URL  */
    const ONEYTRUST_VISUCHECK       = "https://api.sellsecure.com/record/idcmcs/";          //PROD Settings
    //const ONEYTRUST_VISUCHECK       = "https://api-ppr.sellsecure.com/record/idcmcs/";    //PRE-PROD Settings
}