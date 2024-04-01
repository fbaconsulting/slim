# 01_static_server

No config server.

## Run as command:

```
./examples/development/01_static_server/run
```

## Example as code:

```php
$app = new Slim();
```

... And evaluates empty config properties on `ConfigCapsule`:

```php
// Create by real instance of Container (on Slim is private) to decorate it
// ContainerDecorator considered settings as a different property of dependencies
$this->container = !is_null($configCapsuleProperties) ? new ContainerDecorator($configCapsuleProperties)
    : new ContainerDecorator(
        // Create a container without default settings
        new ConfigCapsuleProperties([])
    )
;
```