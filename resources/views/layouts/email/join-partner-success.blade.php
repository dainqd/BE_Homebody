<!DOCTYPE HTML>
<html xmlns:layout="http://www.ultraq.net.nz/thymeleaf/layout">
<head>
    <meta charset="UTF-8"/>
</head>
<body style="font-family: Arial;">
<table align="center" border="1" cellpadding="0" cellspacing="0"
       style="
    border: transparent;
    border-radius: 15px;
    background: linear-gradient(to right, #eedcec, #dacee6);
    width: 600px;
    text-align: center;
    ">
    <tr>
        <td>
            <h1 style="color: #2e3454; letter-spacing: 5px; font-weight: bold;">Dev Fullstack</h1>
        </td>
    </tr>
    <tr>
        <td style="text-align: center;">
            <table
                style="border: transparent; border-radius: 15px; background: #fff; display: inline-block; border-spacing: 0px; margin: 0 50px;">
                <tr>
                    <td>
                        <img alt=""
                             src="https://imagedelivery.net/KvFcUzLL2k6Q3_ROU5d8cw/37c9970f-8e56-4543-bff5-85993b7bc600/public"
                             style="border-radius: 15px 15px 0 0; display: block;" width="100%">
                    </td>
                </tr>
                <tr>
                    <td>
                        <table style="text-align: center; display: inline-block;">
                            <tr>
                                <td>
                                    <p style="color: #2e3454;">Support | Terms and Conditions | Contact Us</p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div
                                        style="font-weight:bold;font-size:20px;line-height:25px;text-align:center;color:#2e3454;">
                                        CONGRATULATIONS EMAIL!
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="content">
                                        <p style="color: #2e3454;">
                                            Welcome {{ $email }}
                                            <br><br>
                                            Congratulations on your successful registration.
                                            <br>
                                            From now on, you will be our perfect and reliable partner.
                                            <br>
                                            Your account has been activated with the following login information:
                                            <br>
                                            <span style="text-align: start">
                                                Email: <strong>{{ $email }}</strong>
                                                <br>
                                                Password: <strong>{{ $password }}</strong>
                                           </span>
                                            <br><br>
                                            Thank you for trusting and choosing our services. We hope you have a
                                            wonderful experience using our platform.
                                        </p>
                                        <p style="color: #2e3454;">
                                            If you have any questions or need assistance, please feel free to contact
                                            us.
                                        </p>
                                        <p style="color: #2e3454;">Wish you have a good experience on our platform!</p>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td style="color: #2e3454;">
                                    Best regards,
                                    <br>
                                    Dev Fullstack Team.
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <P style="color: #2e3454;">Copyright @2024 Dev Fullstack</P>
        </td>
    </tr>
</table>
</body>
</html>
