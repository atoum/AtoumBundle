<?php

declare(strict_types=1);

namespace atoum\AtoumBundle\PHPStan;

use PhpParser\Node\Expr\PropertyFetch;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\ParametersAcceptorSelector;
use PHPStan\Type\DynamicMethodReturnTypeExtension;
use PHPStan\Type\Type;
use PHPStan\Type\ObjectType;
use PhpParser\Node\Expr\MethodCall;

/**
 * Extension PHPStan pour gérer les propriétés et méthodes magiques d'atoum
 * 
 * Cette extension informe PHPStan que les méthodes comme `if()`, `then()`, `and()`, etc.
 * retournent l'instance courante pour permettre le chaînage fluent.
 */
class AtoumDynamicReturnTypeExtension implements DynamicMethodReturnTypeExtension
{
    /**
     * Méthodes magiques qui retournent $this pour le chaînage
     */
    private const MAGIC_METHODS = [
        'if',
        'and',
        'then',
        'when',
        'given',
        'assert',
        'calling',
    ];

    public function getClass(): string
    {
        // S'applique à toutes les classes de test atoum
        return \mageekguy\atoum\test::class;
    }

    public function isMethodSupported(MethodReflection $methodReflection): bool
    {
        return in_array($methodReflection->getName(), self::MAGIC_METHODS, true);
    }

    public function getTypeFromMethodCall(
        MethodReflection $methodReflection,
        MethodCall $methodCall,
        Scope $scope
    ): Type {
        // Retourne le type de l'objet appelant (pour le chaînage)
        $callerType = $scope->getType($methodCall->var);
        
        return $callerType;
    }
}

