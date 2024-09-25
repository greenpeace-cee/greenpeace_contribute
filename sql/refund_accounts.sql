-- Replace IBANs with bank account ID's in CustomField refund_account

ALTER TABLE civicrm_value_contribution_information
    RENAME COLUMN refund_account TO refund_account_copy;

ALTER TABLE civicrm_value_contribution_information
    ADD COLUMN refund_account INT UNSIGNED NULL;

UPDATE civicrm_value_contribution_information
    SET refund_account =
        (SELECT ba_id FROM civicrm_bank_account_reference bar
         JOIN civicrm_bank_account ba ON ba.id = bar.ba_id
         WHERE bar.reference = refund_account_copy AND ba.contact_id = 1)
    WHERE refund_account_copy IS NOT NULL;

ALTER TABLE civicrm_value_contribution_information
    DROP COLUMN refund_account_copy;
