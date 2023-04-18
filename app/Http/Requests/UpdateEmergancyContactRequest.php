<?php
/*
 * File name: UpdateEmergancyContactRequest.php
 * Last modified: 2021.01.21 at 22:12:17
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2021
 */

namespace App\Http\Requests;

use App\Models\EmergancyContact;
use Illuminate\Foundation\Http\FormRequest;

class UpdateEmergancyContactRequest extends FormRequest
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
        return EmergancyContact::$rules;
    }

    /**
     * @param array $keys
     * @return array
     */
    public function all($keys = NULL): array
    {
        $input = parent::all();
        return $input;
    }
}