<?php

declare(strict_types=1);

namespace Drupal\entity_events\Entity;

use Drupal\Core\Entity\EntityInterface;
use Drupal\entity_events\Event\EntityPreSaveEvent;
use Drupal\entity_events\Event\EntityPostSaveEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Servicio que dispara eventos de save de entidad.
 */
final class EntityDispatcher {

  public function __construct(
    private readonly EventDispatcherInterface $eventDispatcher,
  ) {}

  /**
   * Dispara el evento de preSave de entidad.
   */
  public function dispatchPreSave(EntityInterface $entity, bool $is_new): void {
    $event = new EntityPreSaveEvent($entity, $is_new);
    $this->eventDispatcher->dispatch($event, EntityPreSaveEvent::EVENT_NAME);
  }

  /**
   * Dispara el evento de postSave de entidad.
   */
  public function dispatchPostSave(EntityInterface $entity, bool $is_new): void {
    $event = new EntityPostSaveEvent($entity, $is_new);
    $this->eventDispatcher->dispatch($event, EntityPostSaveEvent::EVENT_NAME);
  }

}
