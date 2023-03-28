<?php

declare(strict_types=1);

namespace SqlTark\Query;

use Closure;
use SqlTark\Query;
use SqlTark\Utilities\Helper;
use SqlTark\Component\ComponentType;
use SqlTark\Component\AbstractComponent;

abstract class AbstractQuery implements QueryInterface
{
    /**
     * @var ?AbstractQuery $parent
     */
    protected $parent = null;
    
    /**
     * @var ?array<int,AbstractComponent[]> $components
     */
    protected $components = null;

    /**
     * @var int $method
     */
    protected $method = MethodType::Select;

    /**
     * @var int $conditionComponent ComponentType enum
     */
    protected $conditionComponent = ComponentType::Where;

    /**
     * @return int ComponentType enum
     */
    public function getConditionComponent(): int
    {
        return $this->conditionComponent;
    }

    /**
     * @return ?AbstractQuery
     */
    public function getParent(): ?AbstractQuery
    {
        return $this->parent;
    }

    /**
     * @param AbstractQuery $value
     * @return static
     */
    public function setParent(AbstractQuery $value)
    {
        if ($this === $value) {
            Helper::throwInvalidArgumentException("Cannot set the same %s as a parent of itself", $value);
        }

        $this->parent = $value;
        return $this;
    }

    /**
     * @return int MethodType enum
     */
    public function getMethod(): int
    {
        return $this->method;
    }

    /**
     * @param int $value MethodType enum
     * @return static
     */
    public function setMethod(int $value)
    {
        $this->method = $value;
        return $this;
    }
    
    /**
     * {@inheritdoc}
     */
    public function newQuery(): Query
    {
        return new Query;
    }
    
    /**
     * {@inheritdoc}
     */
    public function newChild(): Query
    {
        return $this->newQuery()->setParent($this);
    }

    /**
     * {@inheritdoc}
     */
    public function addComponent(int $componentType, AbstractComponent $component)
    {
        if(is_null($this->components)) {
            $this->components = [];
        }

        $key = $componentType;
        if(!isset($this->components[$key])) {
            $this->components[$key] = [];
        }

        $component->setComponentType($componentType);
        array_push($this->components[$key], $component);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addOrReplaceComponent(int $componentType, AbstractComponent $component)
    {
        if(is_null($this->components)) {
            $this->components = [];
        }

        $key = $componentType;
        if(isset($this->components[$key])) {
            unset($this->components[$key]);
        }

        return $this->addComponent($componentType, $component);
    }

    /**
     * {@inheritdoc}
     */
    public function clearComponents(?int $componentType = null)
    {
        if(is_null($componentType)) {
            $this->components = null;
        }

        elseif($this->hasComponent($componentType)) {
            unset($this->components[$componentType]);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getComponents(?int $componentType = null, string $expectClass = AbstractComponent::class): array
    {
        if(is_null($this->components)) {
            return [];
        }

        if(is_null($componentType)) {
            return Helper::flatten($this->components);
        }

        $key = $componentType;
        if(!isset($this->components[$key])) {
            return [];
        }

        return $this->components[$key];
    }

    /**
     * {@inheritdoc}
     */
    public function getOneComponent(int $componentType, string $expectClass = AbstractComponent::class): ?AbstractComponent
    {
        return $this->hasComponent($componentType)
            ? $this->components[$componentType][0]
            : null;
    }

    /**
     * {@inheritdoc}
     */
    public function hasComponent(int $componentType): bool
    {
        return isset($this->components, $this->components[$componentType]);
    }

    /**
     * {@inheritdoc}
     */
    public function when(bool $condition, Closure $whenTrue, ?Closure $whenFalse = null)
    {
        if($condition) {
            $whenTrue($this);
        }

        elseif(!is_null($whenFalse)) {
            $whenFalse($this);
        }

        return $this;
    }

    public final function __construct() { }
}