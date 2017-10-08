# Poetry Client Behat Extension

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
