<?php
class Base_entity implements JsonSerializable
{

	/**
	 * Maps names used in sets and gets against unique
	 * names within the class, allowing independence from
	 * database column names.
	 *
	 * Example:
	 *  $datamap = [
	 *      'db_name' => 'class_name'
	 *  ];
	 */
	protected $datamap = [];
	/**
	 * Holds the current values of all class vars.
	 *
	 * @var array
	 */
	protected $attributes = [];
	/**
	 * Holds original copies of all class vars so
	 * we can determine what's actually been changed
	 * and not accidentally write nulls where we shouldn't.
	 *
	 * @var array
	 */
	protected $original = [];

	protected $fixedAttributesOnly = [];

	/**
	 * Allows filling in Entity parameters during construction.
	 *
	 * @param array|null $data
	 */
	public function __construct(array $data = null)
	{
		$this->syncOriginal();

		$this->fill($data);
	}

	/**
	 * Takes an array of key/value pairs and sets them as
	 * class properties, using any `setCamelCasedProperty()` methods
	 * that may or may not exist.
	 *
	 * @param array $data
	 *
	 * @return \CodeIgniter\Entity
	 */
	public function fill(array $data = null)
	{
		if (! is_array($data))
		{
			return $this;
		}

		foreach ($data as $key => $value)
		{
			$this->$key = $value;
		}

		return $this;
	}

	public function removeUnmappedProperties($removeNullValues = false)
	{
		$newAttributes = [];
		foreach($this->datamap as $key=>$map){
			if($removeNullValues){
				if($this->attributes[$map] !== null) {
					$newAttributes[$map] = $this->attributes[$map];
				}
			} else {
				$newAttributes[$map] = $this->attributes[$map];
			}
		}
		$this->attributes = $newAttributes;
	}


	/**
	 * Magic method to allow retrieval of protected and private
	 * class properties either by their name, or through a `getCamelCasedProperty()`
	 * method.
	 *
	 * Examples:
	 *
	 *      $p = $this->my_property
	 *      $p = $this->getMyProperty()
	 *
	 * @param string $key
	 *
	 * @return mixed
	 * @throws \Exception
	 */
	public function __get(string $key)
	{
		$key    = $this->mapProperty($key);
		$result = null;

		// Convert to CamelCase for the method
		$method = 'get' . str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $key)));

		// if a set* method exists for this key,
		// use that method to insert this value.
		if (method_exists($this, $method))
		{
			$result = $this->$method();
		}

		// Otherwise return the protected property
		// if it exists.
		else if (array_key_exists($key, $this->attributes))
		{
			if(empty($this->fixedAttributesOnly)) {
				$result = $this->attributes[$key];
			} elseif (array_key_exists($key, $this->fixedAttributesOnly)){
				$result = $this->attributes[$key];
			}
		}
		return $result;
	}

	/**
	 * Magic method to all protected/private class properties to be easily set,
	 * either through a direct access or a `setCamelCasedProperty()` method.
	 *
	 * Examples:
	 *
	 *      $this->my_property = $p;
	 *      $this->setMyProperty() = $p;
	 *
	 * @param string $key
	 * @param null   $value
	 *
	 * @return $this
	 * @throws \Exception
	 */
	public function __set(string $key, $value = null)
	{
		$key = $this->mapProperty($key);

		// if a set* method exists for this key,
		// use that method to insert this value.
		// *) should be outside $isNullable check - SO maybe wants to do sth with null value automatically
		$method = 'set' . str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $key)));
		if (method_exists($this, $method))
		{
			$this->$method($value);

			return $this;
		}

		// Otherwise, just the value.
		// This allows for creation of new class
		// properties that are undefined, though
		// they cannot be saved. Useful for
		// grabbing values through joins,
		// assigning relationships, etc.
		if(empty($this->fixedAttributesOnly)){
			$this->attributes[$key] = $value;
		} elseif (array_key_exists($key, $this->fixedAttributesOnly)) {
			$this->attributes[$key] = $value;
		}

		return $this;
	}

	/**
	 * Unsets an attribute property.
	 *
	 * @param string $key
	 *
	 * @throws \ReflectionException
	 */
	public function __unset(string $key)
	{
		$key = $this->mapProperty($key);
		unset($this->attributes[$key]);
	}

	/**
	 * Returns true if a property exists names $key, or a getter method
	 * exists named like for __get().
	 *
	 * @param string $key
	 *
	 * @return boolean
	 */
	public function __isset(string $key): bool
	{
		$key = $this->mapProperty($key);

		$method = 'get' . str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $key)));

		if (method_exists($this, $method))
		{
			return true;
		}

		return isset($this->attributes[$key]);
	}

	/**
	 * Set raw data array without any mutations
	 *
	 * @param  array $data
	 * @return $this
	 */
	public function setAttributes(array $data)
	{
		$this->attributes = $data;
		$this->syncOriginal();
		return $this;
	}

	/**
	 * Set raw data array without any mutations
	 *
	 * @param  array $data
	 * @return $this
	 */
	public function getAttributes()
	{
		return $this->attributes;
	}

	/**
	 * Set raw data array without any mutations
	 *
	 * @param  array $data
	 * @return $this
	 */
	public function setDatamap(array $datamap)
	{
		$this->datamap = $datamap;
		return $this;
	}

	public function getDatamap()
	{
		return $this->datamap;
	}

	/**
	 * Checks the datamap to see if this column name is being mapped,
	 * and returns the mapped name, if any, or the original name.
	 *
	 * @param string $key
	 *
	 * @return mixed|string
	 */
	protected function mapProperty(string $key)
	{
		if (empty($this->datamap))
		{
			return $key;
		}

		if (! empty($this->datamap[$key]))
		{
			return $this->datamap[$key];
		}

		return $key;
	}

	/**
	 * Checks a property to see if it has changed since the entity was created.
	 * Or, without a parameter, checks if any properties have changed.
	 *
	 * @param string $key
	 *
	 * @return boolean
	 */
	public function hasChanged(string $key = null): bool
	{
		// If no parameter was given then check all attributes
		if ($key === null)
		{
			return     $this->original !== $this->attributes;
		}

		// Key doesn't exist in either
		if (! array_key_exists($key, $this->original) && ! array_key_exists($key, $this->attributes))
		{
			return false;
		}

		// It's a new element
		if (! array_key_exists($key, $this->original) && array_key_exists($key, $this->attributes))
		{
			return true;
		}

		return $this->original[$key] !== $this->attributes[$key];
	}

	/**
	 * Ensures our "original" values match the current values.
	 *
	 * @return $this
	 */
	public function syncOriginal()
	{
		$this->original = $this->attributes;

		return $this;
	}

	/**
	 * Returns the raw values of the current attributes.
	 *
	 * @param boolean $onlyChanged
	 *
	 * @return array
	 */
	public function toRawArray(bool $onlyChanged = false): array
	{
		$return = [];

		if (! $onlyChanged)
		{
			return $this->attributes;
		}

		foreach ($this->attributes as $key => $value)
		{
			if (! $this->hasChanged($key))
			{
				continue;
			}

			$return[$key] = $this->attributes[$key];
		}

		return $return;
	}

	/**
	 * General method that will return all public and protected
	 * values of this entity as an array. All values are accessed
	 * through the __get() magic method so will have any casts, etc
	 * applied to them.
	 *
	 * @param boolean $onlyChanged If true, only return values that have changed since object creation
	 * @param boolean $cast        If true, properties will be casted.
	 *
	 * @return array
	 * @throws \Exception
	 */
	public function toArray(bool $onlyChanged = false, bool $withMapped = true, $onlyMapped= false): array
	{
		$return      = [];

		// we need to loop over our properties so that we
		// allow our magic methods a chance to do their thing.
		foreach ($this->attributes as $key => $value)
		{
			if (strpos($key, '_') === 0)
			{
				continue;
			}

			if ($onlyChanged && ! $this->hasChanged($key))
			{
				continue;
			}

			$return[$key] = $this->__get($key);
		}

		// Loop over our mapped properties and add them to the list...
		if (is_array($this->datamap) && $withMapped)
		{
			foreach ($this->datamap as $from => $to)
			{
				if (array_key_exists($to, $return))
				{
					$return[$from] = $this->__get($to);
				}
			}
		}
		if(is_array($this->datamap) && $onlyMapped){
			foreach ($return as $key => $value)
			{
				if (!array_key_exists($key, $this->datamap))
				{
					unset($return[$key]);
				}
			}
		}
		return $return;
	}

	public function setFixedAttributesOnly(array $data)
	{
		$this->fixedAttributesOnly = $data;
	}

	/**
	 * Support for json_encode()
	 *
	 * @return array|mixed
	 * @throws \Exception
	 */
	public function jsonSerialize()
	{
		return $this->toArray();
	}

}
