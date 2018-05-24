<?php
/*************************************************************************************/
/*      This file is part of the Thelia package.                                     */
/*                                                                                   */
/*      Copyright (c) OpenStudio                                                     */
/*      email : dev@thelia.net                                                       */
/*      web : http://www.thelia.net                                                  */
/*                                                                                   */
/*      For the full copyright and license information, please view the LICENSE.txt  */
/*      file that was distributed with this source code.                             */
/*************************************************************************************/

namespace OneytrustScore;

use OneytrustScore\Config\OneytrustConst;
use OneytrustScore\Model\OneytrustQuery;
use Propel\Runtime\Connection\ConnectionInterface;
use Thelia\Install\Database;
use Thelia\Module\BaseModule;

class OneytrustScore extends BaseModule
{
    /** @var string */
    const DOMAIN_NAME = 'oneytrustscore';

    /**
     * @param $ref
     * @return string
     */
    public static function analyse($ref)
    {
        $urlValidation = (new OneytrustConst())->getEvalUrl() . OneytrustScore::getConfigValue(OneytrustConst::ONEYTRUST_CONFIG_KEY_SITE_ID_CMC) . ".xml";
        $urlValidation .= "?RefID=" . urlencode($ref);
        $urlValidation .= "&siteidfac=" . OneytrustScore::getConfigValue(OneytrustConst::ONEYTRUST_CONFIG_KEY_SITE_ID_FAC);
        $urlValidation .= "&motifs=1";

        $process = curl_init($urlValidation);

        curl_setopt_array(
            $process,
            [
                CURLOPT_RETURNTRANSFER  => TRUE,
                CURLOPT_CONNECTTIMEOUT  => 30,
                CURLOPT_TIMEOUT         => 30,
            ]
        );

        $return = curl_exec($process);
        curl_close($process);

        return utf8_encode($return);
    }

    /**
     * @param ConnectionInterface|null $con
     */
    public function postActivation(ConnectionInterface $con = null)
    {
        try {
            OneytrustQuery::create()->findOne();
        } catch (\Exception $e) {
            $database = new Database($con);
            $database->insertSql(null, array(__DIR__ . '/Config/thelia.sql'));
        }

        /** Check if lcv_ticket_number exists, if not : creates it with a default value */
        if (null === OneytrustScore::getConfigValue(OneytrustConst::ONEYTRUST_CONFIG_KEY_HOST)) {
            OneytrustScore::setConfigValue(OneytrustConst::ONEYTRUST_CONFIG_KEY_HOST, "api-ppr.sellsecure.com");
        }
    }

    /**
     * @return array
     */
    public function getPaymentAllow()
    {
        return explode(",", OneytrustScore::getConfigValue(OneytrustConst::ONEYTRUST_CONFIG_KEY_PAYMENT_ALLOW_LIST));
    }
}
