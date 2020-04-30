<?php
/**
 * File: order_events.php
 * Created: 19-01-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

// --------------------------------------------------------------
// Order pending
// --------------------------------------------------------------

event('order.pending', null, 'Project\Support\Events\Handlers\OrderPendingEvent');

// --------------------------------------------------------------
// Order processed
// --------------------------------------------------------------

event('order.processed', null, 'Project\Support\Events\Handlers\OrderProcessedEvent');

// --------------------------------------------------------------
// Shipped
// --------------------------------------------------------------

event('order.shipped', null, 'Project\Support\Events\Handlers\OrderShippedEvent');

// --------------------------------------------------------------
// Complete
// --------------------------------------------------------------

event('order.complete', null, 'Project\Support\Events\Handlers\OrderCompleteEvent');

// --------------------------------------------------------------
// Order refunded
// --------------------------------------------------------------

event('order.refunded', null, 'Project\Support\Events\Handlers\OrderRefundedEvent');

// --------------------------------------------------------------
// Order voided
// --------------------------------------------------------------

event('order.voided', null, 'Project\Support\Events\Handlers\OrderVoidedEvent');
