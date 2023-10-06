<?php


use App\Models\Agent;
use App\Models\Config;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


/**
 * Get configuration value
 *
 * @param  String  $config_name
 * @param  null  $default_value
 * @return mixed|string
 */
function getConfig(String $config_name, $default_value = NULL)
{
    $config = Config::where('name', $config_name)->where('status',1)->first();

    if ($config!=NULL && $config->config_type == 'TX') {
        return $config->value;
    }
    elseif ($config!=NULL && $config->config_type == 'DD') {
        return json_decode($config->options_array);
    } else {
        if($default_value == NULL)
            return '';
        else
            return $default_value;
    }
}

/**
 * Get configuration as array
 *
 * @param  String  $config_name
 * @param  null  $default_value
 * @return mixed|string
 */
function getConfigArray(String $config_name, $default_value = NULL)
{
    $config = Config::where('name', $config_name)->where('status',1)->first();

    if ($config!=NULL && $config->config_type == 'TX') {
        return $config->value;
    }
    elseif ($config!=NULL && $config->config_type == 'DD') {
        return json_decode($config->options_array, true);
    } else {
        if($default_value == NULL)
            return '';
        else
            return $default_value;
    }
}

/**
 * Get config text value or default value
 *
 * @param  String  $config_name
 * @param  null  $default_value
 * @return mixed|string
 */
function getConfigValue(String $config_name, $default_value = NULL)
{
    $config = Config::where('name', $config_name)->where('status',1)->first();

    if ($config!=NULL) {
        return $config->value;
    }
    else {
        if($default_value == NULL)
            return '';
        else
            return $default_value;
    }
}

/**
 * Get configuration value by key
 *
 * @param  String  $config_name
 * @param  String  $array_key
 * @param  null  $default_value
 * @return mixed|string
 */
function getConfigArrayValueByKey(String $config_name, String $array_key, $default_value = NULL)
{
    $config = Config::where('name', $config_name)->where('status',1)->first();

    if ($config!=NULL && $config->config_type == 'DD'){
        $options_array= json_decode($config->options_array,true);
        return $options_array[$array_key];
    }  else
        if($default_value == NULL)
            return '';
        else
            return $default_value;
}

/**
 * Get configuration key by value
 *
 * @param  String  $config_name
 * @param  String  $array_value
 * @param  null  $default_value
 * @return false|int|mixed|string
 */
function getConfigArrayKeyByValue(String $config_name, String $array_value, $default_value = NULL)
{
    $config = Config::where('name', $config_name)->where('status',1)->first();
    if ($config!=NULL && $config->config_type == 'DD'){
        $options_array= json_decode($config->options_array,true);
        $key = array_search($array_value,$options_array);
        return $key;
    } else
        if($default_value == NULL)
            return '';
        else
            return $default_value;
}

/**
 * Send formatted json response (JSend)
 *
 * @param  String  $status
 * @param  String  $message
 * @param  array|null  $data
 * @param  Int  $code
 * @return JsonResponse
 */
function apiResponse(String $status = 'success'|'error', String $message = '', Array|object $data = []): JsonResponse
{
    $data_key = 'data';
    $code = 200;
    if ($status == 'error') {
        $data_key = 'errors';
        $code = 400;
    }
    return response()->json([
        'status' => $status,
        'message' => $message,
        $data_key => $data
    ], $code);
}

/**
 * Make array form comma separated string
 *
 * @param $string
 * @return array|false|string[]
 */
function makeArray($string)
{
    return explode(',', trim($string, '[]')) ?? [];
}

/**
 * Add error to log
 *
 * @param  Exception  $e
 */
function addErrorToLog(Exception $e, $place = null)
{
    if($place != null) Log::error($place);
    Log::error($e->getMessage()."\n".$e->getTraceAsString());
}

/**
 * Check isset and not empty given key on given array, then return if it found. else return default
 *
 * @param  string  $needle
 * @param  array  $haystack
 * @param  string  $default
 * @return mixed|string
 */
function in_data(string $needle, array $haystack, $default = '')
{
    if (isset($haystack[$needle]) && !empty($haystack[$needle])) {
        return $haystack[$needle];
    }
    return $default;
}

/**
 * Check unique ids with two arrays and remove that ids from first array
 *
 * @param array $array1
 * @param array $array2
 * @return array
 */
function remove_unique_ids($array1, $array2) {
    return array_filter($array1, function ($item) use ($array2) {
        return !in_array($item, $array2);
    });
}

/**
 * Bind placeholder variables into template
 *
 * @param array $replacements
 * @param string $template
 * @return string
 */
function bind_to_template($replacements, $template): string {
	return preg_replace_callback('/{{(.+?)}}/', function($matches) use ($replacements) {
		return $replacements[$matches[1]];
	}, $template);
}

/**
 * Iterate over a large set of data that parsed.
 *
 * @param Collection $data
 * @param Closure|null $map_columns
 * @param Closure|null $last_row
 * @return Generator $rows
 */
function generator($data, $map_columns = null, $last_row = null) {
    $count = $data->count() - 1;
    foreach ($data->lazy()->map($map_columns ?? fn ($item, $key) => $item) as $key => $row) {
        yield $row;
        if ($last_row != null && $result = $last_row($key, $row, $count)) {
            yield $result;
        }

    }
}

/**
 * Convert data unit to human readable string
 *
 * @param integer $bytes
 * @param integer $decimals
 * @return string $value
 */
function convert_filesize($bytes, $decimals = 3) {
    $size = array('B','kB','MB','GB','TB','PB','EB','ZB','YB');
    $factor = floor((strlen($bytes) - 1) / 3);
    return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$size[$factor];
}

/**
 * Add mask to email address
 *
 * @param $email
 * @return string
 */
function obfuscate_email($email)
{
    $em   = explode("@",$email);
    $name = implode('@', array_slice($em, 0, count($em)-1));
    $len  = floor(strlen($name)/2);

    return substr($name,0, $len) . str_repeat('*', $len) . "@" . end($em);
}

/**
 * Check if auth user is a agent
 *
 * @return bool
 */
function isAgent()
{
    if (Auth::user() &&  Auth::user()->hasRole(['AGENT']))
        return true;

    return false;
}

/**
 * Check if auth user is a admin
 *
 * @return bool
 */
function isAdmin()
{
    if (Auth::user() &&  Auth::user()->hasRole(['SUPER_ADMIN','ADMIN']))
        return true;

    return false;
}

/**
 * Get agent by auth user
 *
 * @param  null  $column
 * @return null
 */
function getAgentByUser($column = null)
{
    $agent = Agent::where('user_id', auth()->id())->first();
    if (!$agent) return null;

    if ($column == null) {
        return $agent;
    } else {
        return $agent->$column;
    }
}
