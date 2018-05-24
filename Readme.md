# Oneytrust Score

Oneytrust score allow you to evaluate your customers according to the Oneytrust DB to reduce
the chance of being frauded.

## Installation

### Manually

* Copy the module into ```<thelia_root>/local/modules/``` directory and be sure that the name of the module is OneytrustScore.
* Activate it in your thelia administration panel

### Composer

Add it in your main thelia composer.json file

```
composer require thelia/oneytrust-score-module:~1.1.0
```

## Usage

####Configuration :
NB1 : You need an account and site IDs provided by Oneytrust for this module to work
with your website.

NB2 : If you use a delivery module that isn't DpdPickup, Colissimo, soColosimmo or LocalPickup,
you'll need to edit the code and add them to the getDeliveryType method in OneytrustManager.php

--

You first need to go to the module configuration page. You can find it by
clicking on the cog button on the right of the module name in the module list
page, then by clicking on the wrench. Fill out the form correctly then save with
th button on the top right.

Configuration example :
https://i.imgur.com/p7yAeCr.png

The rest is automatic. The customers informations will be sent when they pay their order.
You can see a review of all actual paid order by clicking on the "Oneytrust" button
in the order list of Thelia Backoffice. It'll display a list of all orders that needs to be taken care of
with these informations :

**Commande                :** The order Reference. Clicking on it will send you to this oder page

**Nom du client           :** The order customer. Clicking on it will send you to this customer page

**Date                    :** The date at which the order as passed. Clicking on it will redirect you to the Oneytrust page about this order

**Montant                 :** The order total price

**Informations Oneytrust  :** The order status, followed by the score it got at the evaluation between parenthessis, followed
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
