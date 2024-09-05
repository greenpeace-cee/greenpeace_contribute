<?php

require_once 'greenpeace_contribute.civix.php';

use CRM_GreenpeaceContribute_ExtensionUtil as E;

/**
 * Implements hook_civicrm_config().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_config/
 */
function greenpeace_contribute_civicrm_config(&$config): void {
  _greenpeace_contribute_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_install
 */
function greenpeace_contribute_civicrm_install(): void {
  _greenpeace_contribute_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_enable
 */
function greenpeace_contribute_civicrm_enable(): void {
  _greenpeace_contribute_civix_civicrm_enable();
}
