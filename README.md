Optimize Composer for Magento 2 projects
====
[![Build Status](https://travis-ci.org/fooman/composer-magento2-optimizations.svg?branch=master)](https://travis-ci.org/fooman/composer-magento2-optimizations)

# About
Adapted for Magento 2 from [zaporylie/composer-drupal-optimizations](https://github.com/zaporylie/composer-drupal-optimizations)
This composer-plugin contains a set of improvements that makes running heavy duty composer commands (i.e. `composer update` or `composer require`) much faster.

# Installation

```bash
composer config repositories.foomanm2opt git https://github.com/fooman/composer-magento2-optimizations.git
composer require fooman/composer-magento2-optimizations:dev-master
```

No configuration required 🎊

# Optimizations

- Reduce memory usage and CPU usage by removing legacy symfony tags

# Benchmark

[Before](https://travis-ci.org/fooman/composer-magento2-optimizations/jobs/544611808#L1190)
> Memory usage: 351.1MiB (peak: 1092.15MiB), time: 17.23s

[After](https://travis-ci.org/fooman/composer-magento2-optimizations/jobs/544611808#L1210)
> Memory usage: 268.44MiB (peak: 345.67MiB), time: 8.67s

# Configuration

If no configuration is provided this package will provide sensible defaults based on the Magento 2 version constraint in the root composer.json
file. Default configuration should cover 99% of the cases. However, in case you want to manually specify the tags
that should be filtered out you are welcome to use the `extra` section:

```json
{
  "extra": {
    "composer-magento2-optimizations": {
      "require": {
        "symfony/symfony": ">4.1"
      }
    }
  }
}
```

All you have to do is to make sure your Magento project constraint in the root composer.json is set to `magento/project-community-edition: 2.3.0`/`magento/project-enterprise-edition: 2.3.0` or above.

# Credits

- Symfony community - idea and development; Special thanks to @nicolas-grekas
- Jakub Piasecki - port and maintenance
