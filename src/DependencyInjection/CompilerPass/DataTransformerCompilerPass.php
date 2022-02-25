<?php

declare(strict_types=1);

namespace Flagbit\Bundle\CategoryBundle\DependencyInjection\CompilerPass;

use Flagbit\Bundle\CategoryBundle\Transformer\DenormalizationTransformer;
use Flagbit\Bundle\CategoryBundle\Transformer\NormalizationTransformerManager;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Compiler pass to pass {@see NormalizationTransformer}s to the {@see NormalizationTransformerManager}.
 */
class DataTransformerCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (! $container->has(NormalizationTransformerManager::class)) {
            return;
        }

        $normalizationDefinition = $container->findDefinition(NormalizationTransformerManager::class);
        $taggedServices          = $container->findTaggedServiceIds(
            'flagbit.category.data_transformer.normalization'
        );
        foreach ($taggedServices as $id => $tags) {
            foreach ($tags as $attributes) {
                $normalizationDefinition->addMethodCall('addTransformer', [
                    new Reference($id),
                    $attributes['type'],
                ]);
            }
        }

        $denormalizationDefinition = $container->findDefinition(DenormalizationTransformer::class);
        $taggedServices            = $container->findTaggedServiceIds(
            'flagbit.category.data_transformer.denormalization'
        );
        foreach ($taggedServices as $id => $tags) {
            foreach ($tags as $attributes) {
                $denormalizationDefinition->addMethodCall('addTransformer', [
                    new Reference($id),
                    $attributes['type'],
                ]);
            }
        }
    }
}
