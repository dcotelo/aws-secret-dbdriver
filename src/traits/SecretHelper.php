<?php

namespace dcotelo\secretDBdriver\Traits;

use Aws\SecretsManager\SecretsManagerClient;
use Aws\Exception\AwsException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

trait SecretHelper
{

    public static function getSecret($secret)
    {
        $cacheKey = "database-config-" . $secret;
        $CacheMinutes = Config::get("secret.chache");
        $expiresIn = now()->addMinutes($CacheMinutes);

        //check if config is in cache
        if (Cache::has($cacheKey)) {
            //get from cache
            return Cache::get($cacheKey);
        }

        $client = new SecretsManagerClient([
            'version' => '2017-10-17',
            'region' => env('AWS_REGION'),
        ]);

        try {
            //dd($client);
            $result = $client->getSecretValue([
                'SecretId' => $secret,
            ]);
        } catch (AwsException $e) {
            $error = $e->getAwsErrorCode();
            if ($error == 'DecryptionFailureException') {
                // Secrets Manager can't decrypt the protected secret text using the provided AWS KMS key.
                // Handle the exception here, and/or rethrow as needed.
                throw $e;
            }
            if ($error == 'InternalServiceErrorException') {
                // An error occurred on the server side.
                // Handle the exception here, and/or rethrow as needed.
                throw $e;
            }
            if ($error == 'InvalidParameterException') {
                // You provided an invalid value for a parameter.
                // Handle the exception here, and/or rethrow as needed.
                throw $e;
            }
            if ($error == 'InvalidRequestException') {
                // You provided a parameter value that is not valid for the current state of the resource.
                // Handle the exception here, and/or rethrow as needed.
                throw $e;
            }
            if ($error == 'ResourceNotFoundException') {
                // We can't find the resource that you asked for.
                // Handle the exception here, and/or rethrow as needed.

                throw $e;
            }
            throw $e;
        }
        // Decrypts secret using the associated KMS CMK.
        // Depending on whether the secret is a string or binary, one of these fields will be populated.
        if (isset($result['SecretString'])) {
            $secret = $result['SecretString'];
            $configs = json_decode($result['SecretString'], true);
            if (isset($expiresIn)) {
                Cache::put($cacheKey, $configs, $expiresIn);
            }
            return $configs;
        }
    }
}
