# Poetry Client Behat Extension

[![Build Status](https://travis-ci.org/ec-europa/oe-poetry-behat.svg?branch=master)](https://travis-ci.org/ec-europa/oe-poetry-behat)

Behat extension for [Poetry Client](https://github.com/ec-europa/oe-poetry-client).

## Usage

Load and configure the Poetry Behat Extension and context as shown below:

```yaml
default:
  suites:
    default:
      contexts:
        - 'EC\Behat\PoetryExtension\Context\PoetryContext'
  extensions:
    EC\Behat\PoetryExtension:
      client:
        base_url: 'http://local.dev'  # Required. Application base URL running Poetry Client library.
        endpoint: '/my-endpoint'      # Required. Notification endpoint on your application.
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
      server:
        host: 'localhost'             # Optional. Host where mock Poetry server will be running.
        port: '28080'                 # Optional. Mock Poetry server port.
        endpoint: '/service'          # Optional. Mock Poetry server endpoint.
      client:
        base_url: 'http://local.dev'  # Required. Application base URL running Poetry Client library.
        endpoint: '/my-endpoint'      # Required. Notification endpoint on your application.
        username: 'username'          # Optional. Username required for the mock service to authenticate on your application.
        password: 'password'          # Optional. Password required for the mock service to authenticate on your application.
```

All configuration options can be overridden in your Behat scenarios.
