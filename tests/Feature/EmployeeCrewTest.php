<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

// global variables
$token = null;
$crew_id = null;
$employee_id = null;
$id = null;

class EmployeeCrewTest extends TestCase
{

    public function test_login()
    {
        $response = $this->post(env('APP_URL') . '/api/auth/login', [
            'email' => 'superadmin@gmail.com',
            'password' => 'superadmin'
        ],['Accept' => 'application/json']);
        global $token;
        $token = $response->getOriginalContent()['token'];
        $response->assertStatus(200);
    }

    /**
     * EmployeeCrew: Get All
     * @depends test_login
     *
     * @return void
     */
    public function test_index()
    {
        global $token;
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])
        ->get(env('APP_URL') . '/api/crew-employees', ['search' => '', 'size' => '5']);
        $response->assertStatus(200);
    }

    /**
     * EmployeeCrew: Create
     * @depends test_login
     *
     * @return void
     */
    public function test_create()
    {
        global $token;
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])
        ->get(env('APP_URL') . '/api/crew-employees/create');
        $data = $response->getOriginalContent();
        global $crew_id, $employee_id;
        $crew_id = $data['crews'][0]->id;
        $employee_id = $data['employees'][0]->id;
        $response->assertStatus(200);
    }

    /**
     * EmployeeCrew: Store
     * @depends test_login
     *
     * @return void
     */
    public function test_store()
    {
        global $token, $crew_id, $employee_id;
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])
        ->post(env('APP_URL') . '/api/crew-employees', [
            'crew_id' => $crew_id,
            'employee_id' => $employee_id,
            'percentage' => 10,
            'is_lead' => 1
        ]);
        $data = $response->getOriginalContent();
        global $id;
        $id = $data['id'];
        $response->assertStatus(200);
    }

    /**
     * EmployeeCrew: Edit
     * @depends test_login
     *
     * @return void
     */
    public function test_edit()
    {
        global $token, $id;
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])
        ->get(env('APP_URL') . '/api/crew-employees/' . $id . '/edit');
        $response->assertStatus(200);
    }

    /**
     * EmployeeCrew: Update
     * @depends test_login
     *
     * @return void
     */
    public function test_update()
    {
        global $token, $id, $crew_id, $employee_id;
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])
        ->put(env('APP_URL') . '/api/crew-employees/' . $id, [
            'crew_id' => $crew_id,
            'employee_id' => $employee_id,
            'percentage' => 20,
            'is_lead' => 0
        ]);
        $response->assertStatus(200);
    }

    /**
     * EmployeeCrew: Delete
     * @depends test_login
     *
     * @return void
     */
    public function test_delete()
    {
        global $token, $id;
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])
        ->delete(env('APP_URL') . '/api/crew-employees/' . $id);
        $response->assertStatus(200);
    }

}
