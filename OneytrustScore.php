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

    /** @var array  */
    private $paymentAllow = OneytrustConst::PAYMENT_ALLOW_LIST;

    /**
     * @param $ref
     * @return string
     */
    public static function analyse($ref)
    {
        $urlValidation = OneytrustConst::ONEYTRUST_GET_EVAL . OneytrustConst::SITE_ID_CMC . ".xml";
        $urlValidation .= "?RefID=" . urlencode($ref);
        $urlValidation .= "&siteidfac=" . OneytrustConst::SITE_ID_FAC;
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
    }

    /**
     * @return array
     */
    public function getPaymentAllow()
    {
        return $this->paymentAllow;
    }
}
