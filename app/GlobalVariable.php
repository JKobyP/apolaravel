<?php

namespace APOSite;

use Illuminate\Database\Eloquent\Model;

class GlobalVariable extends Model
{
    protected $fillable = ['key','value'];

    protected $primaryKey = 'key';

    public $timestamps = false;

    public static function ContractSigning(){
        return GlobalVariable::whereKey('contract_signing')->first();
    }

    public static function ShowInactive(){
        return GlobalVariable::whereKey('show_inactive')->first();
    }

    public function getValueAttribute($value){
        return ($value == "1");
    }
}
