<?php
/**
 * File: OrderCompleteEvent.php
 * Created: 14-05-2015
 *
 * Description
 *
 * @author   Arpad Olasz <arpi@wingsline.com>
 */

namespace Project\Support\Events\Handlers;

use Project\Models\Template;
use Story\Error;

/**
 * Class OrderCompleteEvent
 * @package Project\Support\Events\Handlers
 */
class OrderCompleteEvent extends AbstractOrderEvent
{
    /**
     * Required to run this event
     *
     * @return mixed
     */
    public function run()
    {
        try {
            $this->deleteOrderCart();

            // Process the items
            $this->order->processItems();

            // log the action
            $this->order->logStatusChange();

            if ($this->email->value) {
                // Replace the template message and subject
                $replacements = $this->createReplacements();
                list($message, $subject) = $this->replaceInTemplate($this->template, $replacements);
                // Send email
                $this->mail($subject, $message, $this->email->value, $this->name->value);
            }
        } catch (\Exception $e) {
            Error::exception($e);
        }
    }

    /**
     * @return void
     */
    protected function setTemplate()
    {
        // get the template and replace the needed parts
        $this->template = Template::one(array('type' => 'order', 'name' => 'complete'));
    }
}
