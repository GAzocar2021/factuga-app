<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InvoiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
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
            'number'                    => ['required', 'string', 'unique:invoices,number'],
            'date'                      => ['required', 'date_format:d/m/Y'],
            'user_id'                   => ['required', 'numeric'],
            'purchase'                  => ['required'],
            'purchase.*.amount'         => ['required', 'numeric'],
            'purchase.*.product_id'     => ['required', 'numeric'],
            'purchase.*.quantity'       => ['required', 'numeric']
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'date.required'                     => 'El campo fecha es requerido',
            'date.date_format'                  => 'El campo fecha debe tener un formato DD/MM/YYYY',
            'user_id.required'                  => 'El campo cliente es requerido',
            'purchase.required'                 => 'Es necesario agregar minimo un concepto',
            'purchase.*.amount.required'        => 'El campo en el concepto precio es requerido',
            'purchase.*.product_id.required'    => 'El campo en el concepto producto es requerido',
            'purchase.*.quantity.required'      => 'El campo en el concepto cantidad es requerido'
        ];
    }
}
