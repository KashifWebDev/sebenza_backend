<!DOCTYPE html>
<html>

<head>
    <title>{{ env('APP_NAME') }} Subscription Invoice !</title>
</head>

<body>
    <h4> <b>Dear Team Member,</b></h4>
    <br>
    <br>
    <p>I trust this email finds you well. As the proud owner of {{ $invdetails['user']['company_name'] }}, I am thrilled
        to inform you about
        an exciting opportunity to enhance our business operations through Sebenza, our all-in-one business management
        platform.</p>
    <br>
    <br>
    <p>
        Sebenza is designed to optimize productivity, streamline workflows, and provide valuable insights to help us
        achieve new heights of success. It offers a wide range of features, including client management, invoicing,
        project tracking, inventory management, and more.
    </p>
    <br>
    <p>
        To join our company on Sebenza and leverage its powerful capabilities, please follow the simple steps below:
    </p>
    <br>
    <br>
    <p>
        <b>Click on the registration link provided below.</b>
    </p>
    <br>
    <p>
        <b> Fill in your details to create your Sebenza account.</b>
    </p>
    <br>
    <p>
        <b>
            Explore the intuitive interface and start using the platform.
        </b>
    </p>
    <br>
    <br>
    <a href="{{ env('APP_URL') }}[Registration Link]">{{ env('APP_URL') }}[Registration Link]</a>
    <br>
    <br>
    <p>
        With Sebenza, we can collaboratively work towards achieving our goals, providing excellent services, and driving
        growth. If you have any questions or need assistance during the onboarding process, our team is readily
        available to support you.
    </p>
    <br>
    <p>We look forward to having you on board and witnessing the positive impact Sebenza will bring to
        {{ $invdetails['user']['company_name'] }}. Together, let's take our business to new horizons.</p>
    <br>
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
