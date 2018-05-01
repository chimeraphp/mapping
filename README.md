# Chimera - mapping

[![Total Downloads](https://img.shields.io/packagist/dt/lcobucci/chimera-mapping.svg?style=flat-square)](https://packagist.org/packages/lcobucci/chimera-mapping)
[![Latest Stable Version](https://img.shields.io/packagist/v/lcobucci/chimera-mapping.svg?style=flat-square)](https://packagist.org/packages/lcobucci/chimera-mapping)
[![Unstable Version](https://img.shields.io/packagist/vpre/lcobucci/chimera-mapping.svg?style=flat-square)](https://packagist.org/packages/lcobucci/chimera-mapping)

![Branch master](https://img.shields.io/badge/branch-master-brightgreen.svg?style=flat-square)
[![Build Status](https://img.shields.io/travis/lcobucci/chimera-mapping/master.svg?style=flat-square)](http://travis-ci.org/#!/lcobucci/chimera-mapping)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/lcobucci/chimera-mapping/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/lcobucci/chimera-mapping/?branch=master)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/lcobucci/chimera-mapping/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/lcobucci/chimera-mapping/?branch=master)

> The term Chimera (_/kɪˈmɪərə/_ or _/kaɪˈmɪərə/_) has come to describe any
mythical or fictional animal with parts taken from various animals, or to
describe anything composed of very disparate parts, or perceived as wildly
imaginative, implausible, or dazzling.

There are many many amazing libraries in the PHP community and with the creation
and adoption of the PSRs we don't necessarily need to rely on full stack
frameworks to create a complex and well designed software. Choosing which
components to use and plugging them together can sometimes be a little
challenging.

The goal of this set of packages is to make it easier to do that (without
compromising the quality), allowing you to focus on the behaviour of your
software.

This package provides a set of annotations to be used to configured your
command/query handlers, HTTP middleware, and command/query bus middleware.

**Important:** these annotations will only be used when used together with
packages that read them, like [`lcobucci/chimera-di-symfony`](https://github.com/lcobucci/chimera-di-symfony).

## Installation

Package is available on [Packagist](http://packagist.org/packages/lcobucci/chimera-mapping),
you can install it using [Composer](http://getcomposer.org).

```shell
composer require lcobucci/chimera-mapping
```

### PHP Configuration

In order to make sure that we're dealing with the correct data, we're using `assert()`,
which is a very interesting feature in PHP but not often used. The nice thing
about `assert()` is that we can (and should) disable it in production mode so
that we don't have useless statements.

So, for production mode, we recommend you to set `zend.assertions` to `-1` in your `php.ini`.
For development you should leave `zend.assertions` as `1` and set `assert.exception` to `1`, which
will make PHP throw an [`AssertionError`](https://secure.php.net/manual/en/class.assertionerror.php)
when things go wrong.

Check the documentation for more information: https://secure.php.net/manual/en/function.assert.php

## Usage

These are the annotations related to how services should be mapped to the
command/query bus:

* `Lcobucci\Chimera\Mapping\ServiceBus\CommandHandler`: exposes the class as
a command handler
* `Lcobucci\Chimera\Mapping\ServiceBus\QueryHandler`: exposes the class as
a query handler
* `Lcobucci\Chimera\Mapping\ServiceBus\Middleware`: exposes the class as
a command/query middleware

And these are the ones related to how services should be mapped to the
PSR-15 application:

* `Lcobucci\Chimera\Mapping\Routing\CreateAndFetchEndpoint`: adds an endpoint
that will execute a command to create a resource and then a query to fetch
its state
* `Lcobucci\Chimera\Mapping\Routing\CreatexEndpoint`: adds an endpoint
that will execute a command to create a resource
* `Lcobucci\Chimera\Mapping\Routing\ExecuteAndFetchEndpoint`: adds an endpoint
that will execute a command to modify a resource and then a query to fetch
its state
* `Lcobucci\Chimera\Mapping\Routing\ExecuteEndpoint`: adds an endpoint
that will execute a command to modify/remove a resource
* `Lcobucci\Chimera\Mapping\Routing\FetchEndpoint`: adds an endpoint
that will execute a query
* `Lcobucci\Chimera\Mapping\Routing\Middleware`: exposes the class as
a PSR-15 HTTP middleware

The idea of the annotations is to simplify the mapping of services, removing
complex tags from the dependency injection container file. They should essentially
be used like this:

```php
<?php
declare(strict_types=1);

namespace Lcobucci\MyApi\Creation;

use Lcobucci\Chimera\Mapping as Chimera;

/**
 * @Chimera\Routing\CreateEndpoint(path="/books", command=AddToCollection::class, name="book.create", redirectTo="book.fetch_one")
 * @Chimera\ServiceBus\CommandHandler(handles=AddToCollection::class)
 */
final class AddToCollectionHandler
{
    // ...
}
```

## License

MIT, see [LICENSE file](https://github.com/lcobucci/chimera-mapping/blob/master/LICENSE).
