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

$signingKey = SecurityUtils::loadPrivateKey(
                '<insert PKCS#12 key file path>',
                '<insert key alias>', 
                '<insert key password>');
```

### Creating the OAuth Authorization Header <a name="creating-the-oauth-authorization-header"></a>
The method that does all the heavy lifting is `OAuth::getAuthorizationHeader`. You can call into it directly and as long as you provide the correct parameters, it will return a string that you can add into your request's `Authorization` header.

```php
use Mastercard\Developer\OAuth\OAuth;

$consumerKey = '<insert consumer key>';
$uri = 'https://sandbox.api.mastercard.com/service';
$method = 'POST';
$payload = 'Hello world!';
$authHeader = OAuth::getAuthorizationHeader($uri, $method, $payload, $consumerKey, $signingKey);
```