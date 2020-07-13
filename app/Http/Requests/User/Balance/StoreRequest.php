<?php

namespace App\Http\Requests\User\Balance;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (Str::contains(strtolower($this->getContentType()), 'xml')) {
            $document = simplexml_load_string($this->getContent());
            $this->request->set('type', $document->getName());
            foreach ($document->attributes() as $value) {
                $this->request->set($value->getName(), (string) $value);
            }
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'type' => 'required',
            'amount' => 'required|integer|min:1',
            'tid' => 'required',
            'uid' => 'required|exists:users,id',
        ];
    }
}
