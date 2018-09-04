<?php

namespace JazzMan\Events;

/**
 * Class EventManager
 *
 * @package JazzMan\Events
 */
class EventManager
{

    /**
     * @var string
     */
    private $hook;
    /**
     * @var int
     */
    private $priority = 10;
    /**
     * @var int
     */
    private $accepted_args = 1;

    /**
     * @var callable|string
     */
    private $callback;

    /**
     * EventManager constructor.
     *
     * @param string|null $hook
     */
    public function __construct($hook = null)
    {
        if ($hook !== null && is_string($hook)) {
            $this->setHook($hook);
            $this->setPriority();
            $this->setAcceptedArgs();
        } else {
            return;
        }
    }

    /**
     * @return string
     */
    public static function getCurrentHook()
    {
        return \current_filter();
    }

    /**
     * @return callable|string
     */
    public function getCallback()
    {
        return $this->callback;
    }

    /**
     * @param callable|string $callback
     */
    public function setCallback($callback)
    {
        $this->callback = $callback;
    }

    /**
     * @param callable|string $callback
     *
     * @param int             $priority
     * @param int             $accepted_args
     *
     * @return bool
     */
    public function addCallback($callback = null, $priority = null, $accepted_args = null)
    {
        $callback      = $callback ?: $this->getCallback();
        $priority      = $priority ?: $this->getPriority();
        $accepted_args = $accepted_args ?: $this->getAcceptedArgs();

        return add_filter($this->hook, $callback, $priority, $accepted_args);
    }

    /**
     * @return int
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @param int $priority
     */
    public function setPriority($priority = 10)
    {
        $this->priority = $priority;
    }

    /**
     * @return int
     */
    public function getAcceptedArgs()
    {
        return $this->accepted_args;
    }

    /**
     * @param int $accepted_args
     */
    public function setAcceptedArgs($accepted_args = 1)
    {
        $this->accepted_args = $accepted_args;
    }

    /**
     * @param callable|string $callback
     * @param int             $priority
     *
     * @return bool
     */
    public function removeCallback($callback = null, $priority = null)
    {
        $callback = $callback ?: $this->getCallback();
        $priority = $priority ?: $this->getPriority();

        return \remove_filter($this->getHook(), $callback, $priority);
    }

    /**
     * @return string
     */
    public function getHook()
    {
        return $this->hook;
    }

    /**
     * @param string $hook
     */
    public function setHook($hook)
    {
        $this->hook = $hook;
    }

    /**
     * @param callable|string|null $callback
     *
     * @return bool|false|int
     */
    public function hasCallback($callback = null)
    {
        $callback = $callback ?: $this->getCallback();

        return \has_filter($this->getHook(), $callback);
    }

    /**
     * @return mixed
     */
    public function filter()
    {
        $args = \func_get_args();

        return \apply_filters($this->getHook(), ...$args);
    }
}