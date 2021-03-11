<?php

namespace Fooman\ComposerMagentoOptimizations;

use Composer\Composer;
use Composer\Factory;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Composer\Repository\RepositoryFactory;
use Composer\Repository\RepositoryManager;
use Composer\Semver\Constraint\Constraint;
use Composer\Semver\Constraint\ConstraintInterface;

class Plugin implements PluginInterface
{

    public function activate(Composer $composer, IOInterface $io)
    {
        if (version_compare('2.0.0', PluginInterface::PLUGIN_API_VERSION, '<=')) {
            if ($io->isVerbose()) {
                $io->write(sprintf('fooman/composer-magento2-optimizations is disabled for Composer 2.'));
            }
            return;
        }
        // Set default version constraints based on the composer requirements.
        $extra = $composer->getPackage()->getExtra();
        $packages = $composer->getPackage()->getRequires();
        if (!isset($extra['composer-magento2-optimizations']['require'])
            && (isset($packages['magento/product-community-edition']) || isset($packages['magento/product-enterprise-edition']) || isset($packages['magento/magento-cloud-metapackage']))
        ) {
            if (isset($packages['magento/product-community-edition'])) {
                $coreConstraint = $packages['magento/product-community-edition']->getConstraint();
            } elseif (isset($packages['magento/magento-cloud-metapackage'])) {
                $coreConstraint = $packages['magento/magento-cloud-metapackage']->getConstraint();
            } else {
                $coreConstraint = $packages['magento/product-enterprise-edition']->getConstraint();
            }

            $extra['composer-magento2-optimizations']['require'] = static::getDefaultRequire($coreConstraint);
            if (!empty($extra['composer-magento2-optimizations']['require']) && $io->isVerbose()) {
                $io->write('Required tags were not explicitly set so the fooman/composer-magento2-optimizations set default based on project\'s composer.json content.');
            }
        }
        if (!empty($extra['composer-magento2-optimizations']['require']) && $io->isVerbose()) {
            foreach ($extra['composer-magento2-optimizations']['require'] as $package => $version) {
                $io->write(sprintf('extra.composer-magento2-optimizations.require.%s: \'%s\'', $package, $version));
            }
        }

        $rfs = Factory::createRemoteFilesystem($io, $composer->getConfig());
        $manager = RepositoryFactory::manager($io, $composer->getConfig(), $composer->getEventDispatcher(), $rfs);
        $setRepositories = \Closure::bind(function (RepositoryManager $manager) use ($extra) {
            $manager->repositoryClasses = $this->repositoryClasses;
            $manager->setRepositoryClass('composer', TruncatedComposerRepository::class);
            $manager->repositories = $this->repositories;
            $i = 0;
            foreach (RepositoryFactory::defaultRepos(null, $this->config, $manager) as $repo) {
                $manager->repositories[$i++] = $repo;
                if ($repo instanceof TruncatedComposerRepository && !empty($extra['composer-magento2-optimizations']['require'])) {
                    $repo->setRequiredVersionConstraints($extra['composer-magento2-optimizations']['require']);
                }
            }
            $manager->setLocalRepository($this->getLocalRepository());
        }, $composer->getRepositoryManager(), RepositoryManager::class);
        $setRepositories($manager);
        $composer->setRepositoryManager($manager);
    }

    /**
     * Negotiates default require constraint and package for given drupal/core.
     *
     * @param \Composer\Semver\Constraint\ConstraintInterface
     *
     * @return array
     */
    public static function getDefaultRequire(ConstraintInterface $coreConstraint)
    {
        if ((new Constraint('>=', '2.3.0'))->matches($coreConstraint)
            && !(new Constraint('<', '2.3.0'))->matches($coreConstraint)) {
            return [
                'symfony/symfony' => '>4.1',
            ];
        }
        return [];
    }

    public function deactivate(Composer $composer, IOInterface $io)
    {
    }

    public function uninstall(Composer $composer, IOInterface $io)
    {
    }
}
