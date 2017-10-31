# Poetry Client Behat Extension

[![Build Status](https://travis-ci.org/ec-europa/oe-poetry-behat.svg?branch=master)](https://travis-ci.org/ec-europa/oe-poetry-behat)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/ec-europa/oe-poetry-behat/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/ec-europa/oe-poetry-behat/?branch=master)

Behat extension for the European Commission's [Poetry Client](https://github.com/ec-europa/oe-poetry-client).

## Setup

Load the Poetry context and configure the extension as shown below:

```yaml
default:
  suites:
    default:
      contexts:
        - 'EC\Behat\PoetryExtension\Context\PoetryContext'
  extensions:
    EC\Behat\PoetryExtension:
      application:
        base_url: 'http://local.dev'  # Required: application base URL running Poetry Client library.
        endpoint: '/my-endpoint'      # Required: notification endpoint on your application.
```

The following extensive configuration allows you to further tweak the extension's behaviour:

```yaml
default:
  suites:
    default:
      contexts:
        - 'EC\Behat\PoetryExtension\Context\PoetryContext'
  extensions:
    EC\Behat\PoetryExtension:
      application:
        base_url: 'http://local.dev'  # Required: application base URL running the Poetry Client library.
        endpoint: '/my-endpoint'      # Required: notification endpoint for your application.
      service:
        host: 'localhost'             # Optional: host where mock Poetry service will be running, defaults to `localhost`.
        port: '28080'                 # Optional: mock Poetry service port, defaults to `28080`.
        endpoint: '/service'          # Optional: mock Poetry service endpoint, defaults to `/service`.
        wsdl: '/wsdl'                 # Optional: mock Poetry service WSDL endpoint, defaults to `/wsdl`.
        username: 'username'          # Optional: username used by the mock service to authenticate on your application, defaults to `username`.
        password: 'password'          # Optional: password used by the mock service to authenticate on your application, defaults to `password`.
```

Service parameters can be also overridden in your Behat scenarios (see below).

## Usage

All scenarios and/or features that wish to use the extension's steps will need to be tagged with `@poetry`.

To send a raw XML notification message to the client endpoint use:

```gherkin
When Poetry notifies the client with the following XML:
"""
<?xml version="1.0" encoding="UTF-8"?>
...
"""
```

Or, if you want to express the message in a `withArray()` format, use:

```gherkin
When Poetry notifies the client with the following "notification.translation_received" message:
"""
identifier:
  code: "WEB"
  year: "2017"
  number: "40012"
  ...
"""
```

To setup test responses for the Poetry server use:

```gherkin
Given Poetry will return the following XML response:
"""
<?xml version="1.0" encoding="utf-8"?><POETRY xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="http://intragate.ec.europa.eu/DGT/poetry_services/poetry.xsd">
    <request communication="synchrone" id="WEB/2017/40029/0/0/TRA" type="status">
        <demandeId>
            <codeDemandeur>WEB</codeDemandeur>
            <annee>2017</annee>
            ...
"""
```

Or, if you want to express the message in a `withArray()` format, use:

```gherkin
Given Poetry will return the following "response.status" message response:
"""
identifier:
  code: WEB
  year: 2017
  number: 40029
  version: 0
  ...
"""
```

Client responses can be asserted by using the following step:

```gherkin
Then client response contains the following text:
  | <codeDemandeur>WEB</codeDemandeur> |
  | <annee>2017</annee>                |
  | <statusMessage>OK</statusMessage>  |
```

Or, if you want to assert XML portions, use:

```gherkin
And Poetry service received request should contain the following XML portion:
"""
<documentSource format="HTML" legiswrite="No">
    <documentSourceName>content.html</documentSourceName>
    <documentSourceFile>BASE64ENCODEDFILECONTENT</documentSourceFile>
    <documentSourceLang lgCode="EN">
        <documentSourceLangPages>1</documentSourceLangPages>
    </documentSourceLang>
</documentSource>
"""
```

Application parameters can be overridden by using the following step:

```gherkin
When Poetry service uses the following settings:
"""
  username: foo
  password: bar
"""
```

For more detailed examples please refer to the Poetry Behat Extension's [tests features](features) baring in mind that
steps beginning with `Given the test application...` are only used to test the extension itself and, thus, not available
to the extension users.
