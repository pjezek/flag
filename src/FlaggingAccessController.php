<?php
/**
 * @file
 * Contains \Drupal\flag\FlaggingAccessController.
 */

namespace Drupal\flag;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Controller\ControllerBase;
use Drupal\flag\Entity\Flag;
use Symfony\Component\HttpFoundation\Request;

/**
 * Controls flagging access permission.
 */
class FlaggingAccessController extends ControllerBase {

  /**
   * Checks flagging permission.
   *
   * @param \Drupal\flag\FlagInterface $flag
   *   The flag entity.
   *
   * @return \Drupal\Core\Access\AccessResult
   *   Returns indication value for flagging access permission.
   */
  public function checkFlag(FlagInterface $flag) {
    return AccessResult::allowedIf($flag->hasActionAccess('flag'));
  }

  /**
   * Checks unflagging permission.
   *
   * @param \Drupal\flag\FlagInterface $flag
   *   The flag entity.
   *
   * @return \Drupal\Core\Access\AccessResult
   *   Returns indication value for unflagging access permission.
   */
  public function checkUnflag(FlagInterface $flag) {
    return AccessResult::allowedIf($flag->hasActionAccess('unflag'));
  }

}
