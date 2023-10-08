<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <title>Document</title>
</head>

<body>
    <div style="padding: 30px; background-color:#01093d;text-align:center;">
        {{-- <img src="{{ $message->embed('http://127.0.0.1:8000/assets/static/img/logo.png') }}"> --}}
        <h1 style="color:#FDC442;font-family: 'Cormorant Garamond', serif;">Welcome to Summer Healing</h1>
    </div>
    <div style="padding:30px;font-family: 'Poppins', sans-serif;color:gray;text-align:left">
        <p>Hello, {{ $user->full_name }},</p>
        <p>Thank you for joining summer healing community.</p>
        <table width="100%" cellspacing="0" cellpadding="0">
            <tr>
                <td>
                    <table cellspacing="0" cellpadding="0">
                        <tr>
                            <td style="border-radius: 10px;" bgcolor="#FDC442">
                                <a href="{{ $link }}" target="_blank"
                                    style="padding: 8px 12px; border: 1px solid #FDC442;border-radius: 10px;font-family: 'Poppins', sans-serif;font-size: 14px; color: #343434;text-decoration: none;font-weight:bold;display: inline-block;">
                                    Login to Dashboard
                                </a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

    </div>
    <div
        style="padding:10px;font-family: 'Poppins', sans-serif;color:rgb(227, 227, 227);text-align:center;background-color:#aaaaaa;font-size:12px;">
        <p>Sent from Summerhealing.org</p>
    </div>
</body>

</html>
