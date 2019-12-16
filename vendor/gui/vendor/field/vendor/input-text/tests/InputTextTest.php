<?php
require_once (__DIR__ . '/../input-text.php');

class InputTextTest extends PHPUnit\Framework\TestCase
{

    /**
     * Testing constructor
     */
    public function test_constructor()
    {
        // setup
        $Field = new InputText([
            'name' => 'name',
            'required' => 1,
            'disabled' => 1,
            'name-prefix' => 'prefix',
            'batch' => 1,
            'toggler' => 'toggler-name',
            'toggle-value' => 3,
            'type' => 'string'
        ], '');

        // test body
        $Content = $Field->html();

        // assertions
        $this->assertContains('<input ', $Content, 'Open tag was not found');
        $this->assertContains('type="text"', $Content, '"Name" attribute was not found');
        $this->assertContains('name="prefix-name[{_creation_form_items_counter}]"', $Content, '"Name" attribute was not found');
        $this->assertContains('required="required"', $Content, '"Required" attribute was not found');
        $this->assertContains('disabled', $Content, '"Disabled" attribute was not found');
        $this->assertContains('toggler="toggler-name"', $Content, '"Toggler" attribute was not found');
        $this->assertContains('toggle-value="3"', $Content, '"Toggle-value" attribute was not found');
    }
}

?>