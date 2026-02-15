# Entity Events

Modulo Drupal que expone eventos custom para el ciclo de guardado de entidades.
Usa un servicio que despacha eventos en `hook_entity_presave`, `hook_entity_insert`
y `hook_entity_update`.

## Que hace

- Dispara un evento antes de guardar (preSave).
- Dispara un evento despues de guardar (postSave) para insert y update.
- Incluye suscriptores de ejemplo.

## Eventos disponibles

- `entity_events.entity_pre_save`
  - Clase: `Drupal\entity_events\Event\EntityPreSaveEvent`
  - Se dispara en `hook_entity_presave`.
  - Metodos utiles: `getEntity()`, `isNew()`, `getEntityTypeId()`, `getBundle()`.

- `entity_events.entity_post_save`
  - Clase: `Drupal\entity_events\Event\EntityPostSaveEvent`
  - Se dispara en `hook_entity_insert` y `hook_entity_update`.
  - Metodos utiles: `getEntity()`, `isNew()`, `getEntityTypeId()`, `getBundle()`.

## Servicio

Servicio encargado de despachar los eventos:

- ID: `entity_events.entity_dispatcher`
- Clase: `Drupal\entity_events\Entity\EntityDispatcher`

## Como usarlo

1. Habilita el modulo.
2. Crea un suscriptor a los eventos.
3. Registra el servicio con la etiqueta `event_subscriber`.

## Ejemplos

### PreSave (antes de guardar)

Archivo ejemplo ya incluido:
- `src/PreSave/ExampleEntityPreSaveSubscriber.php`

Ejemplo minimo:

```php
<?php

declare(strict_types=1);

namespace Drupal\mi_modulo\PreSave;

use Drupal\entity_events\Event\EntityPreSaveEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class NodeArticlePreSaveSubscriber implements EventSubscriberInterface {

  public static function getSubscribedEvents(): array {
    return [
      EntityPreSaveEvent::EVENT_NAME => ['onPreSave', 0],
    ];
  }

  public function onPreSave(EntityPreSaveEvent $event): void {
    if ($event->getEntityTypeId() !== 'node' || $event->getBundle() !== 'article') {
      return;
    }

    // Ejemplo: log simple antes de guardar.
    \Drupal::logger('mi_modulo')->notice('PreSave node @id (isNew=@new)', [
      '@id' => $event->getEntity()->id(),
      '@new' => $event->isNew() ? '1' : '0',
    ]);
  }

}
```

Registro en `mi_modulo.services.yml`:

```yaml
services:
  mi_modulo.node_article_presave_subscriber:
    class: Drupal\mi_modulo\PreSave\NodeArticlePreSaveSubscriber
    tags:
      - { name: event_subscriber }
```

### PostSave (despues de guardar)

Archivo ejemplo ya incluido:
- `src/PostSave/ExampleEntityPostSaveSubscriber.php`

Ejemplo minimo:

```php
<?php

declare(strict_types=1);

namespace Drupal\mi_modulo\PostSave;

use Drupal\entity_events\Event\EntityPostSaveEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class NodeArticlePostSaveSubscriber implements EventSubscriberInterface {

  public static function getSubscribedEvents(): array {
    return [
      EntityPostSaveEvent::EVENT_NAME => ['onPostSave', 0],
    ];
  }

  public function onPostSave(EntityPostSaveEvent $event): void {
    if ($event->getEntityTypeId() !== 'node' || $event->getBundle() !== 'article') {
      return;
    }

    // Ejemplo: log simple despues de guardar.
    \Drupal::logger('mi_modulo')->notice('PostSave node @id (isNew=@new)', [
      '@id' => $event->getEntity()->id(),
      '@new' => $event->isNew() ? '1' : '0',
    ]);
  }

}
```

Registro en `mi_modulo.services.yml`:

```yaml
services:
  mi_modulo.node_article_postsave_subscriber:
    class: Drupal\mi_modulo\PostSave\NodeArticlePostSaveSubscriber
    tags:
      - { name: event_subscriber }
```

## Notas

- `isNew()` indica si el guardado fue insert (`true`) o update (`false`).
- `getBundle()` puede devolver `NULL` si la entidad no tiene bundle.

## Archivos clave del modulo

- `entity_events.module`: hooks que disparan los eventos.
- `src/Entity/EntityDispatcher.php`: servicio de despacho.
- `src/Event/*`: definiciones de eventos.
- `src/PreSave/*` y `src/PostSave/*`: ejemplos de suscriptores.
