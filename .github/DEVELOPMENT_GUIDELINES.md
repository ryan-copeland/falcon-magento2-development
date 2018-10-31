# Deity Magento 2 module guidelines

## Introduction

Deity Magento2 module (hereinafter referred to as `DM2`) should provide all the API interfaces required by `deity-core` in order to integrate Magento2 functionalities.
By API interface we both mean standard JSON/REST API calls and GraphQL integrations provided by the coming 2.3 version of Magento.

### Notes on compatibility

- Compatiblity should be granted starting from Magento 2.2 . We can consider Magento 2.0 and 2.1 as too outdated and lacking some of the core features we may need to develop an high quality integration.
- Minimum PHP version should be 7.1 .

## Technical vision

The DM2 integration should **not be represented by a single module**, but by a **set of different modules** with well **defined and isolated responsabilities**.
Each module dependency must be explicited in the `composer.json` and each module sequence constraint must be explicited in `modules.xml`.

Modules will be divided in different types:

1. API modules
2. Concrete modules
3. Admin/Frontend UI modules

### API modules

API modules are responsible for the **REST API calls definition** of each module exposing a REST API.
The module name must follow the scheme of "*ModuleName*Api" (e.g.: "Deity_CatalogApi").

> This approach will **decouple the module implementation by the module contract** and will give the possibility to replace the implementation itself without breaking any compatibility in future releases.

Api modules should only contain **API interface classes** without any real implementation.
The only exception for real implementation may be represented by **Chain** or **Pool** classes if needed.

Each change to an API module will result in a **module version change** following this scheme:

- A new public method: **Minor version increment**
- Removal or change of an existing public method: **Major version increment**

Following the Magento2 guidelines, each public interface in API modules should be marked with the `@api` annotation.

Example:
```
...

/**
 * ...
 * @api
 */
interface MyInterface
...
```

### Concrete modules

A concrete module represents the real module implementation. If the module is exposing at least one REST API endpoint, then it must depend at least on one **API module** defining the interface.

A concrete module **should not include any Interface class other than SPI classes** if needed. More in general it **MUST NOT** contain any `Api` folder.

Since each interface in Magento 2 represents a potential service contract, the amount of SPI interfaces in a concrete module should be kept **as low as possible**.

### Admin/Frontend UI modules

If any Magento 2 frontend or adminhtml modification is required, then an additional module should be implmeented with such features.

> Most likely DM2 will not have so many impacts on the frontend or the adminhtml. The amount here will be probably around 1-2 modules.

### Modules dependencies

Each module should reduce at minimum its dependencies and prefer one **API module** to a **Concrete module**.
A circular module dependency must be fixed by refactoring the module itself or by adding a new module.

### Testing

Each module should be provided with at least:
- Magento2 integration tests (at least for non-API features)
- Magento2 API functional tests (for each single API endpoint)

## Modules fragmentation example

As previously explained, each module should handle a single and well defined responsability.

For example, the module handling the catalog calls and thus depending on `Magento_Catalog` should be defined by:

- `Deity_Catalog`
- `Deity_CatalogApi`

Module dependency should be as follow:


**app/code/Deity/CatalogApi/composer.json:**

```
{
  "name": "deity/module-catalog-api",
  "description": "N/A",
  "require": {
    "php": "~7.1.0||~7.2.0",
    "magento/module-catalog": "*"
  },
  "type": "magento2-module",
  "license": [
    "OSL-3.0",
    "AFL-3.0"
  ],
  "autoload": {
    "files": [
      "registration.php"
    ],
    "psr-4": {
      "Deity\\CatalogApi\\": ""
    }
  }
}

```

**app/code/Deity/CatalogApi/etc/module.xml:**

```
<?xml version="1.0"?>
<!--
<config xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="urn:magento:framework:Module/etc/module.xsd">
    <module name="Deity_CatalogApi" setup_version="1.0.0" />
</config>

```

**app/code/Deity/Catalog/composer.json:**

```
{
  "name": "deity/module-catalog",
  "description": "N/A",
  "require": {
    "php": "~7.1.0||~7.2.0",
    "magento/module-catalog": "*",
	"deity/module-catalog-api": "*",
  },
  "type": "magento2-module",
  "license": [
    "OSL-3.0",
    "AFL-3.0"
  ],
  "autoload": {
    "files": [
      "registration.php"
    ],
    "psr-4": {
      "Deity\\Catalog\\": ""
    }
  }
}

```

**app/code/Deity/CatalogApi/etc/module.xml:**

```
<?xml version="1.0"?>
<!--
<config xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="urn:magento:framework:Module/etc/module.xsd">
    <module name="Deity_Catalog" setup_version="1.0.0">
    	<sequence>
            <module name="Magento_Catalog"/>
        </sequence>
    </module>
</config>

```

## Modules list

> **NOTE:** This section is still under definition and must be considered as incomplete.

- **Deity_Base:** Common features and basic definitions
- **Deity_CatalogApi:** API definition for catalog
- **Deity_Catalog:** Concrete classes for catalog
- **Deity_Bundle:** Concrete classes for catalog bundle products
- **Deity_Grouped:** Concrete classes for catalog grouped products
- **Deity_CatalogAdminUi:** `system.xml` entries and backend settings
- **DeityCatalogSearchApi:** API definition for catalog search (should be engine agnostic)
- **DeityCatalogSearch:** Concrete classes for catalog search (should be engine agnostic)
- **Deity_CheckoutApi:** API definition for checkout REST API calls
- **Deity_Checkout:** Concrete classes for checkout REST API calls
- **Deity_PaymentApi**: API definition for Magento2 integration with payment methods
- **Deity_Payment**: Magento2 integration with payment methods
- **Deity_ShippingApi**: ...
- **Deity_Shipping**: ...
- **Deity_CustomerApi**: ...
- **Deity_Customer**: ...
- ... others to define
- **Deity_CatalogGraphQl**: For the upcoming GraphQL integration with Magento 2.3

Modules isolation should be also driven by Magento2 modules avoiding too many dependencies or responsabilities on a single module.

## Development guidelines

A good reference on how to write and organize the DM2 code can be found int the **MSI Magento2 community project**: https://github.com/magento-engcom/msi .

### Strict typing

**Each PHP file must include as first line:**

```
<?php
declare(strict_types=1);
```

**Parameters and return types:**

- Each method must explicit the paramters type and return type. No `mixed` type should be allowed other than for compatiblity reasons with Magento 2 legacy code.
- Each method is only allowed to return a single type + `null` if needed

Example:

```
...
public function myMethod(string $myValue, int $myOtherValue): ?int
{
...
}
...
```

Doctype must follow the method specification:

```
...
/**
 * This method makes something
 * @param string $myValue
 * @param int $myOtherValue
 * @param string[] $oneArray
 * @return int|null
 */
public function myMethod(string $myValue, int $myOtherValue, array $oneArray): ?int
{
...
}
...
```

### Strict comparison

Avoid using non-strict comparison. Use strict mode only:

```
if ($a === $b) {
...
}
...
if (in_array($a, $b, true)) {
...
}
...
```

### Exception

**Throw exceptions on unexpected conditions and do not fail silently:**

```
...
if ($somethingIsWrong) {
	throw new LocalizedException(__('Something went wrong));
}
...
```

**Declare exceptions in the doctype:**

```
...
/**
 * This method makes something
 * @param string $myValue
 * @param int $myOtherValue
 * @param string[] $oneArray
 * @return int|null
 * @throws LocalizedException
 */
public function myMethod(string $myValue, int $myOtherValue, array $oneArray): ?int
{
...
	if ($somethingIsWrong) {
		throw new LocalizedException(__('Something went wrong));
	}
...
}
...
```

### Single responsability classes

Classes implementation should follow the single responsability principle with as low as possible public methods.
If a single public method is exposed, then `execute` could be a good naming convention.
The class name should reflect its responsability.

Example:
```
<?php
...
class GetProduct
{
	public function execute(string $sku): ProductInterface
    {
    	...
    }
}
```

### Avoid usage of helper classes

Magento2 developing best practices underlines to avoid usage of Helper classes or classes with too many responsabilities.
Prefer splitting into multiple classes or refactor your code instead of relying on helper classes.

## Variables naming

Despite `PHPMD.LongVariable` rule, prefer long variable names to short variable names.
Example: Avoid using `$qty` and use `$quantity` instead.

If needed, the `PHPMD.LongVariable` suppression is allowed: `@SuppressWarnings(PHPMD.LongVariable)`.

## Static testing

Keep your developing environment configured with **PHPMD** and **PHPCS** configured with the static testing rules in `dev/tests/static/testsuite/Magento/Test/Php/_files`.

> **References:**
PHPMD: https://confluence.jetbrains.com/display/PhpStorm/PHP+Mess+Detector+in+PhpStorm
PHPCS: https://confluence.jetbrains.com/display/PhpStorm/PHP+Code+Sniffer+in+PhpStorm