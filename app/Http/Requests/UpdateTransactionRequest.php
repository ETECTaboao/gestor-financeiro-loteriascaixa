<?php

// app/Http/Requests/UpdateTransactionRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTransactionRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Permite o envio do formulário
    }

    public function rules()
    {
        return [
            'type' => 'required|in:gasto,ganho',
            'category' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'type.required' => 'O tipo de transação é obrigatório.',
            'category.required' => 'A categoria é obrigatória.',
            'amount.required' => 'O valor é obrigatório.',
            'amount.min' => 'O valor deve ser maior que zero.',
        ];
    }
}


