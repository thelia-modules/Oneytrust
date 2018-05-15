<?php

namespace OneytrustScore\Loop;


use OneytrustScore\Model\OneytrustQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Thelia\Core\Template\Element\ArraySearchLoopInterface;
use Thelia\Core\Template\Element\BaseLoop;
use Thelia\Core\Template\Element\LoopResult;
use Thelia\Core\Template\Element\LoopResultRow;
use Thelia\Core\Template\Loop\Argument\Argument;
use Thelia\Core\Template\Loop\Argument\ArgumentCollection;
use Thelia\Model\CustomerQuery;
use Thelia\Model\ModuleQuery;
use Thelia\Model\OrderQuery;

class OneytrustScoreLoop extends BaseLoop implements ArraySearchLoopInterface
{
    /**
     * @param $order
     * @return string
     */
    private function getMessage($order)
    {
        if ($order['Status'] == "oneytrust") {
            return $order['Oneytrust']['Status'] . " (" . $order['Oneytrust']['Validation'] . " ) - " . $order['Oneytrust']['Motifs'];
        }
        return isset($order['Message']) ? $order['Message'] : "Une erreur est survenue";
    }

    /**
     * @param $needle
     * @param $haystack
     * @return bool|int|string
     */
    private function recursiveArraySearch($needle, $haystack)
    {
        foreach ($haystack as $key=>$value) {
            $current_key = $key;
            if ($needle === $value || (is_array($value) && $this->recursiveArraySearch($needle, $value) !== false)) {
                return $current_key;
            }
        }
        return false;
    }

    /**
     * Definition of loop arguments
     * @return \Thelia\Core\Template\Loop\Argument\ArgumentCollection
     */
    protected function getArgDefinitions()
    {
        return new ArgumentCollection(
            Argument::createIntListTypeArgument('orderids')
        );
    }

    /**
     * @return array
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function buildArray()
    {
        $orders = OrderQuery::create()->filterById($this->getOrderids())->orderByCreatedAt(Criteria::DESC)->find()->getData();
        $orderRefs = [];
        $result = [];

        /** @var \Thelia\Model\Order $order */
        foreach ($orders as $order) {
            $paymentType = ModuleQuery::create()->findOneById($order->getPaymentModuleId());
            if (!$paymentType->getCode() == "Atos") {
                continue ;
            }

            $customer = CustomerQuery::create()->findOneById($order->getCustomerId());

            /** Save order reference and initialize wait status */
            $orderRefs[] = $order->getRef();
            $result[] = [
                'CommandeId'        => $order->getId(),
                'CommandeRef'       => $order->getRef(),
                'CommandeDate'      => $order->getCreatedAt("d/m/Y H:i:s"),
                'CommandePrice'     => $order->getTotalAmount(),
                'CustomerId'        => $customer->getId(),
                'CustomerCivilitee' => $customer->getFirstname() . ' ' . $customer->getLastname(),
                'Status'            => 'wait'
            ];
        }

        /** Load the order if it is already in the Oneytrust DataTable */
        $oneytrustOrders = OneytrustQuery::create()->filterByCommande($orderRefs, Criteria::IN)->find()->getData();

        /** @var \OneytrustScore\Model\Oneytrust $oneytrustOrder */
        foreach ($oneytrustOrders as $oneytrustOrder) {
            if ($key = $this->recursiveArraySearch($oneytrustOrder->getCommande(), $result)) {
                $result[$key]['Oneytrust'] = $oneytrustOrder->toArray();
                if ($oneytrustOrder->getEvaldate() != null) {
                    $result[$key]['Status'] = "oneytrust";
                }
            }
        }

        /** Save the order (wait status) */
        foreach ($result as $key => $order) {
            if ($order['Status'] === 'wait') {
                try {
                    $analyse = \OneytrustScore\OneytrustScore::analyse($order['CommandeRef']);
                    $xml = simplexml_load_string($analyse);

                    if ($xml->result['retour'] != 'trouvee') {
                        $result[$key]['Status'] = 'error';
                        $result[$key]['Message'] = isset($xml['message']) ? $xml['message'] : "Une erreur s'est produite lors de l'envoi";
                        continue;
                    }

                    if (null == $oneytrust = OneytrustQuery::create()->findOneByCommande($order['CommandeRef'])) {
                        $oneytrust = new \OneytrustScore\Model\Oneytrust();
                        $oneytrust->setCommande($order['CommandeRef']);
                    }

                    $motifs = "";
                    foreach ($xml->result->analyse->motif as $motif) {
                        $motifs .= $motif;
                        $motifs .= " ";
                    }

                    $oneytrust
                        ->setStatus($xml->result)
                        ->setValidation($xml->result->eval)
                        ->setMotifs($motifs)
                        ->save();

                    $result[$key]['Oneytrust'] = $oneytrust->toArray();
                    $result[$key]['Status'] = "oneytrust";
                } catch (\Exception $e) {
                    $result[$key]['Status'] = 'error';
                    $result[$key]['Message'] = "Une erreur s'est produite lors de l'envoi";
                    continue;
                }
            }
        }

        return $result;
    }

    /**
     * @param LoopResult $loopResult
     * @return LoopResult
     */
    public function parseResults(LoopResult $loopResult)
    {
        foreach ($loopResult->getResultDataCollection() as $order) {
            $loopResultRow = new LoopResultRow();

            $loopResultRow
                ->set('COMMANDE_ID',$order['CommandeId'])
                ->set('COMMANDE_REF',$order['CommandeRef'])
                ->set('COMMANDE_DATE',$order['CommandeDate'])
                ->set('COMMANDE_PRICE',$order['CommandePrice'])
                ->set('CUSTOMER_ID',$order['CustomerId'])
                ->set('CUSTOMER_NAME',$order['CustomerCivilitee'])
                ->set('STATUS',$order['Status'])
                ->set('MESSAGE',$this->getMessage($order))
                ;

            $loopResult->addRow($loopResultRow);
        }

        return $loopResult;
    }
}