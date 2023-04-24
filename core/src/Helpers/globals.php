<?php

if (!class_exists(WorkspaceKeyValueConvertible::class)) {
    class WorkspaceKeyValueConvertible
    {
        public function toObject() {

            $array = (array)$this;

            if (is_array($this)) {
                return (object)$array;
            }

            return $this;
        }
    }
}

if (!class_exists(WorkspaceKeyValueHelper::class)) {
    class WorkspaceKeyValueHelper extends WorkspaceKeyValueConvertible
    {
        public $required = false;
        public $field;
        public $type;
        public $details;
        public $display_name;
        public $options = [];

        public static function create($type, $field, $details, $display_name, $required = 0, $options = []) {
            $result = new WorkspaceKeyValueHelper();
            $result->type = $type;
            $result->field = $field;
            $result->details = $details;
            $result->display_name = $display_name;
            $result->required = $required;
            $result->options = $options;

            return $result;
        }

        public function getTranslatedAttribute($attribute) {
            return $this->display_name;
        }
    }
}

if (!class_exists(WorkspaceKeyValueTypeHelper::class)) {
    class WorkspaceKeyValueTypeHelper extends WorkspaceKeyValueConvertible
    {
        protected $id = 0;
        protected $key = null;

        public function setKey($key, $content) {
            $this->key = $key;
            $this->{$key} = $content;
        }

        public static function create($key, $content) {

            $result = new WorkspaceKeyValueTypeHelper();
            $result->setKey($key, $content);

            return $result;
        }

        public function getKey() { return $this->key; }
    }
}


if (!function_exists('workspace_key_value')){

	function workspace_key_value($type, $key, $content = '', $details = '', $placeholder = '', $required = 0){


        $row = WorkspaceKeyValueHelper::create($type, $key, $details, $placeholder, $required);
        $dataTypeContent = WorkspaceKeyValueTypeHelper::create($key, $content);
		$type = '<input type="hidden" value="' . $type . '" name="' . $key . '_type__workspace_keyvalue">';

        return app('voyager')->formField($row, '', $dataTypeContent->toObject()) . $details . $type;

	}

}

if (!function_exists('profile_field')){

	function profile_field($type, $key){

		$value = auth()->user()->profile($key);
		if($value){
			return workspace_key_value($type, $key, $value);
		} else {
			return workspace_key_value($type, $key);
		}

	}

}

if(!function_exists('stringToColorCode')){

	function stringToColorCode($str) {
	  $code = dechex(crc32($str));
	  $code = substr($code, 0, 6);
	  return $code;
	}

}

if(!function_exists('tailwindPlanColor')){

	function tailwindPlanColor($str) {
	  $code = dechex(crc32($str));
	  $code = substr($code, 0, 6);
	  return $code;
	}

}

if (!function_exists('theme_field')){

    function theme_field($type, $key, $title, $content = '', $details = '', $placeholder = '', $required = 0){
        $theme = \Workspace\Theme::where('folder', '=', ACTIVE_THEME_FOLDER)->first();
        $option_exists = $theme->options->where('key', '=', $key)->first();
        if(isset($option_exists->value)){
            $content = $option_exists->value;
        }
        $row = new class{ public function getTranslatedAttribute(){} };
        $row->required = $required;
        $row->field = $key;
        $row->type = $type;
        $row->details = $details;
        $row->display_name = $placeholder;
        $dataTypeContent = new class{ public function getKey(){} };
        $dataTypeContent->{$key} = $content;
        $label = '<label for="'. $key . '">' . $title . '<span class="how_to">You can reference this value with <code>theme(\'' . $key . '\')</code></span></label>';
        $details = '<input type="hidden" value="' . $details . '" name="' . $key . '_details__theme_field">';
        $type = '<input type="hidden" value="' . $type . '" name="' . $key . '_type__theme_field">';
        return $label . app('voyager')->formField($row, '', $dataTypeContent) . $details . $type . '<hr>';
    }

}

if (!function_exists('theme')){

    function theme($key, $default = ''){
        $theme = \Workspace\Theme::where('active', '=', 1)->first();

        if(Cookie::get('theme')){
            $theme_cookied = \Workspace\Theme::where('folder', '=', Cookie::get('theme'))->first();
            if(isset($theme_cookied->id)){
                $theme = $theme_cookied;
            }
        }
        if(isset($theme->options)){
            $value = $theme->options->where('key', '=', $key)->first();
            if(isset($value)) {
                return $value->value;
            }
        }
            

        return $default;
    }

}

if(!function_exists('theme_folder')){
    function theme_folder($folder_file = ''){

        if(defined('THEME_FOLDER') && THEME_FOLDER){
            return 'themes/' . THEME_FOLDER . $folder_file;
        }
        $theme = \Workspace\Theme::where('active', '=', 1)->first();
        //dd($theme);
        if(Cookie::get('theme')){
            $theme_cookied = \Workspace\Theme::where('folder', '=', Cookie::get('theme'))->first();
            if(isset($theme_cookied->id)){
                $theme = $theme_cookied;
            }
        }
        define('THEME_FOLDER', $theme->folder);
        return 'themes/' . $theme->folder . $folder_file;
    }
}

if(!function_exists('theme_folder_url')){
    function theme_folder_url($folder_file = ''){
        if(defined('THEME_FOLDER') && THEME_FOLDER){
            return url('themes/' . THEME_FOLDER . $folder_file);
        }
        $theme = \Workspace\Theme::where('active', '=', 1)->first();
        if(Cookie::get('theme')){
            $theme_cookied = \Workspace\Theme::where('folder', '=', Cookie::get('theme'))->first();
            if(isset($theme_cookied->id)){
                $theme = $theme_cookied;
            }
        }
        define('THEME_FOLDER', $theme->folder);
        return url('themes/' . $theme->folder . $folder_file);
    }
}





if (!function_exists('current_uuid')){

    function current_uuid(){
        return \DB::table('hostnames')
            ->select('websites.uuid')
            ->join('websites', 'hostnames.website_id', '=', 'websites.id')
            ->where('fqdn', request()->getHost())
            ->value('uuid');
    }

}

if (!function_exists('module_namespace_prefix')){

    function module_namespace_prefix(){
        $explode = explode(".",request()->getHost());
        if(current_uuid() && count($explode))
            return Illuminate\Support\Str::studly($explode[0]).'\\\\';
        return null;
    }

}