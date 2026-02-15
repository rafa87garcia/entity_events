<?php

declare(strict_types=1);

namespace Drupal\entity_events\Event;

use Drupal\Core\Entity\EntityInterface;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Evento disparado antes de guardar una entidad (presave).
 */
final class EntityPreSaveEvent extends Event {

  public const EVENT_NAME = 'entity_events.entity_pre_save';

  public function __construct(
    private readonly EntityInterface $entity,
    private readonly bool $isNew,
  ) {}

  /**
   * Devuelve la entidad asociada al evento.
   */
  public function getEntity(): EntityInterface {
    return $this->entity;
  }

  /**
   * Indica si la entidad es nueva (insert) o existente (update).
   */
  public function isNew(): bool {
    return $this->isNew;
  }

  /**
   * Devuelve el ID del tipo de entidad.
   */
  public function getEntityTypeId(): string {
    return $this->entity->getEntityTypeId();
  }

  /**
   *
   */
  public function getBundle(): ?string {
    return method_exists($this->entity, 'bundle') ? $this->entity->bundle() : NULL;
  }

}
