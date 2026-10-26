<?php

/*
 * As credenciais da service account podem vir de:
 * 1. ANALYTICS_CREDENTIALS_BASE64: JSON codificado em base64 (recomendado no .env);
 * 2. ANALYTICS_CREDENTIALS_JSON: JSON em uma linha;
 * 3. arquivo storage/app/analytics/service-account-credentials.json.
 */
$analyticsCredentials = storage_path('app/analytics/service-account-credentials.json');

$analyticsCredentialsBase64 = env('ANALYTICS_CREDENTIALS_BASE64');
$analyticsCredentialsJson = env('ANALYTICS_CREDENTIALS_JSON');

if (! empty($analyticsCredentialsBase64)) {
    $decodedCredentials = json_decode(base64_decode($analyticsCredentialsBase64, true), true);
    $analyticsCredentials = is_array($decodedCredentials) ? $decodedCredentials : $analyticsCredentials;
} elseif (! empty($analyticsCredentialsJson)) {
    $decodedCredentials = json_decode($analyticsCredentialsJson, true);
    $analyticsCredentials = is_array($decodedCredentials) ? $decodedCredentials : $analyticsCredentials;
}

return [

    /*
     * The view id of which you want to display data.
     */
    'property_id' => env('ANALYTICS_PROPERTY_ID'),

    /*
     * Path to the client secret json file or an array with the credentials.
     * Veja o bloco acima para as formas de configuração aceitas.
     */
    'service_account_credentials_json' => $analyticsCredentials,

    /*
     * The amount of minutes the Google API responses will be cached.
     * If you set this to zero, the responses won't be cached at all.
     */
    'cache_lifetime_in_minutes' => 60 * 24,

    /*
     * Here you may configure the "store" that the underlying Google_Client will
     * use to store it's data.  You may also add extra parameters that will
     * be passed on setCacheConfig (see docs for google-api-php-client).
     *
     * Optional parameters: "lifetime", "prefix"
     */
    'cache' => [
        'store' => 'file',
    ],
];
