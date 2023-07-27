<!DOCTYPE html>
<html>

<head>
    <title>{{ env('APP_NAME') }} Registration Successful !</title>
</head>

<body>
    <h4> <b>Dear {{ $details['user']['name'] }}</b></h4>
    <br>
    <br>
    <p>Congratulations on successfully registering with Sebenza, your all-in-one business management platform! We are
        thrilled to have you on board and look forward to empowering your business with our comprehensive suite of tools
        and features.</p>
    <br>
    <p>
        Sebenza is designed to simplify and streamline your business operations, enabling you to focus on what truly
        matters - growth and success. With Sebenza, you gain access to an array of powerful tools, including client
        management, invoicing, project tracking, inventory management, and more.
    </p>
    <br>
    <p>To get started with Sebenza, please follow the easy steps below:</p>
    <br>
    <ol>
        <li>
            Click on the login link provided below.
        </li>
        <li>
            Use the credentials you provided during registration to access your Sebenza account.
        </li>
        <li>
            Explore the intuitive interface and begin customizing the platform to suit your unique business needs.
        </li>
    </ol>
    <br>
    <a href="{{ url(env('APP_URL')) }}auth/login">{{ env('APP_URL') }}auth/login</a>
    <br>
    <p>Our team is committed to ensuring you have a seamless onboarding experience and making the most out of Sebenza.
        Should you have any questions or need assistance at any point, don't hesitate to reach out to us. We are here to
        support you every step of the way.</p>
    <br>
    <p>
        Thank you for choosing Sebenza to be your trusted business companion. Together, we will achieve new heights of
        efficiency and prosperity.
    </p>
    <br>
    <br>
    <p>
        Welcome aboard!
    </p>

    <p>Thank you<br>
        <b>{{ env('APP_NAME') }} Platform</b>
    </p>
    <br>
    <p>Email: {{ App\Models\Basicinfo::first()->email }}</p>
    <br>
    <p>Website: {{ env('APP_URL') }}</p>
</body>

</html>
