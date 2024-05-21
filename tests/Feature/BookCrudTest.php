<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Book;
use App\Models\User;
use App\Models\Category;
use Database\Seeders\RolesSeeder;
use Illuminate\Http\UploadedFile;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Storage;
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
    Storage::fake('public');

    $file = UploadedFile::fake()->image('images/book1.jpg');

    Category::factory()->create();

    $response = $this->post('/store-book', [
        'image' => $file,
        'name' => 'Test Book',
        'description' => 'Test Description',
        'price' => 10.50,
        'author' => 'Test Author',
        'category_id' => 1,
    ], [
        '_token' => csrf_token(),
    ]);

    $response->assertStatus(302);

    $time=time();
    $this->assertDatabaseHas('books', [
        'image' => '/images/'.$time.'.jpg',
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
        $category = Category::factory()->create();
        $book = Book::factory()->create();

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
        $category = Category::factory()->create();
        $book = Book::factory()->create();

        $data = [
            'image'=>'images/book1.jpg',
            'name' => 'Updated Book Name',
            'description' => 'Updated Book Description',
            'price' => 20.00,
            'author' => 'Updated Author',
            'category_id' => $category->id,
        ];

        $response = $this->post(route('update-book', ['id' => $book->id]), $data);

        $response->assertStatus(302);

        $updatedBook = Book::find($book->id);

        $this->assertEquals($data['image'], $updatedBook->image);
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
        $book = Book::factory()->create();
        $response = $this->get(route('delete-book', ['id' => $book->id]));
        $response->assertStatus(302);
        // $this->assertDeleted($book);
        // $this->assertDatabaseMissing('books', ['id' => $book->id]);
        $response->assertRedirect('books')
            ->assertSessionHas('success', 'Deleted successfully..!');
    }
}
