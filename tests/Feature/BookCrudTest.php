<?php

namespace Tests\Feature;

use App\Models\Categories;
use Tests\TestCase;
use App\Models\User;
use App\Models\Books;
use Database\Seeders\RolesSeeder;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookCrudTest extends TestCase
{
    use RefreshDatabase;
    protected $user;


    public function setUp(): void
    {
        parent::setUp();
        $this->setupPermissions();

        $this->user = User::create([
            "name" => "sample",
            "email" => "sample@gmail.com",
            "password" => bcrypt("Password@123"),
        ]);
        $this->user->assignRole('admin');

        $this->actingAs($this->user);
    }

    protected function setUpPermissions()
    {
        $this->artisan('db:seed', ['--class' => RolesSeeder::class]);
    }

    public function test_can_create_book()
    {
        Categories::factory()->create();

        $response = $this->post('/store-book', [
            'name' => 'Test Book',
            'description' => 'Test Description',
            'price' => 10.50,
            'author' => 'Test Author',
            'category_id' => 1
        ], [
            '_token' => csrf_token(),
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('books', [
            'name' => 'Test Book',
            'description' => 'Test Description',
            'price' => 10.50,
            'author' => 'Test Author',
            'category_id' => 1,
        ]);

        $response->assertRedirect('/books')
            ->assertSessionHas('success', 'Book added successfully..!');
    }

    public function test_can_edit_book()
    {
        $category = Categories::factory()->create();
        $book = Books::factory()->create();

        $response = $this->get(route('edit-book', ['id' => $book->id]));

        $response->assertStatus(200);
        $response->assertViewIs('add-book');

        $response->assertViewHas('book', function ($book) use ($category) {
            return $book->id === $category->id;
        });

        $response->assertViewHas('categories');
    }

    public function test_can_update_book()
    {
        $category = Categories::factory()->create();
        $book = Books::factory()->create();

        $data = [
            'name' => 'Updated Book Name',
            'description' => 'Updated Book Description',
            'price' => 20.00,
            'author' => 'Updated Author',
            'category_id' => $category->id,
        ];

        $response = $this->post(route('update-book', ['id' => $book->id]), $data);

        $response->assertStatus(302);

        $updatedBook = Books::find($book->id);

        $this->assertEquals($data['name'], $updatedBook->name);
        $this->assertEquals($data['description'], $updatedBook->description);
        $this->assertEquals($data['price'], $updatedBook->price);
        $this->assertEquals($data['author'], $updatedBook->author);
        $this->assertEquals($data['category_id'], $updatedBook->category_id);

        $response->assertRedirect('books')
            ->assertSessionHas('success', 'Book updated successfully..!');
    }

    public function test_can_delete_book()
    {
        $book = Books::factory()->create();
        $response = $this->get(route('delete-book', ['id' => $book->id]));
        $response->assertStatus(302);
        // $this->assertDeleted($book);
        // $this->assertDatabaseMissing('books', ['id' => $book->id]);
        $response->assertRedirect('books')
            ->assertSessionHas('success', 'Deleted successfully..!');
    }
}
