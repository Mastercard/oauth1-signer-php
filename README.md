# oauth1-signer-php

[![Build Status](https://travis-ci.org/Mastercard/oauth1-signer-php.svg?branch=master)](https://travis-ci.org/Mastercard/oauth1-signer-php)
[![Packagist](https://img.shields.io/packagist/v/mastercard/oauth1-signer.svg)](https://packagist.org/packages/mastercard/oauth1-signer)
[![License: MIT](https://img.shields.io/badge/license-MIT-yellow.svg)](https://github.com/Mastercard/oauth1-signer-php/blob/master/LICENSE)

## Table of Contents
- [Overview](#overview)
  * [Compatibility](#compatibility)
  * [References](#references)
- [Usage](#usage)
  * [Prerequisites](#prerequisites)
  * [Adding the Library to Your Project](#adding-the-library-to-your-project)
  * [Loading the Signing Key](#loading-the-signing-key) 
  * [Creating the OAuth Authorization Header](#creating-the-oauth-authorization-header)
  * [Signing HTTP Client Request Objects](#signing-http-client-request-objects)
  * [Integrating with OpenAPI Generator API Client Libraries](#integrating-with-openapi-generator-api-client-libraries)
  
## Overview <a name="overview"></a>
Zero dependency library for generating a Mastercard API compliant OAuth signature.

### Compatibility <a name="compatibility"></a>
PHP 5.6+

### References <a name="references"></a>
* [OAuth 1.0a specification](https://tools.ietf.org/html/rfc5849)
* [Body hash extension for non application/x-www-form-urlencoded payloads](https://tools.ietf.org/id/draft-eaton-oauth-bodyhash-00.html)

## Usage <a name="usage"></a>
### Prerequisites <a name="prerequisites"></a>
Before using this library, you will need to set up a project in the [Mastercard Developers Portal](https://developer.mastercard.com). 

As part of this set up, you'll receive credentials for your app:
* A consumer key (displayed on the Mastercard Developer Portal)
* A private request signing key (matching the public certificate displayed on the Mastercard Developer Portal)

### Adding the Library to Your Project <a name="adding-the-library-to-your-project"></a>

```shell
composer require mastercard/oauth1-signer
composer dump-autoload -o
```

### Loading the Signing Key <a name="loading-the-signing-key"></a>

A private key object can be created by calling the `SecurityUtils::loadPrivateKey` function:

```php
use Mastercard\Developer\OAuth\Utils\SecurityUtils;
// ...
$signingKey = SecurityUtils::loadPrivateKey(
                '<insert PKCS#12 key file path>',
                '<insert key alias>', 
                '<insert key password>');
```

### Creating the OAuth Authorization Header <a name="creating-the-oauth-authorization-header"></a>
The method that does all the heavy lifting is `OAuth::getAuthorizationHeader`. You can call into it directly and as long as you provide the correct parameters, it will return a string that you can add into your request's `Authorization` header.

```php
use Mastercard\Developer\OAuth\OAuth;
// ...
$consumerKey = '<insert consumer key>';
$uri = 'https://sandbox.api.mastercard.com/service';
$method = 'POST';
$payload = 'Hello world!';
$authHeader = OAuth::getAuthorizationHeader($uri, $method, $payload, $consumerKey, $signingKey);
```

### Signing HTTP Client Request Objects <a name="signing-http-client-request-objects"></a>

Alternatively, you can use helper classes for some of the commonly used HTTP clients.

These classes, provided in the `Mastercard\Developer\Signers\` namespace, will modify the provided request object in-place and will add the correct `Authorization` header. Once instantiated with a consumer key and private key, these objects can be reused. 

Usage briefly described below, but you can also refer to the test namespace for examples. 

+ [GuzzleHttp](#guzzlehttp)

#### GuzzleHttp <a name="guzzlehttp"></a>
```php
use GuzzleHttp\Psr7\Request;
use Mastercard\Developer\Signers\PsrHttpMessageSigner;
// ...
$body = '{"foo":"bår"}';
$headers = ['Content-Type' => 'application/json'];
$request = new Request('POST', 'https://sandbox.api.mastercard.com/service', $headers, $body);
$signer = new PsrHttpMessageSigner($consumerKey, $signingKey);
$signer.sign($request);
```

### Integrating with OpenAPI Generator API Client Libraries <a name="integrating-with-openapi-generator-api-client-libraries"></a>

[OpenAPI Generator](https://github.com/OpenAPITools/openapi-generator) generates API client libraries from [OpenAPI Specs](https://github.com/OAI/OpenAPI-Specification). 
It provides generators and library templates for supporting multiple languages and frameworks.

Generators currently supported:
+ [php](#php)

See also: [CONFIG OPTIONS for php](https://github.com/OpenAPITools/openapi-generator/blob/master/docs/generators/php.md).

#### php <a name="php"></a>

##### OpenAPI Generator

```shell
java -jar openapi-generator-cli.jar generate -i openapi-spec.yaml -g php -o out
```

##### Usage of the PsrHttpMessageSigner

```php
use GuzzleHttp;
use OpenAPI\Client\Api\ServiceApi;
use Mastercard\Developer\Signers\PsrHttpMessageSigner;
// ...
$stack = new GuzzleHttp\HandlerStack();
$stack->setHandler(new GuzzleHttp\Handler\CurlHandler());
$stack->push(GuzzleHttp\Middleware::mapRequest([new PsrHttpMessageSigner($consumerKey, $signingKey), 'sign']));
$options = ['handler' => $stack];
$client = new GuzzleHttp\Client($options);
$config = new Configuration();
$config->setHost('https://sandbox.api.mastercard.com');
$serviceApi = new ServiceApi($client, $config);
// ...
```


