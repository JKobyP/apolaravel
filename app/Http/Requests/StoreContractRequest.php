<?php namespace APOSite\Http\Requests;

use APOSite\Http\Requests\Request;
use Illuminate\Support\Facades\Validator;

class StoreContractRequest extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
        //TODO add in authorization for contract storing requests.
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		$rules = [
            'display_name'=>['required','min:10'],
            'description'=>['required','min:40']
        ];
        return $rules;
        //TODO finish validation rules to validate all incoming requirement id's
	}

}
