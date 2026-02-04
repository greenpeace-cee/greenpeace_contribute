<?php

namespace Civi\GreenpeaceContribute;

use Civi;
use Civi\Api4\Activity;
use Civi\Api4\ActivityContact;
use Civi\Core\Event\GenericHookEvent;
use Civi\Core\Service\AutoSubscriber;

/**
 * Maintain an activity record for contribution
 *
 * Similar to the contribution activity handling in CRM_Contribute_BAO_Contribution,
 * this creates, updates and deletes contribution activities when contributions
 * are modified. Unlike core, this ensures an activity is always created even if
 * the contribution is not yet completed.
 *
 * @package Civi\GreenpeaceContribute
 */
class ContributionActivity extends AutoSubscriber {

  public static function getSubscribedEvents() {
    return [
      'hook_civicrm_post' => ['hook_civicrm_post', 0],
    ];
  }

  public static function hook_civicrm_post(GenericHookEvent $event) {
    if ($event->entity !== 'Contribution' || !in_array($event->action, ['create', 'edit', 'delete']) || empty($event->id)) {
      return;
    }
    if ($event->action === 'delete') {
      Activity::delete(FALSE)
        ->addWhere('source_record_id', '=', $event->id)
        ->addWhere('activity_type_id:name', '=', 'Contribution')
        ->execute();
    }
    else {
      self::saveContributionActivity($event->id);
    }
  }

  /**
   * Create or update contribution activity
   *
   * This is mostly based on core code, but handles is_test and certain
   * contribution status values differently
   *
   * @param $contributionId
   *
   * @return void
   * @throws \CRM_Core_Exception
   * @throws \Civi\API\Exception\UnauthorizedException
   */
  public static function saveContributionActivity($contributionId) {
    $contribution = \CRM_Contribute_BAO_Contribution::findById($contributionId);
    // is_test handling is weird in core. skip contribution creation here
    if ($contribution->is_test) {
      return;
    }

    $existingActivity = Activity::get(FALSE)->setWhere([
      ['source_record_id', '=', $contribution->id],
      ['activity_type_id:name', '=', 'Contribution'],
      // by default, Activity API4 matches only is_test=0; this matches 0 and 1
      ['is_test', 'IS NOT NULL'],
    ])->execute()->first();
    $contribution_status = \CRM_Core_PseudoConstant::getName('CRM_Contribute_BAO_Contribution', 'contribution_status_id', $contribution->contribution_status_id);
    $activity_status = NULL;
    switch ($contribution_status) {
      case 'Completed':
      case 'Cancelled':
      case 'Failed':
      case 'Refunded':
      case 'Partially paid':
      case 'Pending refund':
      case 'Chargeback':
        $activity_status = 'Completed';
        break;

      case 'Pending':
      case 'In Progress':
        $activity_status = 'Scheduled';
        break;

      default:
        Civi::log()->warning("Unhandled contribution status {$contribution_status} when trying to determine activity status", ['contribution_id' => $contributionId]);
        break;

    }

    $activityParams = [
      'activity_type_id:name' => 'Contribution',
      'source_record_id' => $contribution->id,
      'activity_date_time' => $contribution->receive_date,
      'is_test' => (bool) $contribution->is_test,
      'skipRecentView' => TRUE,
      'subject' => \CRM_Activity_BAO_Activity::getActivitySubject($contribution),
      'campaign_id' => !is_numeric($contribution->campaign_id) ? NULL : $contribution->campaign_id,
      'id' => $existingActivity['id'] ?? NULL,
    ];
    if (!empty($activity_status)) {
      $activityParams['status_id:name'] = $activity_status;
    }
    if (!$activityParams['id']) {
      $activityParams['source_contact_id'] = (int) ($params['source_contact_id'] ?? (\CRM_Core_Session::getLoggedInContactID() ?: $contribution->contact_id));
      $activityParams['target_contact_id'] = ($activityParams['source_contact_id'] === (int) $contribution->contact_id) ? [] : [$contribution->contact_id];
    }
    else {
      [$sourceContactId, $targetContactId] = self::getActivitySourceAndTarget($activityParams['id']);

      if (empty($targetContactId) && $sourceContactId != $contribution->contact_id) {
        // If no target contact exists and the source contact is not equal to
        // the contribution contact, update the source contact
        $activityParams['source_contact_id'] = $contribution->contact_id;
      }
      elseif (isset($targetContactId) && $targetContactId != $contribution->contact_id) {
        // If a target contact exists and it is not equal to the contribution
        // contact, update the target contact
        $activityParams['target_contact_id'] = [$contribution->contact_id];
      }
    }
    Activity::save(FALSE)->addRecord($activityParams)->execute();
  }

  private static function getActivitySourceAndTarget($activityId): array {
    $activityContactQuery = ActivityContact::get(FALSE)->setWhere([
      ['activity_id', '=', $activityId],
      ['record_type_id:name', 'IN', ['Activity Source', 'Activity Targets']],
    ])->execute();

    $sourceContactKey = \CRM_Core_PseudoConstant::getKey('CRM_Activity_BAO_ActivityContact', 'record_type_id', 'Activity Source');
    $targetContactKey = \CRM_Core_PseudoConstant::getKey('CRM_Activity_BAO_ActivityContact', 'record_type_id', 'Activity Targets');

    $sourceContactId = NULL;
    $targetContactId = NULL;

    for ($i = 0; $i < $activityContactQuery->count(); $i++) {
      $record = $activityContactQuery->itemAt($i);

      if ($record['record_type_id'] === $sourceContactKey) {
        $sourceContactId = $record['contact_id'];
      }

      if ($record['record_type_id'] === $targetContactKey) {
        $targetContactId = $record['contact_id'];
      }
    }

    return [$sourceContactId, $targetContactId];
  }

}
