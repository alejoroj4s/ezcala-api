@props(['organizationId'])

@if ($organizationId)
    @php
        $customFields = \App\Models\CustomField::where('organization_id', $organizationId)->get();
    @endphp

    @foreach ($customFields as $field)
        <x-filament::input type="{{ $field->type }}" label="{{ $field->name }}" name="custom_fields[{{ $field->id }}]" />
    @endforeach
@endif