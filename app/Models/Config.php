<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Config extends Model
{
    protected $table = 'options';
    public $timestamps = false;
    protected $guarded = array();

    // table : options
    // return by code 
    // return type array , all thing by code
    public static function get_options($code = '')
    {
        $options = DB::table('options')->where('code', '=', $code)->get();

        $return = array();
        foreach ($options as $results) {
            $return[$results->option_key]   = $results->option_value;
        }
        return $return;
    }

    // table : options
    // return by code & option_key
    // return type text (one value)
    public static function get_option($code = '', $option_key = '')
    {
        $option = DB::table('options')
            ->where('code', '=', $code)
            ->where('option_key', '=', $option_key)
            ->first();
        //ex :
        /*
        code : pageHome
        option_key : banner_title
        option_value : Learn courses online        
        */
        //return if data is set
        return $option ? $option->option_value : '';
    }
    // write new options if code not exists else insert value to current code
    public static function save_options($code, $values)
    {

        //get the options first, this way, we can know if we need to update or insert options
        //we're going to create an array of keys for the requested code
        $options    = Config::get_options($code);


        //loop through the options and add each one as a new row
        foreach ($values as $key => $value) {

            //if the key currently exists, update the option
            if (array_key_exists($key, $options)) {
                DB::table('options')
                    ->where('option_key', $key)
                    ->where('code', $code)
                    ->update(['option_value' => $value]);
            }
            //if the key does not exist, add it
            else {
                DB::table('options')->insert(
                    ['code' => $code, 'option_key' => $key, 'option_value' => $value]
                );
            }
        }
    }
}
