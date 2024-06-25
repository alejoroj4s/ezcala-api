<x-filament-panels::page>
    <p>Configure your WhatsApp integration below.</p>

    <button id="whatsapp-login" style="background-color: #1877f2; border: 0; border-radius: 4px; color: #fff; cursor: pointer; font-family: Helvetica, Arial, sans-serif; font-size: 16px; font-weight: bold; height: 40px; max-width: 40%; padding: 0 24px;">   
        Conectar WhatsApp
    </button>

    <div id="status-message" style="margin-top: 20px;">
        <ul>
            <li id="status-authentication">1. Registro Insertado: Pending</li>
            <li id="status-token">2. Recibir Token: Pending</li>
            <li id="status-phone">3. Recibir numero de telefono: Pending</li>
            <li id="status-register">4. Registrar Numero de Telefono: Pending</li>
            <li id="status-db">5. Conectar numero con Ezcala Chat: Pending</li>
        </ul>
    </div>

    {{-- Conditional Organization Selection (Modified Section) --}}
    @php
        $userOrganizations = auth()->user()->organizations;
    @endphp

    @if ($userOrganizations->count() > 1)
        <div id="organization-select" style="margin-top: 20px; display: none;">
            <label for="organization_id" class="block text-sm font-medium text-gray-700">Select Organization:</label>
            <select id="organization_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                @foreach($userOrganizations as $organization)
                    <option value="{{ $organization->id }}">{{ $organization->name }}</option>
                @endforeach
            </select>

            <button id="finalize-btn" style="background-color: #28a745; border: 0; border-radius: 4px; color: #fff; cursor: pointer; font-family: Helvetica, Arial, sans-serif; font-size: 16px; font-weight: bold; height: 40px; padding: 0 24px; margin-top: 10px;">
                Finalize
            </button>
        </div>
    @elseif ($userOrganizations->count() == 1)
        <input type="hidden" id="organization_id" value="{{ $userOrganizations->first()->id }}"> 
        <p class="mt-2 text-sm text-gray-500">Organization: {{ $userOrganizations->first()->name }}</p>
    @endif
    {{-- End of Modified Section --}}

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var appId = '{{ $this->getAppId() }}';

            function getJwtToken() {
                return fetch('/chat/user/token')
                    .then(response => response.json())
                    .then(data => data.token);
            }

            getJwtToken().then(userAccessToken => {
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
                        var statusMessage = document.getElementById('status-message');

                        if (response.authResponse) {
                            const accessToken = response.authResponse.accessToken;
                            document.getElementById('status-token').innerText = '2. Recibir Token: Success';

                            fetchDebugToken(accessToken, userAccessToken);
                        } else {
                            document.getElementById('status-token').innerText = '2. Recibir Token: Failed';
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

                function fetchDebugToken(accessToken, userAccessToken) {
                    fetch(`https://graph.facebook.com/v18.0/debug_token?input_token=${accessToken}`, {
                        method: 'GET',
                        headers: {
                            'Authorization': `Bearer {{ env('USER_ACCESS_TOKEN_FB') }}`
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.data && data.data.is_valid) {
                            const wabaId = data.data.granular_scopes.find(scope => scope.scope === 'whatsapp_business_management').target_ids[0];
                            document.getElementById('status-authentication').innerText = '1. Registro Insertado: Success';
                            document.getElementById('status-token').innerText = '2. Recibir Token: Success';

                            fetchPhoneNumbers(wabaId, accessToken, userAccessToken);
                            document.getElementById('whatsapp-login').disabled = true;
                        } else {
                            document.getElementById('status-token').innerText = '2. Recibir Token: Failed';
                        }
                    })
                    .catch(error => {
                        document.getElementById('status-token').innerText = '2. Recibir Token: Failed';
                        console.error("fetchDebugToken error:", error);
                    });
                }

                function fetchPhoneNumbers(wabaId, accessToken, userAccessToken) {
                    fetch(`https://graph.facebook.com/v18.0/${wabaId}/phone_numbers`, {
                        method: 'GET',
                        headers: {
                            'Authorization': `Bearer {{ env('USER_ACCESS_TOKEN_FB') }}`
                        }
                    })
                    .then(response => {
                        console.log("fetchPhoneNumbers response:", response);
                        return response.json();
                    })
                    .then(data => {
                        document.getElementById('status-phone').innerText = '3. Recibir numero de telefono: Success';

                        // Check if data.data exists and has at least one element
                        if (data.data && data.data.length > 0) {
                            const numberId = data.data[0].id;
                            const whatsappNumber = data.data[0].display_phone_number;

                            const organizationSelect = document.getElementById('organization-select');
                            if (organizationSelect) {
                                organizationSelect.style.display = 'block';

                                // Attach event listener when organizationSelect becomes visible
                                const finalizeBtn = document.getElementById('finalize-btn');
                                if (finalizeBtn) {
                                    finalizeBtn.addEventListener('click', function () {
                                        const organizationId = document.getElementById('organization_id').value;
                                        registerPhoneNumber(numberId, accessToken, whatsappNumber, wabaId, organizationId, userAccessToken);
                                    });
                                }
                            } else { // This section is executed when only one organization exists
                                const organizationId = document.getElementById('organization_id').value; 
                                registerPhoneNumber(numberId, accessToken, whatsappNumber, wabaId, organizationId, userAccessToken);
                            }
                        } else {
                            document.getElementById('status-phone').innerText = '3. Recibir numero de telefono: Failed (No phone numbers found)';
                            console.error("No phone numbers found in the response:", data);
                        }
                    })
                    .catch(error => {
                        document.getElementById('status-phone').innerText = '3. Recibir numero de telefono: Failed';
                        console.error("fetchPhoneNumbers error:", error);

                        // Still proceed to save the other details if organizationId exists
                        const organizationId = document.getElementById('organization_id')?.value;
                        if (organizationId) {
                            saveToDatabase(accessToken, 'UNKNOWN', 'UNKNOWN', wabaId, organizationId, userAccessToken);
                        } else {
                            console.error("Could not proceed to save details: organizationId not found.");
                        }
                    });
                }


                function registerPhoneNumber(numberId, accessToken, whatsappNumber, wabaId, organizationId, userAccessToken) {
                    fetch(`https://graph.facebook.com/v20.0/${numberId}/register`, {
                        method: 'POST',
                        headers: {
                            'Authorization': `Bearer {{ env('USER_ACCESS_TOKEN_FB') }}`,
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            messaging_product: 'whatsapp',
                            pin: '012666'
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            document.getElementById('status-register').innerText = '4. Registrar Numero de Telefono: Success';
                            saveToDatabase(accessToken, whatsappNumber, numberId, wabaId, organizationId, userAccessToken);
                        } else {
                            document.getElementById('status-register').innerText = '4. Registrar Numero de Telefono: Failed';
                            saveToDatabase(accessToken, whatsappNumber, numberId, wabaId, organizationId, userAccessToken);
                        }
                    })
                    .catch(error => {
                        document.getElementById('status-register').innerText = '4. Registrar Numero de Telefono: Failed';
                        console.error("registerPhoneNumber error:", error);
                        saveToDatabase(accessToken, whatsappNumber, numberId, wabaId, organizationId, userAccessToken);
                    });
                }

                function saveToDatabase(accessToken, whatsappNumber, numberId, wabaId, organizationId, userAccessToken) {
                    fetch('/chat/whatsapp-accounts', {
                        method: 'POST',
                        headers: {
                            'Authorization': `Bearer ${userAccessToken}`,
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            access_token: accessToken,
                            whatsapp_number: whatsappNumber,
                            whatsapp_number_id: numberId,
                            account_id: wabaId,
                            organization_id: organizationId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.error) {
                            document.getElementById('status-db').innerText = '5. Conectar numero con Ezcala Chat: Failed - ' + data.message;
                        } else {
                            document.getElementById('status-db').innerText = '5. Conectar numero con Ezcala Chat: Success';
                        }
                    })
                    .catch(error => {
                        document.getElementById('status-db').innerText = '5. Conectar numero con Ezcala Chat: Failed - ' + error.message;
                        console.error("saveToDatabase error:", error);
                    });
                }
            });
        });
    </script>
</x-filament-panels::page>