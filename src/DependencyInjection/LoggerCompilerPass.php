<?php


namespace App\DependencyInjection;


use App\Logger;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class LoggerCompilerPass implements CompilerPassInterface
{


    public function process(ContainerBuilder $container)
    {
//       $definitions= $container->getDefinitions();
////       dd($definitions);
//        foreach ($definitions as $id => $definition) {
////            dd($definition);
//            if ($id === 'texter.sms' || $id === 'mailer.gmail') {
//                $definition->addMethodCall('setLogger', [new Reference('logger')]);
//            }
//        }
        $ids = $container->findTaggedServiceIds('with_logger');
//        dump($ids);
        foreach ($ids as $id => $data) {
            $definition = $container->getDefinition($id);
            $definition->addMethodCall('setLogger', [new Reference('logger')]);
        }
    }
}