<?php
/*
=====================================================
 Copyright (c) 2020 NIKBA.COM
=====================================================
 File: BaseApi.php
=====================================================
*/
namespace Nikba;

use Directus\Util\ArrayUtils;
use GuzzleHttp\Client as HTTPClient;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Uri;
use GuzzleHttp\Psr7\UriResolver;

class BaseApi {

    /**
     * Directus Server base endpoint
     *
     * @var string
     */
    protected $baseEndpoint;

    /**
     * Authentication Token
     *
     * @var string
     */
    protected $accessToken;

    /**
     * HTTP Client
     *
     * @var \GuzzleHttp\Client
     */
    protected $httpClient;

    /**
     * HTTP Client request timeout
     *
     * @var int
     */
    protected $timeout = 60;

    #End Points
    const ITEMS_ENDPOINT        = 'items/%s';
    const ITEM_ENDPOINT         = 'items/%s/%s';
    const FILES_ENDPOINT        = 'files';
    const FILE_ENDPOINT         = 'files/%s';
    const ASSETS_ENDPOINT       = 'assets/%s';
    const COLLECTIONS_ENDPOINT  = 'collections';
    const FIELDS_ENDPOINT       = 'fields';
    const MAIL_ENDPOINT         = 'mail';
    const HASH_ENDPOINT         = 'utils/hash';
    const CHHASH_ENDPOINT       = 'utils/hash/match';
    const STRING_ENDPOINT       = 'utils/random/string';

    #Class Constructor
    public function __construct($config) {
        $this->accessToken  = $config['token'];
        $this->accessUrl    = rtrim($config['base'], '/');   
        $this->baseEndpoint = $this->accessUrl;

        $this->setHTTPClient($this->getDefaultHTTPClient());
    }

    /**
     * Get the base endpoint url
     *
     * @return string
     */
    public function getBaseEndpoint()
    {
        return $this->baseEndpoint;
    }

    /**
     * Get the base url
     *
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    /**
     * Get the authentication access token
     *
     * @return string
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * Set a new authentication access token
     *
     * @param $newAccessToken
     */
    public function setAccessToken($newAccessToken)
    {
        $this->accessToken = $newAccessToken;
    }

    /**
     * Get the Directus hosted instance key
     *
     * @return null|string
     */
    public function getInstanceKey()
    {
        return $this->instanceKey;
    }

    /**
     * Set the HTTP Client
     *
     * @param HTTPClient $httpClient
     */
    public function setHTTPClient(HTTPClient $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * Get the HTTP Client
     *
     * @return HTTPClient|null
     */
    public function getHTTPClient()
    {
        return $this->httpClient;
    }

    /**
     * Get the default HTTP Client
     *
     * @return HTTPClient
     */
    public function getDefaultHTTPClient()
    {
        $baseUrlAttr = 'base_uri';

        return new HTTPClient([
            $baseUrlAttr => rtrim($this->baseEndpoint, '/') . '/'
        ]);
    }


    /**
     * Build a endpoint path based on a format
     *
     * @param string $pathFormat
     * @param string|array $variables
     *
     * @return string
     */
    public function buildPath($pathFormat, $variables = [])
    {
        return vsprintf(ltrim($pathFormat, '/'), $variables);
    }

    /**
     * Perform a HTTP Request
     *
     * @param $method
     * @param $path
     * @param array $params
     *
     * @return Entry|EntryCollection
     *
     * @throws UnauthorizedRequestException
     */
    public function performRequest($method, $path, array $params = [])
    {
        $request = $this->buildRequest($method, $path, $params);

        try {
            $response = $this->httpClient->send($request);
            
            #Return Response as file content
            if(isset($params['response']) && $params['response']=='asset') {
                $content = $response->getBody()->getContents();
            }
            #Return Response as object
            else {
                $content = json_decode($response->getBody()->getContents());
                #$content = json_decode($response->getBody()->getContents(), true); Return as Array
            }

        } catch (ClientException $ex) {
            if ($ex->getResponse()->getStatusCode() == 401) {
                if ($this->isPsr7Version()) {
                    $uri = $request->getUri();
                } else {
                    $uri = $request->getUrl();
                }

                $message = sprintf('Unauthorized %s Request to %s', $request->getMethod(), $uri);

                throw new UnauthorizedRequestException($message);
            }

            throw $ex;
        }

        return $content;
    }

    /**
     * Build a request object
     *
     * @param $method
     * @param $path
     * @param $params
     *
     * @return \GuzzleHttp\Message\Request|Request
     */
    public function buildRequest($method, $path, array $params = [])
    {
        $body = ArrayUtils::get($params, 'body', null);
        $query = ArrayUtils::get($params, 'query', null);
        $options = [];

        if (in_array($method, ['POST', 'PUT', 'PATCH']) && $body) {
            $options['body'] = $body;
        }

        if ($query) {
            $options['query'] = $query;
        }

        return $this->createRequest($method, $path, $options);
    }

    /**
     * Creates a request for 5.x or 6.x guzzle version
     *
     * @param $method
     * @param $path
     * @param $options
     *
     * @return \GuzzleHttp\Message\Request|\GuzzleHttp\Message\RequestInterface|Request
     */
    public function createRequest($method, $path, $options)
    {
        $headers = [
            'Content-Type'  => 'application/json',
            'Authorization' => 'Bearer ' . $this->getAccessToken(),
        ];

        $body = ArrayUtils::get($options, 'body', null);
        $uri = UriResolver::resolve(new Uri($this->getBaseEndpoint() . '/'), new Uri($path));

        if ($body) {
            $body = json_encode($body);
        }

        if (ArrayUtils::has($options, 'query')) {
            $query = $options['query'];

            if (is_array($query)) {
                $query = http_build_query($query, null, '&', PHP_QUERY_RFC3986);
            }

            if (!is_string($query)) {
                throw new \InvalidArgumentException('query must be a string or array');
            }

            $uri = $uri->withQuery($query);
        }

        $request = new Request($method, $uri, $headers, $body);

        return $request;
    }
}