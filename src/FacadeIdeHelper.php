<?php

namespace Flawlol\FacadeIdeHelper;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

/**
 * Class FacadeIdeHelper
 *
 * This class extends the AbstractBundle and provides methods for booting the bundle,
 * retrieving the path and namespace, and loading the extension configuration.
 *
 * @author Flawlol
 */
class FacadeIdeHelper extends AbstractBundle
{
    /**
     * Boot the bundle.
     *
     * This method is called when the bundle is booted.
     */
    public function boot(): void
    {
        parent::boot();
    }

    /**
     * Get the path of the bundle.
     *
     * @return string The path of the bundle.
     */
    public function getPath(): string
    {
        return __DIR__;
    }

    /**
     * Get the namespace of the bundle.
     *
     * @return string The namespace of the bundle.
     */
    public function getNamespace(): string
    {
        return __NAMESPACE__;
    }

    /**
     * Load the extension configuration.
     *
     * This method loads the extension configuration from the specified YAML file.
     *
     * @param array $config The configuration array.
     * @param ContainerConfigurator $container The container configurator.
     * @param ContainerBuilder $builder The container builder.
     */
    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $container->import('./Resources/config/services.yaml');
    }
}