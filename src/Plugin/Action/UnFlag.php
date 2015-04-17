<?php
/**
 * @file
 * Contains the \Drupal\flag\Plugin\Action\UnFlag class.
 */

namespace Drupal\flag\Plugin\Action;

use Drupal\Core\Entity\Entity;
use Drupal\flag\Entity\Flag as FlagEntity;

/**
 * Provides a generic 'unflag' action.
 *
 * @Action(
 *   id = "flag_action_unflag",
 *   label = @Translation("Unflags the specifies the entity."),
 *   category = @Translation("Entity"),
 *   context = {
 *     "entity" = @ContextDefinition("entity",
 *       label = @Translation("Entity"),
 *       description = @Translation("The target entity to unflag.")
 *     ),
 *     "flag" = @ContextDefinition("entity",
 *       label = @Translation("Flag"),
 *       description = @Translation("The Flag entity.")
 *     )
 *   }
 * )
 *
 */
class UnFlag extends Base {

  /**
   * {@inheritdoc}
   */
  public function execute() {
    /** @var Entity $target */
    $entity = $this->getContextValue('entity')

    /** @var FlagEntity */;
    $flag = $this->getContextValue('flag');

    $this->flagService->unflag($flag, $entity);
  }

  /**
   * {@inheritdoc}
   */
  public function summary() {
    return $this->t('UnFlag entity');
  }

}
