# Oro Platform Behat extension

[![Latest Version](https://img.shields.io/github/release/indigophp/oro-behat-extension.svg?style=flat-square)](https://github.com/indigophp/oro-behat-extension/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Build Status](https://img.shields.io/travis/indigophp/oro-behat-extension.svg?style=flat-square)](https://travis-ci.org/indigophp/oro-behat-extension)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/indigophp/oro-behat-extension.svg?style=flat-square)](https://scrutinizer-ci.com/g/indigophp/oro-behat-extension)
[![Quality Score](https://img.shields.io/scrutinizer/g/indigophp/oro-behat-extension.svg?style=flat-square)](https://scrutinizer-ci.com/g/indigophp/oro-behat-extension)
[![Total Downloads](https://img.shields.io/packagist/dt/indigophp/oro-behat-extension.svg?style=flat-square)](https://packagist.org/packages/indigophp/oro-behat-extension)

**Behat extension for Oro Platform.**


## Install

Via Composer

``` bash
$ composer require indigophp/oro-behat-extension
```


## Usage

This package provides some utilities to ease writting [Functional Tests](https://www.orocrm.com/documentation/index/current/book/functional-tests)
using [Behat](http://docs.behat.org) for [Oro Platform](https://www.orocrm.com/oro-platform) based applications.


Supported features:

- [Database Isolation](https://www.orocrm.com/documentation/index/current/book/functional-tests#database-isolation)*
- [Database Reindex](https://www.orocrm.com/documentation/index/current/book/functional-tests#database-reindex)*
- Setting WSSE header

_* Unlike the original Oro behavior, transaction start-rollback and reindexing are done per scenario, not per feature (test case)_


First, you need to configure Behat and the [Symfony2 Extension](https://github.com/Behat/Symfony2Extension/blob/master/doc/index.rst):

``` yaml
default:
    # ...
    extensions:
        Behat\Symfony2Extension: ~
```


After that, you need to configure a bundle and load the Oro Context:

``` yaml
default:
    suites:
        acme:
            type: symfony_bundle
            contexts:
                - Indigo\Oro\Behat\Context\OroContext
                - Acme\Bundle\AcmeBundle\Features\Context\FeatureContext
            bundle: AcmeBundle
    # ...
```


You can now write your features:

```
@dbIsolation
Feature: I do something
    In order to something
    As someone
    I should be able to do that thing

    @wsse
    Scenario: I do the thing using the REST API
        Given I am someone
        When I do the thing using the REST API
        Then the thing should have been done

    @dbReindex
    Scenario: I search for something
        Given I am someone
        When I search for something
        Then I should see that thing
```


## Testing

``` bash
$ composer test
```


## Contributing

Please see our contributing guide on [developers.indigophp.com](http://developers.indigophp.com).


## Security

If you discover any security related issues, please contact us at [security@indigophp.com](mailto:security@indigophp.com).


## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
