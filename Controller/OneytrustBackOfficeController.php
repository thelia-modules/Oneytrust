<?php

namespace OneytrustScore\Controller;


use OneytrustScore\Config\OneytrustConst;
use OneytrustScore\EventListeners\OneytrustManager;
use OneytrustScore\Model\OneytrustQuery;
use OneytrustScore\OneytrustScore;
use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Core\Event\Order\OrderEvent;
use Thelia\Core\HttpFoundation\Response;
use Thelia\Core\Security\AccessManager;
use Thelia\Core\Security\Resource\AdminResources;
use Thelia\Core\Translation\Translator;
use Thelia\Log\Tlog;
use Thelia\Model\Order;
use Thelia\Model\OrderQuery;

class OneytrustBackOfficeController extends BaseAdminController
{

    /**
     * Render the module config page
     *
     * @return \Thelia\Core\HttpFoundation\Response
     */
    public function viewAction()
    {
        return $this->render("OneytrustConfig");
    }

    /**
     * Save the values entered in the config form to the module config DB
     *
     * @return mixed|null|\Symfony\Component\HttpFoundation\Response|\Thelia\Core\HttpFoundation\Response
     */
    public function saveAction()
    {
        if (null !== $response = $this->checkAuth([AdminResources::MODULE], 'OneytrustScore', AccessManager::UPDATE)) {
            return $response;
        }

        $form = $this->createForm("oneytrust_configuration_form");

        try {
            $data = $this->validateForm($form)->getData();

            OneytrustScore::setConfigValue(OneytrustConst::ONEYTRUST_CONFIG_KEY_SHOP_NAME, $data[OneytrustConst::ONEYTRUST_CONFIG_KEY_SHOP_NAME]);
            OneytrustScore::setConfigValue(OneytrustConst::ONEYTRUST_CONFIG_KEY_PAYMENT_ALLOW_LIST, $data[OneytrustConst::ONEYTRUST_CONFIG_KEY_PAYMENT_ALLOW_LIST]);
            OneytrustScore::setConfigValue(OneytrustConst::ONEYTRUST_CONFIG_KEY_SITE_ID_CMC, $data[OneytrustConst::ONEYTRUST_CONFIG_KEY_SITE_ID_CMC]);
            OneytrustScore::setConfigValue(OneytrustConst::ONEYTRUST_CONFIG_KEY_SITE_ID_FAC, $data[OneytrustConst::ONEYTRUST_CONFIG_KEY_SITE_ID_FAC]);
            OneytrustScore::setConfigValue(OneytrustConst::ONEYTRUST_CONFIG_KEY_PAID_STATUS, $data[OneytrustConst::ONEYTRUST_CONFIG_KEY_PAID_STATUS]);
            OneytrustScore::setConfigValue(OneytrustConst::ONEYTRUST_CONFIG_KEY_HOST, $data[OneytrustConst::ONEYTRUST_CONFIG_KEY_HOST]);
        } catch (\Exception $e) {
            $this->setupFormErrorContext(
                Translator::getInstance()->trans(
                    "Error",
                    [],
                    OneytrustScore::DOMAIN_NAME
                ),
                $e->getMessage(),
                $form
            );

            return $this->viewAction();
        }
        return $this->generateSuccessRedirect($form);
    }

    /**
     * Redrects to the Oneytrust page of the order with autologin
     *
     * @return Response
     */
    public function viewOneytrustOrder()
    {
        $ref = $this->getRequest()->get('order_reference');

        $urlValidation = (new OneytrustConst())->getVisucheckUrl() . OneytrustScore::getConfigValue(OneytrustConst::ONEYTRUST_CONFIG_KEY_SITE_ID_CMC);
        $urlValidation .= "/idfacs/" . OneytrustScore::getConfigValue(OneytrustConst::ONEYTRUST_CONFIG_KEY_SITE_ID_FAC);
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

        $orders = OrderQuery::create()->filterByStatusId(2)->filterByPaymentModuleId(explode(",", OneytrustScore::getConfigValue(OneytrustConst::ONEYTRUST_CONFIG_KEY_PAYMENT_ALLOW_LIST)))->find();

        /** @var Order $order */
        foreach ($orders as $order) {

            /** @noinspection PhpParamsInspection */
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