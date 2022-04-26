<?php
/**
 * Integration with FLow API v3.0.1
 *
 */
namespace App\Integrations\Bci;

use App\Integrations\Bci\Exceptions\BciError;
use App\Integrations\Bci\Utils\Utils;
use Illuminate\Support\Facades\{Auth, Log, Http};
use Illuminate\Support\{Arr, Str};

class Bci
{
    /**
     * Establece si se usara el sandbox o production
     * 
     * @var bool
     */
    const SANBOX = false;

    /**
     * Url base de production
     * 
     * @var string
     */
    const API_URL = 'https://apiprogram.bci.cl/:enviroment';

    /**
     * Uri base de la api
     * 
     * @var string
     */
    protected $baseUrl;

    /**
     * Endpoint de la peticion a realizar
     * 
     * @var string
     */
    public $endpoint;

    /**
     * BCI key
     * 
     * @var string
     */
    private $key;

    /**
     * BCI secret
     * 
     * @var string
     */
    private $secret;

    /**
     * BCI App id
     * 
     * @var string
     */
    private $appId;

    /**
     * Return uri
     *
     * @var string
     */
    public $returntUri;

    /**
     * Bearer token
     *
     * @var string
     */
    private $token;

    /**
     * Response de la peticion
     *
     * @var \Illuminate\Http\Client\Response
     */
    private $response;

    public function __construct($sandbox = null)
    {
        $sandbox = $sandbox ?? config('bci.sandbox');

        $this->setSandbox($sandbox)
            ->setCredentials()
            ->setReturnUri()
            ->setBaseUrl()
            ->setBearerToken();
    }

    /**
     * Establecer credenciales (Key, Secret)
     */
    private function setCredentials()
    {
        $this->key = config('bci.key');
        $this->secret = config('bci.secret');
        $this->appId = config('bci.app_id');

        return $this;
    }

    /**
     * Establecer credenciales (Key, Secret)
     */
    private function setReturnUri()
    {
        $this->returnUri = config('bci.return_uri');

        return $this;
    }

    /**
     * Undocumented function
     *
     * @return string
     */
    private function getEnviroment()
    {
        return $this->sandbox ? 'sandbox' : 'prod';
    }

    /**
     * Establecer url del servidor
     */
    private function setBaseUrl()
    {
        $this->baseUrl = Str::replace(':enviroment', $this->getEnviroment(), Str::finish(static::API_URL, '/'));

        return $this;
    }

    /**
     * Establecer Sandbox
     *
     * @param  bool  $sandbox
     */
    public function setSandbox(bool $sandbox = true)
    {
        $this->sandbox = $sandbox;

        return $this;
    }

    /**
     * Construir la url con el endpoint especificado
     * 
     * @param  string  $endpoint
     * @param  array  $params
     * @return void
     */
    private function buildEndpoint(string $endpoint, array $params = [])
    {
        $endpoint = Str::startsWith($endpoint, '/') ? mb_substr($endpoint, 1) : $endpoint;

        if(Str::contains($endpoint, '?')){
            $endpoint = Str::replaceArray('?', $params, $endpoint);
        }

        $this->endpoint = $this->baseUrl.$endpoint;
    }

    /**
     * Establecer el response de la peticion
     * 
     * @param  array|null  $response
     * @return void
     */
    private function setResponse($response)
    {
      $this->response = $response;
    }

    /**
     * Obtener el response de la peticion
     *
     * @param  bool  $asjson
     * @return array|null
     */
    public function getResponse()
    {
        return $this->getRawResponse(true);
    }

    /**
     * Obtener el objeto completo del response de la peticion
     * 
     * @param  bool  $asJson
     * @return \Illuminate\Http\Client\Response|array|null
     */
    public function getRawResponse($asJson = false)
    {
        return $asJson ? $this->response->json() : $this->response;
    }

    /**
     * Manejar los errores al realizar peticiones a BCi
     * 
     * @param  string  $message
     * @param  array  $attributes
     * @return void
     */
    private function handleBciError(string $message, array $attributes = [])
    {
        $response = $this->getRawResponse();
        $attributes = [
            'user' => Auth::id(),
            'sandbox' => $this->sandbox,
            'attributes' => $attributes,
            'response' => [
                'code' => $response->status(),
                'json' => $response->json(),
            ],
        ];

        Log::channel('integrations')
            ->error($message, $attributes);

        throw BciError::error($message);
    }

    /**
     * Undocumented function
     *
     * @return $this
     */
    private function setBearerToken()
    {
        $this->token = $this->getBearerToken()['access_token'];

        return $this;
    }

    /**
     * Undocumented function
     *
     * @return array
     */
    private function getBearerToken()
    {
        $this->buildEndpoint('v1/api-oauth/token');

        $requestJwt = Utils::buildJWT([
            'iss' => $this->key,
            'credentials' => $this->secret,
        ]);

        $params = [
            'grant_type' => 'client_credentials',
            'redirect_uri' => $this->returnUri,
            'scope' => 'access-requests',
            'client_assertion_type' => 'urn:ietf:params:oauth:client-assertion-type:jwt-beare',
            'client_assertion' => $requestJwt,
        ];

        $this->setResponse(Http::asForm()->post($this->endpoint, $params));

        if($this->getRawResponse()->failed()){
            $this->handleBciError('Error al soliticar Bearer token');
        }

        return $this->getResponse();
    }

    protected function generateAccessRequestId()
    {
        $this->buildEndpoint('v1/api-access-requests/requests');

        $params = [
            'Data' =>[
                'TppId' => $this->appId,
                'Scope' => 'transactions',
            ]
        ];

        $this->setResponse(
            Http::withToken($this->token)
            ->withHeaders([
                'Accept' => 'application/json'
            ])
            ->asForm()
            ->bodyFormat('none')
            ->send('POST', $this->endpoint, [
                'body' => json_encode($params),
            ])
        );

        if($this->getRawResponse()->failed()){
            $this->handleBciError('Error al soliticar Access Request ID', ['token' => $this->token]);
        }

        return $this->getResponse();
    }


    /**
     * Request
     * 
     * @param  string  $requestId
     * @return array
     */
    public function requestAuthorize()
    {        
        $requestId = $this->generateAccessRequestId()['Request']['RequestId'];
        $this->buildEndpoint('v1/api-oauth/authorize');

        $state = Utils::generateState();
        $nonce = Utils::generateNonce();
        $requestJwt = $this->signAuthorizePaypload($state, $nonce, $requestId);
        
        $params = [
            'response_type' => 'code',
            'client_id' => $this->key,
            'redirect_uri' => $this->returnUri,
            'state' => $state,
            'nonce' => $nonce,
            'scope' => 'transactions',
            'request' => $requestJwt,
        ];
        
        Log::channel('integrations')->info('Request Autorize', [
            'endpoint' => $this->endpoint,
            'requestId' => $requestId,
            'param' => $params,
        ]);

        $onRedirect = function($request, $response, $uri
        ) {
            echo 'Redirecting! ' . $request->getUri() . ' to ' . $uri . "\n";
            dd($request->getUri(), $uri);
        };
        $this->setResponse(
            Http::withHeaders([
                'x-apikey' => $this->key,
            ])
            ->asJson()
            ->send('POST', $this->endpoint, [
                'query' => $params,
                'allow_redirects' => [
                    'strict' => true,
                    'on_redirect'     => $onRedirect,
                ],
            ])
        );

        if($this->getRawResponse()->failed()){
            $this->handleBciError('Error al consultar request', ['requestID' => $requestId]);
        }

        return $this->getResponse();
    }

    /**
     * Undocumented function
     * 
     * @param  string  $requestId
     * @return array
     */
    private function signAuthorizePaypload(string $state, string $nonce, string $requestId)
    {
        $payload = [
            'iss' => 'https://api.openbank.com',
            'response_type' => 'code',
            'client_id' => $this->key,
            'redirect_uri' => $this->returnUri,
            'state' => $state,
            'nonce' => $nonce,
            'scope' => 'transactions',
            'claims' => [
                'id_token' => [
                    'openbanking_intent_id' => [
                        'value' => 'urn:openbank:intent:transactions:' . $requestId,
                        'essential' => true
                    ],
                    'acr' => [
                        'essential' => true
                    ],
                ]
            ]
        ];

        return Utils::buildJWT($payload);
    }
}
