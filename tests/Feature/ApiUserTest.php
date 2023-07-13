<?php

use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;

beforeEach(function () {
    $this->user = User::factory()->make();
});

// Testing the protected routes
it('protected user routes', function ($name, $url) {
    $response = null;

    switch ($name) {
        case 'GET':
            $response = $this->getJson($url);
            break;
        case 'POST':
            $response = $this->postJson($url);
            break;
        case 'PUT':
            $response = $this->putJson($url);
            break;
        case 'DELETE':
            $response = $this->deleteJson($url);
            break;
    }

    $response->assertStatus(401);
})->with([
    ['GET', '/api/users'],
    ['GET', '/api/users/1'],
    ['POST', '/api/users'],
    ['PUT', '/api/users/1'],
    ['DELETE', '/api/users/1'],
]);

// Testing the response of the routes
it('POST store', function () {
    $faker = Faker\Factory::create();

    $response = $this->actingAs($this->user)
        ->postJson('/api/users', [
            'name' => 'Test User',
            'email' => $faker->safeEmail(),
            'password' => '12345678',
        ]);

    $response->assertStatus(201);
});

it('GET index', function () {
    $response = $this->actingAs($this->user)
        ->getJson('/api/users');

    $response
        ->assertStatus(200)
        ->assertJson(function (AssertableJson $json) {
            return $json->hasAll(['data', 'meta', 'links']);
        });
});

it('GET show', function () {
    $response = $this->actingAs($this->user)
        ->getJson('/api/users/1');

    $response
        ->assertStatus(200)
        ->assertJson(function (AssertableJson $json) {
            return $json->hasAll(['id', 'name'])
                ->missing('password')
                ->etc();
        });
});

it('PUT update', function () {
    $faker = Faker\Factory::create();

    $response = $this->actingAs($this->user)
        ->putJson('/api/users/1', [
            'name' => 'Test User',
            'email' => $faker->safeEmail(),
            'password' => '12345678',
        ]);

    $response->assertStatus(200);
});

it('DELETE destroy', function () {
    $response = $this->actingAs($this->user)
        ->deleteJson('/api/users/1');

    $response->assertStatus(204);
});
