<?php

/**
 * @copyright Copyright (c) Orbital Flight
 */

namespace orbitalflight\celigo\services;

use Craft;
use craft\base\Component;
use craft\helpers\App;
use orbitalflight\celigo\Celigo;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\ServerException;

class CeligoService extends Component {

    /**
     * call
     * Calls the Celigo My API service
     *
     * @param  mixed $handle 
     * @param  mixed $action (GET / POST ...)
     * @param  mixed $params
     * @return array
     */
    public function call(string $handle = null, string $action, array $params = null): array {

        $settings = Celigo::getInstance()->getSettings();

        // * A) Check handle and credentials
        if ($handle === null || $handle == "") {
            return ["error" => "Please provide an API handle."];
        }

        if (empty($settings->apis)) {
            return ["error" => "Please provide credentials in the plugin settings."];
        }

        // Get the requested endpoint credentials
        $endpoint = $this->_getEndpoint($handle);
        if (empty($endpoint)) {
            return ["error" => "Unknown API handle."];
        }

        // * B) Send the request
        try {
            $client = new Client();
            $response = $client->request($action, 'https://api.integrator.io/v1/apis/' . $endpoint['apiId'] . '/request', [
                'json' => $params,
                'headers' => [
                    'Authorization' => 'Bearer ' . $endpoint['apiToken'],
                    'accept' => 'application/json',
                    'content-type' => 'application/json',
                ],
                'timeout' => $settings->timeout,
            ]);

            // Return the response if JSON
            $responseBody = $response->getBody()->getContents();
            $decodedJSON = json_decode($responseBody, true);

            if ($decodedJSON !== null) {
                return json_decode($responseBody, true);
            } else {
                return [
                    'error' => "The received response is not a valid JSON.",
                    'errorDebug' => json_last_error(),
                ];
            }

            // * Catch potential errors
        } catch (ConnectException $e) {

            // Send a timeout error message
            return ['error' => "The request reached the maximum execution time."];
            
        } catch (ClientException $e) { // 4XX
            return $this->_formatError($e->getResponse());
        } catch (ServerException $e) { // 5XX
            return $this->_formatError($e->getResponse());
        }
    }
    
    /**
     * _formatError
     * Format the Guzzld error in a comprehensive array
     *
     * @param  mixed $response
     * @return array
     */
    protected function _formatError($response): array {
        $debugError['status'] = $response->getStatusCode();
        $debugError['reasonPhrase'] = $response->getReasonPhrase();
        $debugError['body'] = $response->getBody()->getContents();
        $debugError['headers'] = $response->getHeaders();
        $debugError['metadata'] = $response->getBody()->getMetadata();
        return [
            'error' => "The API server returned the following error: " . $response->getStatusCode() . " – " . $response->getReasonPhrase(),
            'errorBody' => json_decode($debugError['body'], true)['errors'][0],
            'errorDebug' => $debugError,
        ];
    }

    /**
     * _getEndpoint
     * Check if the provided handle exists in the plugin settings and returns the associated credentials.
     * 
     * @param  mixed $handle
     * @return array
     */
    private function _getEndpoint(string $handle): array {
        $endpoint = [];
        $apis = Celigo::getInstance()->getSettings()->apis;

        foreach ($apis as $api) {
            if ($api[0] === $handle) {
                $endpoint['apiId'] = App::parseEnv($api[1]);
                $endpoint['apiToken'] = App::parseEnv($api[2]);
            }
        }

        return $endpoint;
    }
}
