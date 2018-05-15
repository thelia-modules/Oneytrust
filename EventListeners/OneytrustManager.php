<?php

namespace OneytrustScore\EventListeners;


use OneytrustScore\Config\OneytrustConst;
use OneytrustScore\Model\Oneytrust as OneytrustModel;
use OneytrustScore\Model\OneytrustQuery;
use SoColissimo\Model\OrderAddressSocolissimoQuery;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Thelia\Core\Event\Order\OrderEvent;
use Thelia\Core\Event\TheliaEvents;
use Thelia\Log\Tlog;
use Thelia\Model\CountryQuery;
use Thelia\Model\ModuleQuery;
use Thelia\Model\Order;
use Thelia\Model\OrderAddressQuery;
use Thelia\Model\OrderProduct;

class OneytrustManager implements EventSubscriberInterface
{
    /**
     * Returns an array of event names this subscriber wants to listen to
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return array(
          TheliaEvents::ORDER_UPDATE_STATUS => array(
              'exportOneytrustScore',120
          ),
            TheliaEvents::ORDER_AFTER_CREATE => [
                'saveCustomerIp',128
            ]
        );
    }

    /**
     * Save customer's IP to use in XML
     *
     * @param OrderEvent $orderEvent
     * @throws \Exception
     */
    public function saveCustomerIp(OrderEvent $orderEvent)
    {
        try {
            // Create new Oneytrust data to remember customer IP for current order
            $oneytrustScore = new OneytrustModel();
            $oneytrustScore
                ->setCommande($orderEvent->getOrder()->getRef())
                ->setCustomerip($_SERVER['REMOTE_ADDR'])
                ->save();
        } catch (\Exception $e) {
            Tlog::getInstance()->addError("#ONEYTRUST //" . $e->getMessage());
        }
    }

    private function getPrice(OrderProduct $product)
    {
        if ($product->getWasInPromo()) {
            return $product->getPromoPrice();
        }
        return $product->getPrice();
    }

    private function getCountryIso($countryId)
    {
        return CountryQuery::create()->findOneById($countryId)->getIsoalpha2();
    }

    /** @todo */
    private function countryIsGranted()
    {
        return true;
    }

    private function paymentModuleIsGranted($moduleId)
    {
        $log = Tlog::getNewInstance();
        $log->setDestinations("\\Thelia\\Log\\Destination\\TlogDestinationFile");
        $log->setConfig("\\Thelia\\Log\\Destination\\TlogDestinationFile", 0, THELIA_ROOT . 'log' . DS . 'log-oneytrust.txt');
        $log->error("ONEYTRUST // Payment module " . $moduleId . "");

        /** Accepted payment module IDs. Change accordingly to your liking and to your website */
        if ((in_array($moduleId, OneytrustConst::PAYMENT_ALLOW_LIST)) == true) {
            return true;
        }
        return false;
    }

    private function isAllow(OrderEvent $orderEvent)
    {
        return ($this->paymentModuleIsGranted($orderEvent->getOrder()->getPaymentModuleId()) && $this->countryIsGranted());
    }

    /**
     * Select the type of delivery according to the module (Messy, could not find a better way to do it)
     *
     * @param $deliveryModule
     * @param $deliveryOrder
     * @return int
     */
    private function getDeliveryType($deliveryModule, $deliveryOrder)
    {
        if (null !== $deliveryModule) {
            if ($deliveryModule->getCode() == "DpdPickup") {
                $deliveryType = 2;
            } elseif ($deliveryModule->getCode() == "Colissimo") {
                $deliveryType = 4;
            } elseif ($deliveryModule->getCode() == "SoColissimo") {
                $ColissimoType = OrderAddressSocolissimoQuery::create()->findOneById($deliveryOrder->getId());
                if ($ColissimoType->getType() == "DOM") {
                    $deliveryType = 4;
                } elseif ($ColissimoType->getType() == "A2P") {
                    $deliveryType = 2;
                } elseif ($ColissimoType->getType() == "BPR") {
                    $deliveryType = 2;
                } else {
                    $deliveryType = 4;
                }
            } elseif ($deliveryModule->getCode() == "LocalPickup") {
                $deliveryType = 1;
            } else {
                $deliveryType = 4; //Unknown module : Putting a basic default type value
            }
        } else {
            $deliveryType = 4; //No delivery module found, putting basic default values instead
        }

        return $deliveryType;
    }

    /**
     * Send the XML flux to Oneytrust Score
     *
     * @param $exportXML
     * @param $reference
     */
    private function sendExport($exportXML, $reference)
    {
        $query="siteidfac=".OneytrustConst::SITE_ID_FAC;
        $query.="&siteidcmc=".OneytrustConst::SITE_ID_CMC;
        $query.="&controlcallback=".rawurlencode($exportXML);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => OneytrustConst::ONEYTRUST_SCORE . OneytrustConst::SITE_ID_CMC . ".xml",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $query,
            CURLOPT_HTTPHEADER => array(
                "Cache-Control: no-cache",
                "Content-Type: application/x-www-form-urlencoded"
            ),
        ));

        $body = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        $log = Tlog::getNewInstance();
        $log->setDestinations("\\Thelia\\Log\\Destination\\TlogDestinationFile");
        $log->setConfig("\\Thelia\\Log\\Destination\\TlogDestinationFile", 0, THELIA_ROOT . "log" . DS . "log-oneytrust.txt");

        if ($err) {
            $log->error("cURL Error #:" . $err);
            //Response
            echo "cURL Error #:" . $err;
        } else {
            $log->error("#ONEYTRUST // Réponse d'Oneytrust : ".$body."");
            $log->error($query);
            //Response
            echo $body;
        }

    }

    /**
     * Write the XML file to be sent to the Oneytrust Scoring Server
     *
     * @param OrderEvent $orderEvent
     * @return string
     * @throws \Propel\Runtime\Exception\PropelException
     */
    private function writeXML(OrderEvent $orderEvent)
    {
        /** @var Order $order */
        try {
            $order = $orderEvent->getPlacedOrder();
        } catch (\Exception $e) {
            $order = $orderEvent->getOrder();
        }

        $log = Tlog::getNewInstance();
        $log->setDestinations("\\Thelia\\Log\\Destination\\TlogDestinationFile");
        $log->setConfig("\\Thelia\\Log\\Destination\\TlogDestinationFile", 0, THELIA_ROOT . "log" . DS . "log-oneytrust.txt");
        $log->error("#ONEYTRUST // Réf de l'order : " . $order->getRef()."");

        /** @var  $customer */
        $customer = $order->getCustomer();

        /** @var  $address */
        $address = $customer->getDefaultAddress();

        /** @var  $deliveryOrder */
        $deliveryOrder = OrderAddressQuery::create()->findPk($order->getDeliveryOrderAddressId());

        /** @var  $invoiceOrder */
        $invoiceOrder = OrderAddressQuery::create()->findPk($order->getInvoiceOrderAddressId());

        /** @var  $customerTitleFr */
        $customerTitleFr = strtoupper($customer->getCustomerTitle()->getTranslation('fr_FR')->getLong());

        /** @var  $productNumber */
        $numberOfProducts = $order->getOrderProducts()->count();

        /** @var  $productOrder */
        $orderProducts = $order->getOrderProducts()->getData();

        /** @var  $customerIp */
        $customerIp = OneytrustQuery::create()
            ->filterByCommande($order->getRef())
            ->select('customerIp')
            ->findOne();

        /** @var  $deliveryModule */
        $deliveryModule = ModuleQuery::create()->findOneById($order->getDeliveryModuleId());

        /** @var  $paymentType */
        $paymentType = ModuleQuery::create()->findOneById($order->getPaymentModuleId());

        /** @var  $deliveryType */
        $deliveryType = $this->getDeliveryType($deliveryModule, $deliveryOrder);

        /** Start writing the xml */
        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>";
        $xml .= "<stack>";
        $xml .= "<control>";
        $xml .= "<type_flux>score</type_flux>";

        $xml .= "<utilisateur type=\"facturation\" qualite=\"2\">";
        $xml .= "<nom titre=\"" . $customerTitleFr . "\">" . $customer->getLastname() . "</nom>";
        $xml .= "<prenom>" . $customer->getFirstname() . "</prenom>";
        $xml .= "<societe>" . $address->getCompany() . "</societe>";
        $xml .= "<telhome>" . $address->getPhone() . "</telhome>";
        $xml .= "<telmobile>" . $address->getCellphone() . "</telmobile>";
        $xml .= "<email>" . $customer->getEmail() . "</email>";
        $xml .= "<idclient>" . $customer->getId() . "</idclient>";
        $xml .= "</utilisateur>";

        $xml .= "<utilisateur type=\"livraison\" qualite=\"2\">";
        $xml .= "<nom titre=\"" . $customerTitleFr . "\">" . $customer->getLastname() . "</nom>";
        $xml .= "<prenom>" . $customer->getFirstname() . "</prenom>";
        $xml .= "<societe>" . $address->getCompany() . "</societe>";
        $xml .= "<telhome>" . $address->getPhone() . "</telhome>";
        $xml .= "<telmobile>" . $address->getCellphone() . "</telmobile>";
        $xml .= "<email>" . $customer->getEmail() . "</email>";
        $xml .= "<idclient>" . $customer->getId() . "</idclient>";
        $xml .= "</utilisateur>";

        $xml .= "<adresse type=\"facturation\" format=\"1\">";
        $xml .= "<rue1>" . $invoiceOrder->getAddress1() . "</rue1>";
        $xml .= "<rue2>" . $invoiceOrder->getAddress2() . "</rue2>";
        $xml .= "<cpostal>" . $invoiceOrder->getZipcode() . "</cpostal>";
        $xml .= "<ville>" . $invoiceOrder->getCity() . "</ville>";
        $xml .= "<pays>" . $this->getCountryIso($invoiceOrder->getCountryId()) . "</pays>";
        $xml .= "</adresse>";

        /** Sets the delivery address to the customer address if home delivery is chosen */
        if ($deliveryType == 4)
        {
            $xml .= "<adresse type=\"livraison\" format=\"1\">";
            $xml .= "<rue1>" . $deliveryOrder->getAddress1() . "</rue1>";
            $xml .= "<rue2>" . $deliveryOrder->getAddress2() . "</rue2>";
            $xml .= "<cpostal>" . $deliveryOrder->getZipcode() . "</cpostal>";
            $xml .= "<ville>" . $deliveryOrder->getCity() . "</ville>";
            $xml .= "<pays>" . $this->getCountryIso($deliveryOrder->getCountryId()) . "</pays>";
            $xml .= "</adresse>";
        }

        $xml .= "<infocommande>";
        $xml .= "<siteid>" . OneytrustConst::SITE_ID_CMC . "</siteid>";
        $xml .= "<refid>" . $order->getRef() . "</refid>";
        $xml .= "<montant devise=\"EUR\">" . $order->getTotalAmount() . "</montant>";
        if ($customerIp) {
            $xml .= "<ip timestamp=\"" . date("Y-m-d H:i:s") . "\">" . $customerIp . "</ip>";
        }
        $xml .= "<transport>";
        $xml .= "<type>" . $deliveryType . "</type>";
        if (null !== $deliveryModule) {
            if ($deliveryType != 1) {
                $xml .= "<nom>" . $deliveryModule->getCode() . "</nom>";
            } else {
                /** Name of the shop in case of local pickup */
                $xml .= "<nom>" . OneytrustConst::SHOP_NAME . "</nom>";
            }
        } else {
            $xml .= "<nom>inconnu</nom>";
        }
        $xml .= "<rapidite>2</rapidite>"; //Did not find a way to select the speed correctly, put a basic default one instead
        /** Sets the pickup address if it's not an home delivery */
        if ($deliveryType != 4) {
            $xml .= "<pointrelais>";
            $xml .= "<adresse>";
            $xml .= "<rue1>" . $deliveryOrder->getAddress1() . "</rue1>";
            $xml .= "<rue2>" . $deliveryOrder->getAddress2() . "</rue2>";
            $xml .= "<cpostal>" . $deliveryOrder->getZipcode() . "</cpostal>";
            $xml .= "<ville>" . $deliveryOrder->getCity() . "</ville>";
            $xml .= "<pays>" . $this->getCountryIso($deliveryOrder->getCountryId()) . "</pays>";
            $xml .= "</adresse>";
            $xml .= "</pointrelais>";
        }
        $xml .= "</transport>";
        $xml .= "<list nbproduit=\"" . $numberOfProducts . "\">";
        /** Add every product from the order */
        foreach ($orderProducts as $product) {
            $xml .= "<produit ref=\"" . $product->getProductRef() . "\" type=\"13\" nb=\"" . $product->getQuantity() . "\" prixunit=\"" . $this->getPrice($product) . "\">" . $product->getTitle() . "</produit>";
        }
        $xml .= "</list>";
        $xml .= "<saisiecommande>1</saisiecommande>";
        $xml .= "</infocommande>";

        $xml .= "<paiement>";
        if ($paymentType->getCode() == "Paypal") {
            $xml .= "<type>paypal</type>";
        } elseif ($paymentType->getCode() == "Virement") {
            $xml .= "<type>virement</type>";
        } else {
            $xml .= "<type>carte</type>";
        }
        $xml .= "</paiement>";

        $xml .= "</control>";
        $xml .= "</stack>";
        /** Stop writing the xml */

        return $xml;
    }

    /**
     * Method handling the other sub-methods which write and send the XML flux
     * Also handles most of the log writing
     *
     * @param OrderEvent $orderEvent
     * @return bool|null
     */
    public function exportOneytrustScore(OrderEvent $orderEvent)
    {
        try {
            $reference = $orderEvent->getOrder()->getRef();

            $log = Tlog::getNewInstance();
            $log->setDestinations("\\Thelia\\Log\\Destination\\TlogDestinationFile");
            $log->setConfig("\\Thelia\\Log\\Destination\\TlogDestinationFile", 0, THELIA_ROOT . "log" . DS . "log-oneytrust.txt");
            $log->error("#ONEYTRUST // La commande " . $reference . " est dans l'évènement Oneytrust (method exportOneytrustScore)");

            /** Check if order has status paid */
            if ($orderEvent->getStatus() != 2) {
                if (2 != $status = $orderEvent->getOrder()->getStatusId()) {
                    return false;
                }
            }

            $log->error("ONEYTRUST // La commande " . $reference . " a le statut 'payée'");

            /** Check if order need Oneytrust verification */
            if (!$this->isAllow($orderEvent)) {
                $log->error("#ONEYTRUST // La commande : " . $reference . " n'est pas valide pour traitement par Oneytrust");

                return false;
            }

            $log->error("#ONEYTRUST // La commande " . $reference . " est valide pour traitement par Oneytrust");

            $exportXML = $this->writeXML($orderEvent);
            $log->error("#ONEYTRUST // La commande " . $reference . " a généré le fichier XML");

            $this->sendExport($exportXML, $reference);
            $log->error("#ONEYTRUST // La commande " . $reference . " a été exportée");
        } catch (\Exception $e) {
            Tlog::getInstance()->addError("ONEYTRUST // " .$e->getMessage());
        }

        return null;
    }
}