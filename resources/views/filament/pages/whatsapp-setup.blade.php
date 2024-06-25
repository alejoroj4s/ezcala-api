<x-filament-panels::page>
    <p>Configure your WhatsApp integration below.</p>

    <button id="whatsapp-login" style="background-color: #1877f2; border: 0; border-radius: 4px; color: #fff; cursor: pointer; font-family: Helvetica, Arial, sans-serif; font-size: 16px; font-weight: bold; height: 40px; padding: 0 24px;">
        Authenticate with WhatsApp
    </button>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var appId = '{{ $this->getAppId() }}';

            window.fbAsyncInit = function() {
                FB.init({
                    appId            : appId,
                    cookie           : true,
                    xfbml            : false,
                    version          : 'v18.0'
                });
            };

            (function(d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) return;
                js = d.createElement(s); js.id = id;
                js.src = "https://connect.facebook.net/en_US/sdk.js";
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));

            document.getElementById('whatsapp-login').addEventListener('click', function () {
                FB.login(function (response) {
                    if (response.authResponse) {
                        const accessToken = response.authResponse.accessToken;

                        console.log('auth response:', response.authResponse);
                        // Use this token to call the debug_token API and get the shared WABA's ID
                    } else {
                        console.log('User cancelled login or did not fully authorize.');
                    }
                }, {
                    scope: 'whatsapp_business_management, whatsapp_business_messaging',
                    extras: {
                        feature: 'whatsapp_embedded_signup',
                        setup: {
                            // Prefilled data can go here
                        }
                    }
                });
            });
        });
    </script>
</x-filament-panels::page>