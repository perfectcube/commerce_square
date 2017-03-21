<?php

namespace Drupal\Tests\commerce_squareup\FunctionalJavascript;

use Drupal\FunctionalJavascriptTests\JSWebAssert;
use Drupal\Tests\commerce\Functional\CommerceBrowserTestBase;
use Drupal\Tests\commerce\FunctionalJavascript\JavascriptTestTrait;

/**
 * Tests the creation and configuration of the gateway.
 *
 * @group commerce_squareup
 */
class ConfigureGatewayTest extends CommerceBrowserTestBase {

  use JavascriptTestTrait;

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = [
    'commerce_payment',
    'commerce_squareup',
  ];

  /**
   * {@inheritdoc}
   */
  protected function getAdministratorPermissions() {
    return array_merge([
      'administer commerce_payment_gateway',
    ], parent::getAdministratorPermissions());
  }

  /**
   * Tests that a Squareup gateway can be configured.
   */
  public function testCreateSquareupGateway() {
    $this->drupalGet('admin/commerce/config/payment-gateways');
    $this->getSession()->getPage()->clickLink('Add payment gateway');
    $this->assertSession()->addressEquals('admin/commerce/config/payment-gateways/add');

    $this->getSession()->getPage()->fillField('Name', 'Square');
    $this->getSession()->getPage()->checkField('Squareup');
    $this->assertSession()->assertWaitOnAjaxRequest();

    $this->assertSession()->pageTextContains('Please provide a valid personal access token to select a location ID.');
    $this->getSession()->getPage()->fillField('Application Name', 'Drupal Commerce 2 Demo');
    $this->getSession()->getPage()->fillField('Application ID', 'sq0idp-nV_lBSwvmfIEF62s09z0-Q');
    $this->getSession()->getPage()->fillField('Personal Access Token', 'sq0atp-0N5GE_l_6-IDt4oz1dUXZQ');
    $this->getSession()->getPage()->fillField('Application ID', 'sq0idp-nV_lBSwvmfIEF62s09z0-Q');
    $this->assertSession()->assertWaitOnAjaxRequest();

    $this->assertSession()->fieldExists('Location ID');
    $this->getSession()->getPage()->selectFieldOption('Location ID', '2QB9VG5WN7WPE');
    $this->assertSession()->pageTextNotContains('Please provide a valid personal access token to select a location ID.');
    $this->getSession()->getPage()->pressButton('Save');

    $this->assertSession()->pageTextContains('Square');
  }

  /**
   * {@inheritdoc}
   *
   * @return \Drupal\FunctionalJavascriptTests\JSWebAssert
   *   A new web-assert option for asserting the presence of elements with.
   */
  public function assertSession($name = NULL) {
    return new JSWebAssert($this->getSession($name), $this->baseUrl);
  }

}