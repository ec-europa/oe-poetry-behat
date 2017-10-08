# Poetry Client Behat Extension

[![Build Status](https://travis-ci.org/ec-europa/oe-poetry-behat.svg?branch=master)](https://travis-ci.org/ec-europa/oe-poetry-behat)

Behat extension for [Poetry Client](https://github.com/ec-europa/oe-poetry-client).

## Usage

Load the Poetry Behat Extension and context as shown below:

```yaml
default:
  suites:
    default:
      contexts:
        - EC\Behat\PoetryExtension\Context\PoetryContext
  extensions:
    EC\Behat\PoetryExtension:
      poetry:
        base_url: http://localhost/test-site    
```
