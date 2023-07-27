<!DOCTYPE html>
<html>

<head>
    <title>Payment Confirmation - {{ env('APP_NAME') }} </title>
</head>

<body>
    <h4> <b>Dear {{ $details['user']['first_name'] }} {{ $details['user']['last_name'] }}</b></h4>
    <br>
    <br>
    <p>We hope this email finds you well. This is to confirm that we have received your payment for the recent invoice
        you settled with us. Thank you for choosing Sebenza as your business management platform and for making a timely
        payment.</p>
    <br>
    <br>
    <p>
        <b>Invoice Number: {{ $details['invoice']['invoiceID'] }}</b>
    </p>
    <br>
    <p>
        <b>Invoice Date: {{ $details['invoice']['invoiceDate'] }}</b>
    </p>
    <br>
    <p>
        <b>Payment Date: {{ $details['invoice']['paymentDate'] }}</b>
    </p>
    <br>
    <p>
        <b>Payment Method: [Payment Method]</b>
    </p>
    <br>
    <p>
        <b>Amount Paid: {{ $details['invoice']['paid_amount'] }}</b>
    </p>
    <br>
    <p>
        Your prompt payment is highly appreciated, and it ensures the continued smooth operation of your business with
        Sebenza. If you require any assistance or have any questions about your payment or our services, please do not
        hesitate to reach out to our support team.
    </p>
    <br>
    <p>We remain committed to providing you with exceptional service and helping you optimize your business processes
        through Sebenza.</p>
    <br>

    <p>Once again, thank you for your continued trust in Sebenza.</p>
    <br>
    <br>

    <p>Best regards,<br>
    <h4> <b>{{ env('APP_NAME') }}</h4></b>
    </p>
    <br>
    <p>Email: {{ App\Models\Basicinfo::first()->email }}</p>
    <br>
    <p>Website: {{ env('APP_URL') }}</p>
</body>

</html>
