<?php

namespace App\Traits;

use App\Models\CustomField;
use App\Models\CustomFieldValue;

trait HandlesCustomFields
{
    protected function prepareCustomFieldsData(array $data): array
    {
        $customFields = CustomField::where('organization_id', $data['organization_id'])->get();

        $data['custom_fields'] = [];

        foreach ($customFields as $customField) {
            $customFieldKey = 'custom_' . $customField->id;
            if (isset($data[$customFieldKey])) {
                $data['custom_fields'][] = [
                    'custom_field_id' => $customField->id,
                    'value' => $data[$customFieldKey],
                ];
                unset($data[$customFieldKey]);
            }
        }

        return $data;
    }

    protected function createCustomFields(array $data, $contact): void
    {
        foreach ($data['custom_fields'] as $customFieldValue) {
            CustomFieldValue::create([
                'contact_id' => $contact->id,
                'custom_field_id' => $customFieldValue['custom_field_id'],
                'value' => $customFieldValue['value'],
            ]);
        }
    }

    protected function updateCustomFields(array $data, $contact): void
    {
        foreach ($data['custom_fields'] as $customFieldValue) {
            CustomFieldValue::updateOrCreate(
                ['contact_id' => $contact->id, 'custom_field_id' => $customFieldValue['custom_field_id']],
                ['value' => $customFieldValue['value']]
            );
        }
    }
}