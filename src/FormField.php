<?php
namespace Webmachine\FormField;

class FormField {
    
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
        $formatted_options = $use_empty_option? [sprintf($option_format, '', config('formfield.emptyOptionText') . ' ' . $label)] : [];
        
        foreach ($options as $val => $opt) {
            $val = $is_assoc? $val : $opt;
            $formatted_options[] = sprintf($option_format, $val, $opt);
        }
        $select_format = str_replace('{OPTIONS}', implode("\n", $formatted_options), $select_format);
        return $this->format($select_format, $name, $attributes, 'textarea');
    }
    
    public function hidden($name, $attributes = []) {
        $value = isset($attributes['value'])? $attributes['value'] : '';
        $id = isset($attributes['id'])? $attributes['id'] : $name;
        return sprintf('<input type="hidden" name="%s" value="%s" id="%s">', $name, $value, $id);
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
        $class = isset($attributes['class'])? $attributes['class'] : config('formfield.inputClass');
        $id = isset($attributes['id'])? $attributes['id'] : $name;
        $field = sprintf($format, $name, $class, $id, $this->setAttributes($attributes));
        return sprintf($this->createWrapper(), $label . "\n" . $field);
    }    
    
    /**
     * Set Atributes from array
     * 
     * @param array $attributes
     * @return string
     */
    protected function setAttributes($attributes) {
        $result = [];
        foreach ($attributes as $attr => $val) {
            if(in_array($attr, ['class', 'id'])) continue;
            $result[] = "$attr=\"$val\"";
        }
        return implode('', $result);
    }

    /**
     * Prepare the wrapping container for each field.
     * 
     * @return string
     */
    protected function createWrapper() {
        $wrapper = config('formfield.wrapper');
        $wrapperClass = config('formfield.wrapperClass');
        return "<$wrapper class='$wrapperClass'>%s</$wrapper>";
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
        $class = isset($attributes['label_class'])? $attributes['label_class'] : config('formfield.labelClass');
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
