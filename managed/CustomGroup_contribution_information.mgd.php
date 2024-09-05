<?php

use CRM_Customfields_ExtensionUtil as E;

return [
  [
    'name' => 'CustomGroup_contribution_information',
    'entity' => 'CustomGroup',
    'cleanup' => 'never',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'contribution_information',
        'title' => E::ts('Additional Information'),
        'extends' => 'Contribution',
        'table_name' => 'civicrm_value_contribution_information',
        'style' => 'Inline',
        'weight' => 45,
        'collapse_adv_display' => TRUE,
      ],
      'match' => [
        'name',
        'extends',
      ],
    ],
  ],
  [
    'name' => 'CustomGroup_contribution_information_CustomField_cancellation_costs',
    'entity' => 'CustomField',
    'cleanup' => 'never',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'contribution_information',
        'name' => 'cancellation_costs',
        'label' => E::ts('Cancellation / Refund Costs'),
        'data_type' => 'Money',
        'html_type' => 'Text',
        'is_searchable' => TRUE,
        'is_search_range' => TRUE,
        'column_name' => 'cancellation_costs',
      ],
      'match' => [
        'name',
      ],
    ],
  ],
  [
    'name' => 'CustomGroup_contribution_information_CustomField_refund_amount',
    'entity' => 'CustomField',
    'cleanup' => 'never',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'contribution_information',
        'name' => 'refund_amount',
        'label' => E::ts('refund_amount'),
        'data_type' => 'Money',
        'html_type' => 'Text',
        'column_name' => 'refund_amount',
      ],
      'match' => [
        'name',
      ],
    ],
  ],
  [
    'name' => 'CustomGroup_contribution_information_CustomField_refund_references',
    'entity' => 'CustomField',
    'cleanup' => 'never',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'contribution_information',
        'name' => 'refund_references',
        'label' => E::ts('IMB references of the refund transactions'),
        'html_type' => 'Text',
        'is_searchable' => TRUE,
        'column_name' => 'refund_references',
      ],
      'match' => [
        'name',
      ],
    ],
  ],
  [
    'name' => 'CustomGroup_contribution_information_CustomField_to_ba',
    'entity' => 'CustomField',
    'cleanup' => 'never',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'contribution_information',
        'name' => 'to_ba',
        'label' => E::ts('GP Bank Account'),
        'data_type' => 'EntityReference',
        'html_type' => 'Select',
        'is_searchable' => TRUE,
        'column_name' => 'to_ba',
        'fk_entity' => 'BankAccount',
      ],
      'match' => [
        'name',
      ],
    ],
  ],
  [
    'name' => 'CustomGroup_contribution_information_CustomField_from_ba',
    'entity' => 'CustomField',
    'cleanup' => 'never',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'contribution_information',
        'name' => 'from_ba',
        'label' => E::ts("Donor's Bank Account"),
        'data_type' => 'EntityReference',
        'html_type' => 'Select',
        'is_searchable' => TRUE,
        'column_name' => 'from_ba',
        'fk_entity' => 'BankAccount',
      ],
      'match' => [
        'name',
      ],
    ],
  ],
  [
    'name' => 'CustomGroup_contribution_information_CustomField_refund_account',
    'entity' => 'CustomField',
    'cleanup' => 'never',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'contribution_information',
        'name' => 'refund_account',
        'label' => E::ts('Refund Account'),
        'html_type' => 'Text',
        'is_searchable' => TRUE,
        'column_name' => 'refund_account',
      ],
      'match' => [
        'name',
      ],
    ],
  ],
  [
    'name' => 'OptionGroup_billing_operator',
    'entity' => 'OptionGroup',
    'cleanup' => 'never',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'billing_operator',
        'title' => E::ts('Billing Operator'),
        'description' => E::ts('Billing Operators for SMS donations'),
        'option_value_fields' => [
          'name',
          'label',
          'description',
        ],
      ],
      'match' => [
        'name',
      ],
    ],
  ],
  [
    'name' => 'OptionGroup_billing_operator_OptionValue_Drei',
    'entity' => 'OptionValue',
    'cleanup' => 'never',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'billing_operator',
        'label' => E::ts('Drei'),
        'value' => '3',
        'name' => 'Drei',
      ],
      'match' => [
        'name',
        'option_group_id',
      ],
    ],
  ],
  [
    'name' => 'OptionGroup_billing_operator_OptionValue_T_Mobile',
    'entity' => 'OptionValue',
    'cleanup' => 'never',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'billing_operator',
        'label' => E::ts('T-Mobile'),
        'value' => '2',
        'name' => 'T_Mobile',
      ],
      'match' => [
        'name',
        'option_group_id',
      ],
    ],
  ],
  [
    'name' => 'OptionGroup_billing_operator_OptionValue_A1',
    'entity' => 'OptionValue',
    'cleanup' => 'never',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'billing_operator',
        'label' => E::ts('A1'),
        'value' => '1',
        'name' => 'A1',
      ],
      'match' => [
        'name',
        'option_group_id',
      ],
    ],
  ],
  [
    'name' => 'CustomGroup_contribution_information_CustomField_billing_operator',
    'entity' => 'CustomField',
    'cleanup' => 'never',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'contribution_information',
        'name' => 'billing_operator',
        'label' => E::ts('Billing Operator'),
        'data_type' => 'Int',
        'html_type' => 'Select',
        'is_searchable' => TRUE,
        'column_name' => 'billing_operator',
        'option_group_id.name' => 'billing_operator',
      ],
      'match' => [
        'name',
      ],
    ],
  ],
  [
    'name' => 'CustomGroup_contribution_information_CustomField_keyword',
    'entity' => 'CustomField',
    'cleanup' => 'never',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'contribution_information',
        'name' => 'keyword',
        'label' => E::ts('Keyword'),
        'html_type' => 'Text',
        'is_searchable' => TRUE,
        'text_length' => 160,
        'column_name' => 'keyword',
      ],
      'match' => [
        'name',
      ],
    ],
  ],
];
