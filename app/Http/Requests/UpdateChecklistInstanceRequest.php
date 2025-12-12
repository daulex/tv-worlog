<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateChecklistInstanceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('checklistInstance'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [];

        // Get the checklist instance to validate item responses
        $checklistInstance = $this->route('checklistInstance');
        if ($checklistInstance) {
            foreach ($checklistInstance->itemInstances as $itemInstance) {
                $item = $itemInstance->checklistItem;
                $fieldName = "items.{$item->id}.value";

                switch ($item->type) {
                    case 'bool':
                        $rules[$fieldName] = 'nullable|in:0,1,true,false';
                        break;
                    case 'text':
                        $rules[$fieldName] = 'nullable|string|max:255';
                        break;
                    case 'number':
                        $rules[$fieldName] = 'nullable|numeric';
                        break;
                    case 'textarea':
                        $rules[$fieldName] = 'nullable|string|max:1000';
                        break;
                }
            }
        }

        return $rules;
    }
}
