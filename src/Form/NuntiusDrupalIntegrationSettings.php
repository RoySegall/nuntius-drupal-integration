<?php

namespace Drupal\nuntius_drupal_integration\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class NuntiusDrupalIntegrationSettings.
 */
class NuntiusDrupalIntegrationSettings extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'nuntius_drupal_integration.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'nuntius_drupal_integration_settings';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('nuntius_drupal_integration.settings');

    $form['token'] = [
      '#type' => 'textfield',
      '#title' => t('Nuntius token'),
      '#description' => t('A token validation in nuntius and on Drupal'),
      '#default_value' => $config->get('token'),
      '#required' => TRUE,
    ];

    $form['slack_address'] = [
      '#type' => 'textfield',
      '#title' => t('Nuntius slack address'),
      '#description' => t('Set the address to which slack updates will be sent.'),
      '#default_value' => $config->get('slack_address'),
    ];

    $form['facebook_address'] = [
      '#type' => 'textfield',
      '#title' => t('Nuntius facebook address'),
      '#description' => t('Set the address to which slack updates will be sent.'),
      '#default_value' => $config->get('facebook_address'),
    ];

    $form['slack_room'] = [
      '#type' => 'textfield',
      '#title' => t('Slack room'),
      '#description' => t('Which room will be notified there something new on the site'),
      '#default_value' => $config->get('slack_room'),
      '#required' => TRUE,
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $config = $this->config('nuntius_drupal_integration.settings');

    $config->set('token', $form_state->getValue('token'));
    $config->set('slack_address', $form_state->getValue('slack_address'));
    $config->set('facebook_address', $form_state->getValue('facebook_address'));
    $config->set('slack_room', $form_state->getValue('slack_room'));
    $config->save();
  }

}
