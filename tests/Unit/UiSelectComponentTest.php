<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;
use Tests\TestCase;

class UiSelectComponentTest extends TestCase
{
    public function test_select_component_renders_the_validation_message_without_leaking_css_classes(): void
    {
        $errors = new ViewErrorBag;
        $errors->put('default', new MessageBag([
            'department_id' => 'Departemen wajib dipilih.',
        ]));

        app('view')->share('errors', $errors);

        $html = Blade::render('<x-ui.select name="department_id" label="Departemen" />');

        $this->assertStringContainsString('Departemen wajib dipilih.', $html);
        $this->assertStringNotContainsString('border border-[#EE1D36] focus:border-[#EE1D36] focus:ring-[#EE1D36]</p>', $html);
    }

    public function test_select_component_can_render_without_a_name_for_filter_controls(): void
    {
        $html = Blade::render('<x-ui.select x-model="filterDepartment" label="Departemen"><option value="">Semua</option></x-ui.select>');

        $this->assertStringContainsString('x-model="filterDepartment"', $html);
        $this->assertStringNotContainsString('Undefined variable $name', $html);
    }
}
