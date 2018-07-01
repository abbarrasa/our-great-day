<?php

namespace AppBundle\DependencyInjection\Compiler;

use AppBundle\Templating\Helper\UploaderHelper;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class OverrideServiceCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if ($container->hasDefinition('vich_uploader.templating.helper.uploader_helper')) {
            $definition = $container->getDefinition('vich_uploader.templating.helper.uploader_helper');
            $definition->setClass(UploaderHelper::class);
        }
    }
}