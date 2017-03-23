<?php
/**
 * Pimcore
 *
 * This source file is available under two different licenses:
 * - GNU General Public License version 3 (GPLv3)
 * - Pimcore Enterprise License (PEL)
 * Full copyright and license information is available in
 * LICENSE.md which is distributed with this source code.
 *
 * @category   Pimcore
 * @package    EcommerceFramework
 * @copyright  Copyright (c) 2009-2016 pimcore GmbH (http://www.pimcore.org)
 * @license    http://www.pimcore.org/license     GPLv3 and PEL
 */


namespace Pimcore\Bundle\PimcoreEcommerceFrameworkBundle\OrderManager;

use Pimcore\Bundle\PimcoreEcommerceFrameworkBundle\Factory;
use Pimcore\Bundle\PimcoreEcommerceFrameworkBundle\Model\AbstractOrder as Order;
use Pimcore\Bundle\PimcoreEcommerceFrameworkBundle\Model\AbstractOrderItem as OrderItem;
use Pimcore\Bundle\PimcoreEcommerceFrameworkBundle\Model\Currency;
use Pimcore\Model\Element\Note;


interface IOrderAgent
{
    /**
     * @param Factory $factory
     * @param Order                        $order
     */
    public function __construct(Factory $factory, Order $order);


    /**
     * @return Order
     */
    public function getOrder();


    /**
     * cancel order item and refund payment
     *
     * @param OrderItem $item
     *
     * @return Note
     */
    public function itemCancel(OrderItem $item);


    /**
     * start item complaint
     *
     * @param OrderItem $item
     * @param float $quantity
     *
     * @return Note
     */
    public function itemComplaint(OrderItem $item, $quantity);


    /**
     * change order item
     *
     * @param OrderItem $item
     * @param float $amount
     *
     * @return Note
     */
    public function itemChangeAmount(OrderItem $item, $amount);


    /**
     * set a item state
     *
     * @param OrderItem $item
     * @param string    $state
     *
     * @return Note
     */
    public function itemSetState(OrderItem $item, $state);



    /**
     * @return Currency
     */
    public function getCurrency();


    /**
     * @return bool
     */
    public function hasPayment();


    /**
     * @return \Pimcore\Bundle\PimcoreEcommerceFrameworkBundle\PaymentManager\Payment\IPayment
     */
    public function getPaymentProvider();


    /**
     * @param \Pimcore\Bundle\PimcoreEcommerceFrameworkBundle\PaymentManager\Payment\IPayment $paymentProvider
     *
     * @return Order
     */
    public function setPaymentProvider(\Pimcore\Bundle\PimcoreEcommerceFrameworkBundle\PaymentManager\Payment\IPayment $paymentProvider);


    /**
     * Starts payment:
     * checks if payment info with PENDING payment exists and checks if order fingerprint has not changed
     * if true -> returns existing payment info
     * if false -> creates new payment info (and aborts existing PENDING payment infos)
     *
     * @return \Pimcore\Bundle\PimcoreEcommerceFrameworkBundle\Model\AbstractPaymentInformation
     */
    public function startPayment();

    /**
     * Returns current payment info of order, or null if none exists
     *
     * @return null|\Pimcore\Bundle\PimcoreEcommerceFrameworkBundle\Model\AbstractPaymentInformation
     */
    public function getCurrentPendingPaymentInfo();

    /**
     * cancels payment for current payment info
     * - payment will be cancelled, order state will be resetted and cart will we writable again.
     *
     * -> this should be used, when user cancels payment
     *
     * only possible when payment state is PENDING, otherwise exception is thrown
     *
     * @return \Pimcore\Bundle\PimcoreEcommerceFrameworkBundle\Model\AbstractOrder
     * @throws \Pimcore\Bundle\PimcoreEcommerceFrameworkBundle\Exception\UnsupportedException
     */
    public function cancelStartedOrderPayment();


    /**
     * @param \Pimcore\Bundle\PimcoreEcommerceFrameworkBundle\PaymentManager\IStatus $status
     *
     * @return IOrderAgent
     */
    public function updatePayment(\Pimcore\Bundle\PimcoreEcommerceFrameworkBundle\PaymentManager\IStatus $status);


    /**
     * @return Note[]
     */
    public function getFullChangeLog();
}
