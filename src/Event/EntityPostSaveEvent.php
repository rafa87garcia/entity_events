<?php

declare(strict_types=1);

namespace Drupal\entity_events\Event;

use Drupal\Core\Entity\EntityInterface;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Evento disparado después de guardar una entidad (insert/update).
 */
final class EntityPostSaveEvent extends Event {

  public const EVENT_NAME = 'entity_events.entity_post_save';

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
   * Devuelve el ID del bundle de la entidad.
   */
  public function getBundle(): ?string {
    return method_exists($this->entity, 'bundle') ? $this->entity->bundle() : NULL;
  }

}
