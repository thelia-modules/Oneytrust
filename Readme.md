# Oneytrust Score

OneytrustScore is a module that sends customer informations to Oneytrust for them to
calculate a score and tell you if you should trust the customer or not, thus helping
you not losing money from fraud.

NB1 : You need an account and site IDs provided by Oneytrust for this module to work
with your website.

NB2 : This module was originally made for labonnepointure.fr and will need some tweakings
to work with your own website.

## Installation

### Manually

* Copy the module into ```<thelia_root>/local/modules/``` directory and be sure that the name of the module is OneytrustScore.
* Activate it in your thelia administration panel

### Composer

Add it in your main thelia composer.json file

```
composer require your-vendor/oneytrust-score-module:~1.0
```

## Usage

####Configuration :
Change the const variables in Config/OneytrustConst.php according to your website

Add any delivery module that you use but isn't already in the getDeliveryType method in OneytrustManager.php

--

The rest is automatic. The customers informations will be sent when they pay their order.
You can see a review of all actual paid order by clicking on the "Oneytrust" button
in the order list of Thelia Backoffice. It'll display a list of all orders that needs to be taken care of
with these informations :

Commande                : The order Reference. Clicking on it will send you to this oder page

Nom du client           : The order customer. Clicking on it will send you to this customer page

Date                    : The date at which the order as passed. Clicking on it will redirect you to the Oneytrust page about this order

Montant                 : The order total price

Informations Oneytrust  : The order status, followed by the score it got at the evaluation between parenthessis, followed
by the reason it got this mark. Displays an error message in case of an error.

## Loop

[oneytrust.loop]

### Input arguments

|Argument           |Description |
|---                |--- |
|**orderids**       | The list of order IDs you wish to select in your loop. |

### Output arguments

|Variable           |Description |
|---                |--- |
|$COMMANDE_ID       | The order ID |
|$COMMANDE_REF      | The order reference |
|$COMMANDE_DATE     | The date at which the order was passed |
|$COMMANDE_PRICE    | The order price |
|$CUSTOMER_ID       | The customer ID |
|$CUSTOMER_NAME     | The customer name |
|$STATUS            | The status of the order |
|$MESSAGE           | Informations about the status of the order |

### Example

````$xslt
{loop type="oneytrust.loop" name="oneytrust" orderids=$orderidlist}
    Order ID        : {$COMMANDE_ID}
    Order Ref       : {$COMMANDE_REF}
    Order date      : {$COMMANDE_DATE}
    Order Price     : {format_number number=$COMMANDE_PRICE}
    Customer ID     : {$CUSTOMER_ID}}
    Customer name   : {$CUSTOMER_NAME}
    Order status    : {$STATUS}
    Message         : {$MESSAGE}
{/loop}
````