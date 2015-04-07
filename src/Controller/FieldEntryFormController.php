<?php
/**
 * @file
 * Contains the \Drupal\flag\Controller\FieldEntryFormController class.
 */

namespace Drupal\flag\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\flag\FlagInterface;
use Drupal\flag\FlaggingInterface;
use Drupal\flag\Entity\Flag;

/**
 * Provides a controller for the Field Entry link type.
 */
class FieldEntryFormController extends ControllerBase {

  /**
   * Performs a flagging when called via a route.
   *
   * @param \Drupal\flag\FlagInterface $flag
   *   The flag entity.
   * @param int $entity_id
   *   The flaggable ID.
   *
   * @return AjaxResponse
   *   The response object.
   *
   * @see \Drupal\flag\Plugin\ActionLink\AJAXactionLink
   */
  public function flag(FlagInterface $flag, $entity_id) {
    $flag_id = $flag->id();

    $account = $this->currentUser();

    $flagging = $this->entityManager()->getStorage('flagging')->create([
      'fid' => $flag->id(),
      'entity_type' => $flag->getFlaggableEntityType(),
      'entity_id' => $entity_id,
      'type' => $flag->id(),
      'uid' => $account->id(),
    ]);

    return $this->getForm($flagging, 'add');
  }

  /**
   * Return the flagging edit form.
   *
   * @param \Drupal\flag\FlagInterface $flag
   *   The flag entity.
   * @param mixed $entity_id
   *   The entity ID.
   *
   * @return array
   *   The flagging edit form.
   */
  public function edit(FlagInterface $flag, $entity_id) {
    $flag_id = $flag->id();

    $flagging = $this->getFlagging($flag_id, $entity_id);

    return $this->getForm($flagging, 'edit');
  }

  /**
   * Performs an unflagging when called via a route.
   *
   * @param \Drupal\flag\FlagInterface $flag
   *   The flag entity.
   * @param int $entity_id
   *   The entity ID to unflag.
   *
   * @return AjaxResponse
   *   The response object.
   *
   * @see \Drupal\flag\Plugin\ActionLink\AJAXactionLink
   */
  public function unflag(FlagInterface $flag, $entity_id) {
    $flag_id = $flag->id();

    $flagging = $this->getFlagging($flag_id, $entity_id);

    return $this->getForm($flagging, 'delete');
  }

  /**
   * Title callback when creating a new flagging.
   *
   * @param \Drupal\flag\FlagInterface $flag
   *   The flag entity.
   * @param int $entity_id
   *   The entity ID to unflag.
   *
   * @return string
   *   The flag field entry form title.
   */
  public function flagTitle(FlagInterface $flag, $entity_id) {
    $link_type = $flag->getLinkTypePlugin();
    return $link_type->getFlagQuestion();
  }

  /**
   * Title callback when editing an existing flagging.
   *
   * @param \Drupal\flag\FlagInterface $flag
   *   The flag entity.
   * @param int $entity_id
   *   The entity ID to unflag.
   *
   * @return string
   *   The flag field entry form title.
   */
  public function editTitle(FlagInterface $flag, $entity_id) {
    $link_type = $flag->getLinkTypePlugin();
    return $link_type->getEditFlaggingTitle();
  }

  /**
   * Get a flagging that already exists.
   *
   * @param string $flag_id
   *   The flag ID.
   * @param mixed $entity_id
   *   The flaggable ID.
   *
   * @return FlaggingInterface|null
   *   The flagging or NULL.
   */
  protected function getFlagging($flag_id, $entity_id) {
    $account = $this->currentUser();
    $flag = \Drupal::service('flag')->getFlagById($flag_id);
    $entity = \Drupal::service('flag')->getFlaggableById($flag, $entity_id);
    $flaggings = \Drupal::service('flag')->getFlaggings($entity, $flag, $account);

    return !empty($flaggings) ? reset($flaggings) : NULL;
  }

  /**
   * Get the flag's field entry form.
   *
   * @param FlaggingInterface $flagging
   *   The flagging from which to get the form.
   * @param string|null $operation
   *   The operation identifying the form variant to return.
   *
   * @return array
   *   The form array.
   */
  protected function getForm(FlaggingInterface $flagging, $operation = 'default') {
    return $this->entityFormBuilder()->getForm($flagging, $operation);
  }

}
