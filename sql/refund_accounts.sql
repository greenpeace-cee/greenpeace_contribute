-- Replace IBANs with bank account ID's in CustomField refund_account

ALTER TABLE civicrm_value_contribution_information
    RENAME COLUMN refund_account TO refund_account_copy;

ALTER TABLE civicrm_value_contribution_information
    ADD COLUMN refund_account INT UNSIGNED NULL;

UPDATE civicrm_value_contribution_information
    SET refund_account =
        (SELECT ba_id FROM civicrm_bank_account_reference WHERE reference = refund_account_copy)
    WHERE refund_account_copy IS NOT NULL;

ALTER TABLE civicrm_value_contribution_information
    DROP COLUMN refund_account_copy;
