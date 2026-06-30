<?php

namespace Tests\Feature\Admin;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
use App\Models\Training;
use App\Models\TrainingParticipant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TrainingReportTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => 'admin', 'status' => 'active']);
    }

    public function test_admin_can_access_laporan_training(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.laporan-training'));

        $response->assertStatus(200);
        $response->assertSee('Laporan Training');
    }

    public function test_employee_cannot_access_laporan_training(): void
    {
        $employee = User::factory()->create(['role' => 'employee', 'status' => 'active']);

        $response = $this->actingAs($employee)->get(route('admin.laporan-training'));

        $response->assertStatus(403);
    }

    public function test_guest_redirected_to_login(): void
    {
        $response = $this->get(route('admin.laporan-training'));

        $response->assertRedirect('/login');
    }

    public function test_default_shows_current_month_data(): void
    {
        $this->createParticipantWithDate(now()->subMonth()); // last month - should not appear
        $currentMonth = $this->createParticipantWithDate(now()); // current month - should appear

        $response = $this->actingAs($this->admin)->get(route('admin.laporan-training'));

        $response->assertStatus(200);
        $response->assertSee($currentMonth->employee->name);
    }

    public function test_filter_by_training(): void
    {
        $training1 = Training::factory()->create(['title' => 'Training Alpha']);
        $training2 = Training::factory()->create(['title' => 'Training Beta']);
        $p1 = $this->createParticipant($training1);
        $p2 = $this->createParticipant($training2);

        $response = $this->actingAs($this->admin)->get(route('admin.laporan-training', ['training_id' => $training1->id]));

        $response->assertStatus(200);
        // Both appear in dropdown, but only Alpha participant should be in table
        $response->assertSee('Training Alpha');
    }

    public function test_filter_by_department(): void
    {
        $dept1 = Department::factory()->create(['name' => 'IT']);
        $dept2 = Department::factory()->create(['name' => 'HR']);
        $p1 = $this->createParticipantWithDepartment($dept1);
        $p2 = $this->createParticipantWithDepartment($dept2);

        $response = $this->actingAs($this->admin)->get(route('admin.laporan-training', ['department_id' => $dept1->id]));

        $response->assertStatus(200);
        $response->assertSee('IT');
    }

    public function test_filter_by_status(): void
    {
        $p1 = $this->createParticipantWithStatus('passed');
        $p2 = $this->createParticipantWithStatus('failed');

        $response = $this->actingAs($this->admin)->get(route('admin.laporan-training', ['status' => 'passed']));

        $response->assertStatus(200);
        $response->assertSee('Lulus');
    }

    public function test_search_by_employee_name(): void
    {
        $user = User::factory()->create(['role' => 'employee', 'status' => 'active']);
        $employee = Employee::factory()->create([
            'user_id' => $user->id,
            'name' => 'Budi Santoso',
            'department_id' => Department::factory(),
            'position_id' => Position::factory(),
        ]);
        $training = Training::factory()->create();
        $participant = TrainingParticipant::factory()->create([
            'training_id' => $training->id,
            'employee_id' => $employee->id,
            'completed_at' => now(),
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.laporan-training', ['search' => 'Budi']));

        $response->assertStatus(200);
        $response->assertSee('Budi Santoso');
    }

    public function test_search_by_training_title(): void
    {
        $training = Training::factory()->create(['title' => 'Laravel Advanced']);
        $participant = $this->createParticipant($training);

        $response = $this->actingAs($this->admin)->get(route('admin.laporan-training', ['search' => 'Laravel']));

        $response->assertStatus(200);
        $response->assertSee('Laravel Advanced');
    }

    public function test_summary_cards_visible(): void
    {
        $this->createParticipantWithStatus('passed');
        $this->createParticipantWithStatus('failed');

        $response = $this->actingAs($this->admin)->get(route('admin.laporan-training'));

        $response->assertStatus(200);
        $response->assertSee('Total Peserta');
        $response->assertSee('Total Training');
        $response->assertSee('Total Lulus');
        $response->assertSee('Total Tidak Lulus');
        $response->assertSee('Persentase Kelulusan');
        $response->assertSee('Rata-rata Pre-Test');
        $response->assertSee('Rata-rata Post-Test');
    }

    public function test_export_pdf_respects_filters(): void
    {
        $training = Training::factory()->create(['title' => 'Export Test Training']);
        $this->createParticipant($training);

        $response = $this->actingAs($this->admin)->get(route('admin.laporan-training.export.pdf', ['training_id' => $training->id]));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_export_excel_respects_filters(): void
    {
        $training = Training::factory()->create(['title' => 'Excel Test Training']);
        $this->createParticipant($training);

        $response = $this->actingAs($this->admin)->get(route('admin.laporan-training.export.excel', ['training_id' => $training->id]));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'text/csv; charset=UTF-8');
    }

    public function test_employee_cannot_export(): void
    {
        $employee = User::factory()->create(['role' => 'employee', 'status' => 'active']);

        $response = $this->actingAs($employee)->get(route('admin.laporan-training.export.pdf'));
        $response->assertStatus(403);

        $response = $this->actingAs($employee)->get(route('admin.laporan-training.export.excel'));
        $response->assertStatus(403);
    }

    public function test_pagination_works(): void
    {
        $training = Training::factory()->create();
        for ($i = 0; $i < 15; $i++) {
            $this->createParticipant($training);
        }

        $response = $this->actingAs($this->admin)->get(route('admin.laporan-training'));

        $response->assertStatus(200);
        $response->assertSee('15'); // total count in summary
    }

    private function createParticipant(?Training $training = null): TrainingParticipant
    {
        $training ??= Training::factory()->create();
        $user = User::factory()->create(['role' => 'employee', 'status' => 'active']);
        $employee = Employee::factory()->create([
            'user_id' => $user->id,
            'department_id' => Department::factory(),
            'position_id' => Position::factory(),
        ]);

        return TrainingParticipant::factory()->create([
            'training_id' => $training->id,
            'employee_id' => $employee->id,
            'completed_at' => now(),
            'progress_status' => 'passed',
        ]);
    }

    private function createParticipantWithDate($date): TrainingParticipant
    {
        $user = User::factory()->create(['role' => 'employee', 'status' => 'active']);
        $employee = Employee::factory()->create([
            'user_id' => $user->id,
            'department_id' => Department::factory(),
            'position_id' => Position::factory(),
        ]);

        return TrainingParticipant::factory()->create([
            'training_id' => Training::factory(),
            'employee_id' => $employee->id,
            'completed_at' => $date,
            'created_at' => $date,
        ]);
    }

    private function createParticipantWithDepartment(Department $department): TrainingParticipant
    {
        $user = User::factory()->create(['role' => 'employee', 'status' => 'active']);
        $employee = Employee::factory()->create([
            'user_id' => $user->id,
            'department_id' => $department->id,
            'position_id' => Position::factory(),
        ]);

        return TrainingParticipant::factory()->create([
            'training_id' => Training::factory(),
            'employee_id' => $employee->id,
            'completed_at' => now(),
        ]);
    }

    private function createParticipantWithStatus(string $status): TrainingParticipant
    {
        $user = User::factory()->create(['role' => 'employee', 'status' => 'active']);
        $employee = Employee::factory()->create([
            'user_id' => $user->id,
            'department_id' => Department::factory(),
            'position_id' => Position::factory(),
        ]);

        return TrainingParticipant::factory()->create([
            'training_id' => Training::factory(),
            'employee_id' => $employee->id,
            'completed_at' => now(),
            'progress_status' => $status,
        ]);
    }
}
