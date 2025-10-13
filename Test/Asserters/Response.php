<?php

namespace atoum\AtoumBundle\Test\Asserters;

use atoum\atoum\asserters;

class Response extends asserters\phpObject
{
    /**
     * @param bool $checkType
     *
     * @return $this
     */
    public function setWith(mixed $value, $checkType = true)
    {
        parent::setWith($value, false);

        if (true === $checkType) {
            if (false === self::isResponse($this->value)) {
                $this->fail(sprintf($this->getLocale()->_('%s is not a response'), $this->getTypeOf($this->value)));
            } else {
                $this->pass();
            }
        }

        return $this;
    }

    /**
     * @param int         $status
     * @param string|null $failMessage
     *
     * @return $this
     */
    public function hasStatus($status, $failMessage = null)
    {
        if (($actual = $this->valueIsSet()->value->getStatusCode()) !== $status) {
            $this->fail(null !== $failMessage ? $failMessage : sprintf($this->getLocale()->_('Status %s is not equal to %s'), $this->getTypeOf($actual), $this->getTypeOf($status)));
        } else {
            $this->pass();
        }

        return $this;
    }

    /**
     * @param int         $age
     * @param string|null $failMessage
     *
     * @return $this
     */
    public function hasAge($age, $failMessage = null)
    {
        if (($actual = $this->valueIsSet()->value->getAge()) !== $age) {
            $this->fail(null !== $failMessage ? $failMessage : sprintf($this->getLocale()->_('Age %s is not equal to %s'), $this->getTypeOf($actual), $this->getTypeOf($age)));
        } else {
            $this->pass();
        }

        return $this;
    }

    /**
     * @param int         $age
     * @param string|null $failMessage
     *
     * @return $this
     */
    public function hasMaxAge($age, $failMessage = null)
    {
        if (($actual = $this->valueIsSet()->value->getMaxAge()) !== $age) {
            $this->fail(null !== $failMessage ? $failMessage : sprintf($this->getLocale()->_('Max age %s is not equal to %s'), $this->getTypeOf($actual), $this->getTypeOf($age)));
        } else {
            $this->pass();
        }

        return $this;
    }

    /**
     * @param string      $charset
     * @param string|null $failMessage
     *
     * @return $this
     */
    public function hasCharset($charset, $failMessage = null)
    {
        if (($actual = $this->valueIsSet()->value->getCharset()) !== $charset) {
            $this->fail(null !== $failMessage ? $failMessage : sprintf($this->getLocale()->_('Charset %s is not equal to %s'), $this->getTypeOf($actual), $this->getTypeOf($charset)));
        } else {
            $this->pass();
        }

        return $this;
    }

    /**
     * @param string      $content
     * @param string|null $failMessage
     *
     * @return $this
     */
    public function hasContent($content, $failMessage = null)
    {
        if (($actual = $this->valueIsSet()->value->getContent()) !== $content) {
            $this->fail(null !== $failMessage ? $failMessage : sprintf($this->getLocale()->_('Content %s is not equal to %s'), $this->getTypeOf($actual), $this->getTypeOf($content)));
        } else {
            $this->pass();
        }

        return $this;
    }

    /**
     * @param string      $etag
     * @param string|null $failMessage
     *
     * @return $this
     */
    public function hasEtag($etag, $failMessage = null)
    {
        if (($actual = $this->valueIsSet()->value->getEtag()) !== $etag) {
            $this->fail(null !== $failMessage ? $failMessage : sprintf($this->getLocale()->_('Etag %s is not equal to %s'), $this->getTypeOf($actual), $this->getTypeOf($etag)));
        } else {
            $this->pass();
        }

        return $this;
    }

    /**
     * @param string      $version
     * @param string|null $failMessage
     *
     * @return $this
     */
    public function hasVersion($version, $failMessage = null)
    {
        if (($actual = $this->valueIsSet()->value->getProtocolVersion()) !== $version) {
            $this->fail(null !== $failMessage ? $failMessage : sprintf($this->getLocale()->_('Version %s is not equal to %s'), $this->getTypeOf($actual), $this->getTypeOf($version)));
        } else {
            $this->pass();
        }

        return $this;
    }

    /**
     * @param int         $ttl
     * @param string|null $failMessage
     *
     * @return $this
     */
    public function hasTtl($ttl, $failMessage = null)
    {
        if (($actual = $this->valueIsSet()->value->getTtl()) !== $ttl) {
            $this->fail(null !== $failMessage ? $failMessage : sprintf($this->getLocale()->_('TTL %s is not equal to %s'), $this->getTypeOf($actual), $this->getTypeOf($ttl)));
        } else {
            $this->pass();
        }

        return $this;
    }

    /**
     * @param string      $name
     * @param string      $value
     * @param string|null $failMessage
     *
     * @return $this
     */
    public function hasHeader($name, $value, $failMessage = null)
    {
        if (($actual = $this->getHeaders()->get($name)) !== $value) {
            $this->fail(null !== $failMessage ? $failMessage : sprintf($this->getLocale()->_('Value %s is not equal to %s for header %s'), $this->getTypeOf($actual), $this->getTypeOf($value), $name));
        } else {
            $this->pass();
        }

        return $this;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\ResponseHeaderBag
     */
    public function getHeaders()
    {
        return $this->valueIsSet()->value->headers;
    }

    /**
     * @return bool
     */
    protected static function isResponse(mixed $value)
    {
        return $value instanceof \Symfony\Component\HttpFoundation\Response;
    }

    /**
     * @return $this
     */
    public function dumpResponse()
    {
        // Note: dump() method might be from parent asserter or atoum
        /* @phpstan-ignore-next-line */
        $this->dump($this->getValue()->getContent());

        return $this;
    }

    /**
     * @param string      $text
     * @param string|null $failMessage
     *
     * @return $this
     */
    public function contains($text, $failMessage = null)
    {
        if (false !== strpos($this->getValue()->getContent(), $text)) {
            $this->pass();
        } else {
            $this->fail(null !== $failMessage ? $failMessage : sprintf($this->getLocale()->_("text : '%s' wasn't found in the response"), $text));
        }

        return $this;
    }
}
