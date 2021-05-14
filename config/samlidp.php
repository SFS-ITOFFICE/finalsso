<?php

return [

    /*
    |--------------------------------------------------------------------------
    | SAML idP configuration file
    |--------------------------------------------------------------------------
    |
    | Use this file to configure the service providers you want to use.
    |
     */
    // Outputs data to your laravel.log file for debugging
    'debug' => true,
    // Define the email address field name in the users table
    'email_field' => 'email',
    //'email_field' => 'username',

    // The URI to your login page
    //'login_uri' => 'adauth/login',
    'login_uri' => 'simplesaml/saml2/idp/SSOService.php',

    // Log out of the IdP after SLO
    'logout_after_slo' => env('LOGOUT_AFTER_SLO', false),

    // The URI to the saml metadata file, this describes your idP
    'issuer_uri' => 'saml/metadata',

    // Name of the certificate PEM file
    'certname' => 'cert.pem',

    // Name of the certificate key PEM file
    'keyname' => 'key.pem',

    // Encrypt requests and reponses
    'encrypt_assertion' => false,

    // Make sure messages are signed
    'messages_signed' => false,

    // list of all service providers
    'sp' => [
        // Base64 encoded ACS URL
        '' => [ // finalsite 는 AssertionConsumerServiceURL 값을 AuthnRequest 에서 넘겨주지 않아서 base64 값이 빈값이다.
            'destination' => 'https://seoulforeignorg.finalsite.com/integration/saml/ACS.cfm',
            'logout' => 'https://seoulforeignorg.finalsite.com/userlogin.cfm?do=logout',
            'certificate' => 'https://seoulforeignorg.finalsite.com/userlogin.cfm?do=logout',
        ],

        'aHR0cHM6Ly9zZW91bGZvcmVpZ25vcmcuZmluYWxzaXRlLmNvbS9pbnRlZ3JhdGlvbi9zYW1sL0FDUy5jZm0=' => [ // 정상일때는 이 배열..
            'destination' => 'https://seoulforeignorg.finalsite.com/integration/saml/ACS.cfm',
            'logout' => 'https://seoulforeignorg.finalsite.com/userlogin.cfm?do=logout',
        ],
    ],

    // If you need to redirect after SLO depending on SLO initiator
    // key is beginning of HTTP_REFERER value from SERVER, value is redirect path
    'sp_slo_redirects' => [
        // 'https://example.com' => 'https://example.com',
    ],

    // List of guards saml idp will catch Authenticated, Login and Logout events
    'guards' => ['web']
];
