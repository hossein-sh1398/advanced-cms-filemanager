<html>
    <head>
        <title>@yield('title')</title>
        <style>
            html,body {
                padding: 0;
                margin: 0;
                direction: rtl;
                text-align: right;
            }
        </style>
        @yield('css')
    </head>
    <body>
        <div style="font-family:tahoma; line-height: 1.5; font-weight: normal; font-size: 15px; color: #2F3044; min-height: 100%; margin:0; padding:0; width:100%; background-color:#edf2f7">
            <br>
            <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse;margin:0 auto; padding:0; max-width:600px">
                <tbody>
                    {{-- <tr>
                        <td align="center" valign="center" style="text-align:center; padding: 40px">
                            <a href="https://keenthemes.com" rel="noopener" target="_blank">
                                <img alt="Logo" src="../../assets/media/logos/mail.svg">
                            </a>
                        </td>
                    </tr> --}}
                    <tr>
                        <td align="left" valign="center">
                            <div style="text-align:left; margin: 0 20px; padding: 40px; background-color:#ffffff; border-radius: 6px">
                                @yield('main-content')
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" valign="center" style="font-size: 13px; text-align:center;padding: 20px; color: #6d6e7c;">
                            <a href="{{ env('APP_URL') }}" rel="noopener" target="_blank">{{ env('APP_NAME') }}</a>.</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        @yield('js')
    </body>
</html>
