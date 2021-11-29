<?php

namespace DummyNamespace;

use Illuminate\Foundation\Http\FormRequest;

class DummyRequestClass extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [];
    }

    public function attributes()
    {
        return [];
    }
}
