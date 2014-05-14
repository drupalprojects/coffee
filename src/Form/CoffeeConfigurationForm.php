<?php

/**
 * @file
 * Contains \Drupal\coffee\Form\CoffeeConfigurationForm.
 */

namespace Drupal\coffee\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Component\Utility\String;
use Symfony\Component\DependencyInjection\ContainerInterface;


/**
 * Configure Coffee for this site.
 */
class CoffeeConfigurationForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormID() {
    return 'coffee_configuration_form';
  }

  /**
   * Implements \Drupal\Core\Form\FormInterface::buildForm().
   */
  public function buildForm(array $form, array &$form_state) {
    $config = $this->config('coffee.configuration');

    $menus = menu_ui_get_menus();

    if (!empty($menus)) {
      // Settings for coffee.
      $form['coffee_menus'] = array(
        '#type' => 'checkboxes',
        '#title' => t('Menus to include'),
        '#description' => t('Select the menus that should be used by Coffee to search.'),
        '#options' => $menus,
        '#required' => TRUE,
        '#default_value' => (array) $config->get('coffee_menus'),
      );
    }
    return parent::buildForm($form, $form_state);
  }

  /**
   * Implements \Drupal\Core\Form\FormInterface::submitForm().
   */
  public function submitForm(array &$form, array &$form_state) {

    $this->config('coffee.configuration')
    ->set('coffee_menus', $form_state['values']['coffee_menus'])
    ->save();

    parent::submitForm($form, $form_state);
    // @todo Implement Cache::invalidateTags().
  }

}