<?php
/**
 * Dependency injection container.
 *
 * @author: Dmitriy Yuriev <coolkid00@gmail.com>
 * @version: 3.1
 * @package Forpost3
 * @license: http://www.gnu.org/licenses/agpl.txt GNU AGPLv3
 */

/**
 * Class FContainer represents dependency injection container
 */
class FContainer
{
    /**
     * @var array An array of container binds
     */
    protected $binds = array();

    /**
     * @var array An array of instances of entities
     */
    protected $instances = array();


    /**
     * Adds bind to container.
     *
     * @param string $alias An alias of entity
     * @param callable|string|object|null $entity An entity. May be string, object, closure. The parameter is optional
     * @param array $arguments array List of call arguments
     *
     * return @void
     */
    protected function addBind($alias, $entity, $arguments = array())
    {
        $this->binds[$alias]['entity'] = $entity;
        $this->binds[$alias]['arguments'] = $arguments;
    }

    /**
     * Adds instance of entity to container.
     *
     * @param string $alias An alias of entity
     * @param object $instance An instance of entity.
     *
     * return @void
     */
    protected function addInstance($alias, $instance)
    {
        $this->instances[$alias] = $instance;
    }

    /**
     * Returns instance of entity.
     *
     * @param string $alias An alias of entity
     *
     * return object
     */
    protected function getInstance($alias)
    {
        return $this->instances[$alias];
    }

    /**
     * Creates instance of entity.
     *
     * @param string $alias An alias of entity
     * @param array $arguments Input arguments
     *
     * @return object
     */
    protected function instantiate($alias, $arguments = array())
    {
        if (Lib::chkArr($arguments)) {
            return call_user_func_array($this->binds[$alias]['entity'], $arguments);
        } else {
            return call_user_func_array($this->binds[$alias]['entity'], $this->binds[$alias]['arguments']);
        }
    }

    /**
     * Return true if bind exists in container otherwise return false.
     *
     * @param string $alias An alias of entity
     *
     * @return bool
     */
    public function isBindExists($alias)
    {
        return isset($this->binds[$alias]['entity']);
    }


    /**
     * Return true if instance exists in container otherwise return false.
     *
     * @param string $alias An alias of entity
     *
     * @return bool
     */
    public function isInstanceExists($alias)
    {
        return isset($this->instances[$alias]);
    }

    /**
     * Create a bind between alias and entity then puts it into Container.
     *
     * @param string $alias An alias of entity that will be used in entity's instance process and can be use to get instance of entity
     * @param callable|string|object|null $entity An entity. May be string, object, closure. The parameter is optional
     * @param array $arguments Input arguments
     *
     * @return self
     */
    public function bind($alias, $entity = null, $arguments = array())
    {
        if (is_null($entity)) {
            $entity = $alias;
        }

        if (is_object($entity)) {

            if ($entity instanceof Closure) {

                if (!$this->isBindExists($alias)) {
                    $this->addBind($alias, $entity, $arguments);
                }

            } else {

                if (!$this->isBindExists($alias)) {
                    $this->addBind(
                        $alias,
                        function () use ($entity) {
                            return $entity;
                        }
                    );
                }
            }

        } elseif (is_string($entity)) {

            $this->addBind(
                $alias,
                function () use ($entity) {

                    if (class_exists($entity)) {

                        $reflector = new ReflectionClass($entity);

                        if ($reflector->isInstantiable()) {

                            if ($reflector->getConstructor() === null) {
                                return new $entity;
                            } else {
                                return $reflector->newInstanceArgs(func_get_args());
                            }

                        } else {
                            throw new FException(Lang::getMessage(
                                'system.core.class_not_instantiable',
                                array($entity)
                            ));
                        }

                    } else {
                        throw new FException(Lang::getMessage('system.core.class_not_found', array($entity)));
                    }
                },
                $arguments
            );
        }

        return $this;
    }

    /**
     * Retrieves a bind from container if it exists and after creates instance of entity or returns existing one.
     *
     * @param string $alias An alias of entity
     * @param array $arguments Input arguments
     *
     * @throws FException if bind does not exists in container
     *
     * @return object
     */
    public function make($alias, $arguments = array())
    {
        if ($this->isBindExists($alias)) {

            if (!$this->isInstanceExists($alias)) {
                $this->addInstance($alias, $this->instantiate($alias, $arguments));
            }

            return $this->getInstance($alias);
        } else {
            throw new FException(Lang::getMessage('system.core.bind_not_found', array($alias)));
        }
    }

    /**
     * Retrieves a bind from container if it exists, creates new instance of entity and returns it.
     *
     * @param string $alias An alias of entity
     * @param array $arguments Input arguments
     *
     * @throws FException if bind does not exists in container
     *
     * @return object
     */
    public function makeNew($alias, $arguments = array())
    {
        if ($this->isBindExists($alias)) {
            return $this->instantiate($alias, $arguments);
        } else {
            throw new FException(Lang::getMessage('system.core.bind_not_found', array($alias)));
        }
    }
}
