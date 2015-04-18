<?php
/**
 * @file
 * Contains \Drupal\Tests\flag\Integration\Action\Base.
 */

namespace Drupal\Tests\flag\Integration\Action;

use Drupal\Tests\rules\Integration\RulesEntityIntegrationTestBase;

class FlagIntegrationTestBase extends RulesEntityIntegrationTestBase {

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();
    $this->enableModule('flag', ['Drupal\\flag' => $this->root . '/modules/flag/src']);

    $flagtypeMock = $this->getMockBuilder('Drupal\flag\FlagTypePluginManager')
      ->disableOriginalConstructor()
      ->getMock();
    $this->container->set('plugin.manager.flag.flagtype', $flagtypeMock);

    $linktypeMock = $this->getMockBuilder('Drupal\flag\ActionLinkPluginManager')
      ->disableOriginalConstructor()
      ->getMock();
    $this->container->set('plugin.manager.flag.linktype', $linktypeMock);
  }

  /**
   * Helper function to create a Mock of 'Drupal\flag\FlagService'.
   *
   * @return \PHPUnit_Framework_MockObject_MockObject
   */
  protected function getFlagServiceMock() {
    return $this->getMockBuilder('Drupal\flag\FlagService')
      ->disableOriginalConstructor()
      ->getMock();
  }

  protected function getFlagMock() {
    return $this->getMockBuilder('Drupal\flag\Entity\Flag')
      ->disableOriginalConstructor()
      ->getMock();
  }

}
