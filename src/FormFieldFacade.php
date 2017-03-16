<?php
namespace Webmachine\FormField;

use Illuminate\Support\Facades\Facade;

class FormFieldFacade extends Facade {

    protected static function getFacadeAccessor()
    {
        return 'form_field';
    }
}