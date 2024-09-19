<?php

namespace Civi\Api4\Service\Autocomplete;

use Civi\API\Event\PrepareEvent;
use Civi\Core\Event\GenericHookEvent;
use Civi\Core\HookInterface;
use Civi\Core\Service\AutoService;

/**
 * @service
 * @internal
 */
class GreenpeaceContributeBankAccountAutocompleteProvider extends AutoService implements HookInterface {

  /**
   * Limit the autocomplete results for
   * `Contribution.contribution_information.from_ba` to bank accounts owned by a
   * specific contact
   *
   * @param \Civi\API\Event\PrepareEvent $event
   */
  public static function on_civi_api_prepare(PrepareEvent $event) {
    $api_request = $event->getApiRequest();

    if (!is_object($api_request)) return;
    if (!is_a($api_request, 'Civi\Api4\Generic\AutocompleteAction')) return;
    if (!str_contains($api_request->getFormName(), 'CRM_Custom_Form_CustomDataByType')) return;
    if ($api_request->getFieldName() !== 'Contribution.contribution_information.from_ba') return;

    $referer_header = parse_url($_SERVER['HTTP_REFERER']);
    parse_str($referer_header['query'], $url_params);
    $contact_id = $url_params['cid'];

    $api_request->addFilter('contact_id', $contact_id);
  }

  /**
   * Include bank account references (IBANs) and contact IDs in selection for
   * bank account autocomplete results
   *
   * @param Civi\Core\Event\GenericHookEvent $event
   */
  public static function on_civi_search_autocompleteDefault(GenericHookEvent $event) {
    if (!is_array($event->savedSearch)) return;
    if ($event->savedSearch['api_entity'] !== 'BankAccount') return;

    $event->savedSearch['api_params'] = [
      'version' => 4,
      'select' => [
         'id',
         'ba_ref.reference',
         'contact_id',
       ],
       'join' => [
         ['BankAccountReference AS ba_ref', 'LEFT', ['id', '=', 'ba_ref.ba_id']],
       ],
    ];
  }

  /**
   * Display bank account references (IBANs) and contact names in bank account
   * autocomplete results
   *
   * @param Civi\Core\Event\GenericHookEvent $event
   */
  public function on_civi_search_defaultDisplay(GenericHookEvent $event): void {
    if ($event->display['type'] !== 'autocomplete') return;
    if ($event->savedSearch['api_entity'] !== 'BankAccount') return;

    $event->display['settings'] = [
      'sort' => [
        ['id', 'ASC'],
      ],
      'columns' => [
        [
          'type' => 'field',
          'key'  => 'ba_ref.reference',
        ],
        [
          'type'    => 'field',
          'key'     => 'id',
          'rewrite' => '#[id] owned by [contact_id.display_name]',
        ],
      ],
    ];
  }

}
