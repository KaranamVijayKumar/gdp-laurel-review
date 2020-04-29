<p>
    Changing the status of an order will perform the following actions:
</p>
<table class="1/1">
    <thead>
    <tr>
        <th class="1/8">Order status</th>
        <th>Description</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>Pending</td>
        <td>
            <p>The order status is set <strong>automatically</strong> to Pending, when the order is created
                (the visitor presses the <q>Continue with Paypal</q> on checkout).</p>

            <p>You can set an order to Pending manually also, the action will only change the order's status
                and logs it in the history. No emails will be sent.</p>
        </td>
    </tr>
    <tr>
        <td>Processed</td>
        <td>
            <p><q>Processed</q> status is set <strong>automatically</strong>, when the the payment has been completed,
                and the funds have been added successfully to your account balance and the order contains shippable
                items like Issues.</p>

            <p>The system will also generate the submisssion, subscription
                (if the order contains them) and will send an confirmation email using the
                <em>Order processed</em> template.</p>

            <p>Setting an order to this status manually will send the email again, but it wont generate the
                submissions or subscriptions in case they were already generated.</p>
        </td>
    </tr>
    <tr>
        <td>Shipped</td>
        <td>
            <p>This status needs to be set <strong>manually</strong> when you shipped the ordered Issues.</p>

            <p>The action will send an email using the
                <em>Order shipped</em> template.</p>
        </td>
    </tr>
    <tr>
        <td>Complete</td>
        <td>
            <p>This status is <strong>automatically</strong> set when the the payment has been completed and the order
                <strong>doesn't contain shippable items</strong> like Issues (if it contains shippable items, it will
                be set to Processed).</p>

            <p>The system will also generate the submisssion, subscription
                (if the order contains them) and will send an confirmation email using the
                <em>Order complete</em> template.</p>

            <p>Setting an order to this status manually will send the email again, but it won't generate the
                submissions or subscriptions in case they were already generated.</p>
        </td>
    </tr>
    <tr>
        <td>Refunded</td>
        <td>
            <p>This status is <strong>automatically</strong> set, when you refunded the payment using your Paypal
                merchant account and issued a refund from it.</p>

            <p>When the refund happened, the system will also the following actions on the ordered items:</p>
            <ul class="list-ui">
                <li>
                    <strong>Submission</strong>: will set the submission to withdrawn (if it was already created)
                </li>
                <li>
                    <strong>Subscription</strong>: will delete the subscription (if it was already created).
                    <span class="gray">
                         Setting the order back to Processed will create the subscription again.
                    </span>
                </li>
                <li>
                    <strong>Issue</strong>: no action will be taken
                </li>
            </ul>

            <p>Setting an order to this status will send an email using the <em>Order refunded</em> template.</p>
        </td>
    </tr>
    <tr>
        <td>Voided</td>
        <td>
            <p>This status is <strong>automatically</strong> set for the following payment failures:</p>
            <ul class="list-ui">
                <li>
                    <strong>Denied</strong>: The payment was denied
                </li>
                <li>
                    <strong>Expired</strong>: The payment authorization has expired and cannot be captured.
                </li>
                <li>
                    <strong>Failed</strong>: The payment has failed.
                </li>
                <li>
                    <strong>Reversed</strong>: A payment was reversed due to a chargeback or other type of reversal.
                    The funds have been removed from your account balance and returned to the buyer.
                </li>
                <li>
                    <strong>Voided</strong>: This authorization has been voided.
                </li>

            </ul>
            <p>When the order is voided, the system will also the following actions on the ordered items:</p>
            <ul class="list-ui">
                <li>
                    <strong>Submission</strong>: will set the submission to withdrawn (if it was already created)
                </li>
                <li>
                    <strong>Subscription</strong>: will delete the subscription (if it was already created).
                    <span class="gray">
                         Setting the order back to Processed will create the subscription again.
                    </span>
                </li>
                <li>
                    <strong>Issue</strong>: no action will be taken
                </li>
            </ul>

            <p>Setting an order to this status will send an email using the <em>Order voided</em> template.</p>
        </td>
    </tr>
    </tbody>
</table>
