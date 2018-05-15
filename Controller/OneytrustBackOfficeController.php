<?php

namespace OneytrustScore\Controller;


use OneytrustScore\Config\OneytrustConst;
use OneytrustScore\EventListeners\OneytrustManager;
use OneytrustScore\Model\OneytrustQuery;
use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Core\Event\Order\OrderEvent;
use Thelia\Core\HttpFoundation\Response;
use Thelia\Core\Security\AccessManager;
use Thelia\Core\Security\Resource\AdminResources;
use Thelia\Log\Tlog;
use Thelia\Model\Order;
use Thelia\Model\OrderQuery;

class OneytrustBackOfficeController extends BaseAdminController
{

    /**
     * Redrects to the Oneytrust page of the order with autologin
     *
     * @return Response
     */
    public function viewOneytrustOrder()
    {
        $ref = $this->getRequest()->get('order_reference');

        $urlValidation = OneytrustConst::ONEYTRUST_VISUCHECK . OneytrustConst::SITE_ID_CMC;
        $urlValidation .= "/idfacs/" . OneytrustConst::SITE_ID_FAC;
        $urlValidation .= "/refids/" . $ref;

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

        return new Response($return);
    }

    public function reloadPayOrder()
    {
        Tlog::getInstance()->info("oneytrust log test");

        if (null !== $response = $this->checkAuth(array(AdminResources::MODULE), array('Oneytrust'), AccessManager::CREATE)) {
            return $response;
        }

        $log = Tlog::getNewInstance();
        $log->setDestinations("\\Thelia\\Log\\Destination\\TlogDestinationFile");
        $log->setConfig("\\Thelia\\Log\\Destination\\TlogDestinationFile", 0, THELIA_ROOT . "log" . DS . "log-oneytrust.txt");
        $log->error('----- Log Oneytrust -------');
        $log->error('---------------------------');

        $orders = OrderQuery::create()->filterByStatusId(2)->filterByPaymentModuleId(OneytrustConst::PAYMENT_ALLOW_LIST)->find();

        /** @var Order $order */
        foreach ($orders as $order) {

            if (null == $oneytrust = OneytrustQuery::create()->findOneByCommande($order->getRef())) {

                $orderEvent = new OrderEvent($order);
                $orderEvent->setPaymentModule($order->getPaymentModuleId());
                $orderEvent->setPlacedOrder($order);

                Tlog::getInstance()->error("#ONEYTRUST // La commande : " . $orderEvent->getOrder()->getRef() . "va être envoyée");

                (new OneytrustManager)->exportOneytrustScore($orderEvent);
            }
        }

        return $this->generateRedirectFromRoute('admin.order.list');
    }
}