<?php

namespace atoum\AtoumBundle\Annotation;

/**
 * @Annotation
 */
interface Clean
{
    public function getEventName();
}