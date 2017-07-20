<?php

namespace atoum\AtoumBundle\Manager;

use Doctrine\Common\Annotations\Reader;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use atoum\AtoumBundle\Test\Controller\CleanableControllerTest;
use atoum\AtoumBundle\Event\CleanEvent;
use ReflectionMethod;

class CleaningManager
{
    const BASE_ANNOTATION = 'atoum\\AtoumBundle\\Annotation\\Clean';

    /**
     * @var Reader
     */
    protected $reader;

    /**
     * @var ContainerAwareEventDispatcher
     */
    protected $eventDispatcher;

    public function __construct(Reader $reader, EventDispatcherInterface $eventDispatcher)
    {
        $this->reader    = $reader;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function process(CleanableControllerTest $object, $method)
    {
        $reflectionMethod = new ReflectionMethod($object, $method);

        if ($reflectionMethod->getDocComment() === false) {
            return;
        }

        $annotations = $this->reader->getMethodAnnotations($reflectionMethod);

        foreach ($annotations as $annot) {

            if (!is_a($annot, self::BASE_ANNOTATION)) {
                continue;
            }

            $this->eventDispatcher->dispatch(
                $annot->getEventName(),
                new CleanEvent($object, $annot)
            );
        }
    }
}