<?php
/**
 * @file
 * Contains \Drupal\flag\Plugin\Action\Flag class.
 */

namespace Drupal\flag\Plugin\Action;

use Drupal\Core\Entity\Entity;
use Drupal\flag\Entity\Flag as FlagEntity;

/**
 * Provides a generic 'flag' action.
 *
 * @Action(
 *   id = "flag_action_flag",
 *   label = @Translation("Flags the specifies the entity."),
 *   category = @Translation("Entity"),
 *   context = {
 *     "entity" = @ContextDefinition("entity",
 *       label = @Translation("Entity"),
 *       description = @Translation("The target entity to flag.")
 *     ),
 *     "flag" = @ContextDefinition("entity",
 *       label = @Translation("Flag"),
 *       description = @Translation("The Flag entity.")
 *     )
 *   }
 * )
 *
 */
class Flag extends FlagActionBase {

  /**
   * {@inheritdoc}
   */
  public function execute() {
    /** @var Entity $target */
    $entity = $this->getContextValue('entity')

    /** @var FlagEntity */;
    $flag = $this->getContextValue('flag');

    $this->flagService->flag($flag, $entity);
  }

  /**
   * {@inheritdoc}
   */
  public function summary() {
    return $this->t('flag entity');
  }

}
