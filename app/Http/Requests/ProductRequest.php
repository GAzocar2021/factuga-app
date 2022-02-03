<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
            'code'          => ['required', 'string'],
            'name'          => ['required', 'string'],
            'description'   => ['required', 'string'],
            'stock'         => ['required', 'numeric'],
            'alert_stock'   => ['required', 'numeric'],
            'cost'          => ['required', 'numeric'],
            'price'         => ['required', 'numeric'],
            'tax_perc'      => ['required', 'numeric'],
            'image'         => ['nullable']
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
            'code.required'         => 'El campo Code es obligatorio.',
            'name.required'         => 'El campo Nombre es obligatorio.',
            'description.required'  => 'El campo Descripción es obligatorio.',
            'cost.required'         => 'El campo Costo es obligatorio.',
            'price.required'        => 'El campo Precio es obligatorio.',
            'tax_perc.required'     => 'El campo Precio es obligatorio.',
            'stock.required'        => 'El campo Stock es obligatorio.',
            'alert_stock.required'  => 'El campo Alerta Stock es obligatorio.'
        ];
    }
}
