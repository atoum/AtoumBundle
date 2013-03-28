<?php

namespace atoum\AtoumBundle\Manager;

use Doctrine\Common\Annotations\Reader;
use Symfony\Component\DependencyInjection\ContainerInterface;
use atoum\AtoumBundle\Test\Controller\CleanableControllerTest;
use ReflectionMethod;

class CleaningManager
{
    const BASE_ANNOTATION = 'atoum\\AtoumBundle\\Annotation\\Clean';

    /**
     * @var Reader
     */
    protected $reader;

    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(Reader $reader, ContainerInterface $container)
    {
        $this->reader    = $reader;
        $this->container = $container;
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

            $cleanerName = $annot->getCleaningServiceName();

            if (!$this->container->has($cleanerName)) {
                continue;
            }

            $cleaner = $this->container->get($cleanerName);

            $cleaner->clean($object, $annot);
        }
    }
}