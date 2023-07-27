<!DOCTYPE html>
<html>

<head>
    <title>{{ env('APP_NAME') }} Subscription Invoice !</title>
</head>

<body>
    <h4> <b>Dear {{ $details['user']['name'] }}</b></h4>
    <br>
    <br>
    <p>We hope this email finds you well. We are delighted to present your invoice from Sebenza, your all-in-one
        business management platform. This invoice is a detailed summary of the services and products you have availed
        from us during the specified period.</p>
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
    <br>
    <p>
        <b>Total Amount: {{ $details['invoice']['payable_amount'] }}</b>
    </p>
    <br>
    <p>
        <b>Payment Method: [Payment Method]</b>
    </p>
    <br>
    <br>
    <p>To make the payment process smooth and hassle-free, we have provided a secure link below. Please use this link to
        proceed with the payment.</p>
    <br>
    <br>
    <h4><b>Payment Link</b></h4>
    <br>
    <p>If you have any questions or concerns regarding the invoice or need any further assistance, please do not
        hesitate to contact us. Our team is always available to support and provide the necessary guidance.</p>
    <br>
    <p>
        We greatly appreciate your continued partnership with Sebenza and look forward to serving you in the future.
        Your satisfaction is our priority, and we are committed to providing exceptional service at all times.
    </p>
    <br>
    <p>Thank you for choosing Sebenza as your trusted business solution.</p>
    <br>
    <br>
    <p>Best regards,<br>
        <b>{{ env('APP_NAME') }} Platform</b>
    </p>
    <br>
    <p>Email: {{ App\Models\Basicinfo::first()->email }}</p>
    <br>
    <p>Website: {{ env('APP_URL') }}</p>
</body>

</html>
