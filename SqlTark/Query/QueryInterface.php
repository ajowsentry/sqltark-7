<?php

declare(strict_types=1);

namespace SqlTark\Query;

use SqlTark\Query;
use SqlTark\Component\AbstractComponent;

interface QueryInterface
{
    /**
     * Get query parent
     * @return ?AbstractQuery Parent
     */
    public function getParent(): ?AbstractQuery;

    /**
     * Set query parent
     * @param AbstractQuery $value Parent
     * @return static Self object
     */
    public function setParent(AbstractQuery $value);

    /**
     * Create new ```Query``` object
     * @return Query
     */
    public function newQuery(): Query;

    /**
     * Create new ```Query``` object then set parent as ```this``` object
     * @return Query
     */
    public function newChild(): Query;

    /**
     * Add new component
     * 
     * @param int $componentType Component type from ```ComponentType``` enum class
     * @param AbstractComponent $component Component object
     * @return static Self object
     */
    public function addComponent(int $componentType, AbstractComponent $component);

    /**
     * Remove old component with specified ```componentType``` if exists then
     * add new component.
     * 
     * @param int $componentType Component type from ```ComponentType``` enum class
     * @param AbstractComponent $component Component object
     * @return static Self object
     */
    public function addOrReplaceComponent(int $componentType, AbstractComponent $component);

    /**
     * Remove all component with specified ```componentType``` if exists
     * 
     * @param ?int $componentType Component type from ```ComponentType``` enum class
     * @return static Self object
     */
    public function clearComponents(?int $componentType = null);

    /**
     * Get all components with specified ```componentType```.
     * 
     * Get all components when no parameter is specified.
     * 
     * @template T of AbstractComponent
     * @param ?int $componentType Component type from ```ComponentType``` enum class
     * @param class-string<T> $expectClass
     * @return list<T> Components.
     */
    public function getComponents(?int $componentType = null, string $expectClass = AbstractComponent::class): array;

    /**
     * Get single components with specified ```componentType```.
     * 
     * @template T of AbstractComponent
     * @param int $componentType Component type from ```ComponentType``` enum class
     * @param class-string<T> $expectClass
     * @return ?T Component.
     */
    public function getOneComponent(int $componentType, string $expectClass = AbstractComponent::class): ?AbstractComponent;

    /**
     * Check wether components with specified ```componentType``` is exists.
     * 
     * @param int $componentType Component type from ```ComponentType``` enum class
     * @return bool Is exists.
     */
    public function hasComponent(int $componentType): bool;

    /**
     * Execute query when condition is fulfilled
     * 
     * @param bool $condition Condition
     * @param (\Closure(QueryInterface):void) $whenTrue Query when true
     * @param ?(\Closure(QueryInterface):void) $whenFalse Query when false
     * @return static Self object
     */
    public function when(bool $condition, \Closure $whenTrue, ?\Closure $whenFalse);
}