<?php
namespace Webmachine\FormField;

class FormField {
    
    /*
     * What should each form element be
     * wrapped within?
    */
    protected $wrapper;

    /*
     * What class should the wrapper
     * element receive?
    */
    protected $wrapperClass;

    /**
     * Should form inputs receive a class name?
     */
    protected $inputClass;
    
    /**
     * Should form labels receive a class name?
     */
    protected $labelClass;    

    /**
     * Empty option text for selects
     */
    protected $emptyOptionText;      
    
    /**
     * Init config params
     */
    public function __construct() {
        $this->resetConfigs();
    }
    
    /**
     * Build Text input
     * 
     * @param string $name field name
     * @param array $attributes
     * @return string
     */    
    public function text($name, $attributes = []) {
        $format = '<input type="text" name="%s" class="%s" id="%s"%s>';
        return $this->format($format, $name, $attributes, 'text');
    }
    
    /**
     * Build Password input
     * 
     * @param string $name field name
     * @param array $attributes
     * @return string
     */    
    public function password($name, $attributes = []) {
        $format = '<input type="password" name="%s" class="%s" id="%s"%s>';
        return $this->format($format, $name, $attributes, 'password');
    }    
    
    /**
     * Build File input
     * 
     * @param string $name field name
     * @param array $attributes
     * @return string
     */
    public function file($name, $attributes = []) {
        $format = '<input type="file" name="%s" class="%s" id="%s"%s>';
        return $this->format($format, $name, $attributes, 'file');
    }

    /**
     * Build Textarea input
     * 
     * @param string $name field name
     * @param array $attributes
     * @return string
     */
    public function textarea($name, $attributes = []) {
        $format = '<textarea name="%s" class="%s" id="%s"%s></textarea>';
        return $this->format($format, $name, $attributes, 'textarea');
    }
    
    /**
     * Build Select input
     * 
     * @param string $name field name
     * @param array $options
     * @param array $attributes
     * @param bool $use_empty_option if true sets first option by default
     * @return string
     */
    public function select($name, $options, $attributes = [], $use_empty_option = TRUE) {
        $select_format = '<select name="%s" class="%s" id="%s"%s>{OPTIONS}</select>';
        $option_format = '<option value="%s">%s</option>';
        $is_assoc = array_keys($options) !== range(0, count($options) - 1);
        
        $label = isset($attributes['label'])? $attributes['label'] : $this->prettifyFieldName($name);
        $formatted_options = $use_empty_option? [sprintf($option_format, '', $this->emptyOptionText . ' ' . $label)] : [];
        
        foreach ($options as $val => $opt) {
            $val = $is_assoc? $val : $opt;
            $formatted_options[] = sprintf($option_format, $val, $opt);
        }
        $select_format = str_replace('{OPTIONS}', implode("\n", $formatted_options), $select_format);
        return $this->format($select_format, $name, $attributes, 'textarea');
    }
    
    /**
     * Build hidden input
     * 
     * @param string $name field name
     * @param array $attributes
     * @return string
     */
    public function hidden($name, $attributes = []) {
        $value = isset($attributes['value'])? $attributes['value'] : '';
        return sprintf('<input type="hidden" name="%s" value="%s"%s>', $name, $value, $this->setAttributes($attributes, ['type', 'name', 'value']));
    }
    
    /**
     * Build button input
     * 
     * @param string $label button label
     * @param array $attributes
     * @return string
     */
    public function button($label, $attributes = []) {
        $type = isset($attributes['type'])? $attributes['type'] : 'button';
        return sprintf('<button type="%s"%s>%s</button>', $type,  $this->setAttributes($attributes, ['type']), $label);
    }

    /**
     * Form open
     * 
     * @param array $attributes
     * @return string
     */
    public function open($attributes = []) {
        $method = isset($attributes['method'])? $attributes['method'] : 'POST';
        $enctype = isset($attributes['file']) && $attributes['file']? ' enctype="multipart/form-data"' : '';
        return sprintf('<form method="%s"%s%s>', $method, $enctype, $this->setAttributes($attributes, ['method', 'file', 'enctype']));
    }
    
    /**
     * Form close
     * 
     * @return string
     */
    public function close() {
        return '</form>';
    }
    
    /**
     * Set Default config
     * 
     * @param string $option
     * @param string $value
     * @return void
     */
    public function setConfig($option, $value) {
        if(isset($this->$option)) $this->$option = $value;
    }

    /**
     * Set properties from config
     * 
     * @return void
     */
    public function resetConfigs() {
        $this->wrapper = config('formfield.wrapper');
        $this->wrapperClass = config('formfield.wrapperClass');
        $this->inputWrapper = config('formfield.inputWrapper');
        $this->inputWrapperClass = config('formfield.inputWrapperClass');
        $this->inputClass = config('formfield.inputClass');
        $this->labelClass = config('formfield.labelClass');
        $this->emptyOptionText = config('formfield.emptyOptionText');
    }    
    
    /**
     * Field Format
     * 
     * @param string $format input format
     * @param string $name field name
     * @param array $attributes
     * @param string $type input type
     * @return string
     */
    protected function format($format, $name, $attributes, $type) {
        $label = $this->createLabel($name, $attributes);
        $class = isset($attributes['class'])? $attributes['class'] : $this->inputClass;
        $id = isset($attributes['id'])? $attributes['id'] : $name;
        $field = sprintf($format, $name, $class, $id, $this->setAttributes($attributes));
        $field = sprintf($this->createInputWrapper(), $field);
        return sprintf($this->createWrapper(), $label . "\n" . $field);
    }    
    
    /**
     * Set Atributes from array
     * 
     * @param array $attributes
     * @param array $banned
     * @return string
     */
    protected function setAttributes($attributes, $banned = ['type', 'class', 'id', 'label']) {
        $result = [];
        foreach ($attributes as $attr => $val) {
            if(in_array($attr, $banned)) continue;
            if(is_bool($val)) {
                $result[] = $val? " $attr" : "";
            } else {
                $result[] = " $attr=\"$val\"";
            }
        }
        return implode('', $result);
    }

    /**
     * Prepare the wrapping container for each field.
     * 
     * @return string
     */
    protected function createWrapper() {
        $wrapper = $this->wrapper;
        $wrapperClass = $this->wrapperClass;
        return $wrapper == ''? '%s': "<$wrapper class='$wrapperClass'>%s</$wrapper>";
    }
    
    /**
     * Prepare the wrapping container for each field.
     * 
     * @return string
     */
    protected function createInputWrapper() {
        $wrapper = $this->inputWrapper;
        $wrapperClass = $this->inputWrapperClass;
        return $wrapper == ''? '%s': "<$wrapper class='$wrapperClass'>%s</$wrapper>";
    }    
    
    /**
     * Create label
     * 
     * @param string $name field name
     * @param array $attributes
     * @return type
     */
    protected function createLabel($name, $attributes) {
        $format = '<label class="%s" for="%s">%s</label>';
        $class = isset($attributes['label_class'])? $attributes['label_class'] : $this->labelClass;
        $for = isset($attributes['id'])? $attributes['id'] : $name;
        $label = isset($attributes['label'])? $attributes['label'] : $this->prettifyFieldName($name);
        
        return sprintf($format, $class, $for, $label);
    }

    /**
     * Clean up the field name for the label
     *
     * @param string $name
     * @return string
     */
    protected function prettifyFieldName($name) {
        return ucwords(preg_replace('/(?<=\w)(?=[A-Z])/', " $1", $name));
    }

}
