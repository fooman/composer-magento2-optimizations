Optimize Composer for Magento 2 projects
====

# About
Adapted for Magento 2 from [zaporylie/composer-drupal-optimizations](https://github.com/zaporylie/composer-drupal-optimizations)
This composer-plugin contains a set of improvements that makes running heavy duty composer commands (i.e. `composer update` or `composer require`) much faster.

# Installation

```bash
composer config repositories.foomanm2opt git https://github.com/extdn/composer-magento2-optimizations.git
composer require fooman/composer-magento2-optimizations:dev-master
```

No configuration required 🎊

# Optimizations

- Reduce memory usage and CPU usage by removing legacy symfony tags

# Unscientific Benchmark

Before
> [1165.2MB/123.52s] Memory usage: 1165.16MB (peak: 4744.7MB), time: 123.52s

After
> [923.5MB/43.85s] Memory usage: 923.49MB (peak: 1151.42MB), time: 43.85s

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
