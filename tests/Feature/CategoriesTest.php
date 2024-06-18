<?php
use App\Models\Category;

beforeEach(function () {
    $this->user = setupAdminUser();
});

it('can fetch categories', function () {
    $categories = Category::factory()->count(3)->create();
    $this->actingAs($this->user)->get(route('categories'))
    ->assertViewIs('categories')
    ->assertViewHas('categories', $categories);
});

it('cannot fetch categories for an unauthenticated user', function () {
    $categories = Category::factory()->count(3)->create();
    $this->get(route('categories'))
    ->assertRedirect(route('login'));
});

it('displays the add category view', function () {
    $this->actingAs($this->user)->get(route('add-category'))
    ->assertViewIs('add-category');
});

it('successfully creates a category', function () {
    $this->actingAs($this->user)->post(route('store-category'), [
        'name' => 'Education',
    ])
    ->assertRedirect('categories')
    ->assertSessionHas('success', 'Category created successfully..!');
    expect(Category::where('name', 'Education')->exists())->toBeTrue();
});

it('cannot store a category with an existing name', function () {
    Category::create(['name' => 'Education']);
    $this->actingAs($this->user)->post(route('store-category'), [
        'name' => 'Education'
    ])
    ->assertSessionHasErrors('name');
    expect(Category::where('name', 'Education')->count())->toBe(1);
});

it('displays the edit category view for a valid category ID', function () {
    $category = Category::factory()->create();
    $this->actingAs($this->user)->get(route('edit-category', ['id' => $category->id]))
    ->assertViewIs('edit-category')
    ->assertViewHas('category', $category);
});

it('can update a category', function () {
    $category = Category::factory()->create(['name' => 'Education']);
    $this->actingAs($this->user)->post(route('update-category', $category->id), [
        'name' => 'Devotional'
    ])
    ->assertRedirect(route('categories'))
    ->assertSessionHas('success', 'Updated successfully..!');
    expect(Category::find($category->id)->name)->toBe('Devotional');
});

it('returns error when updating a non-existent category', function () {
    $this->actingAs($this->user)->post(route('update-category', 9999), [
        'name' => 'Sample'
    ])
    ->assertSessionHas('error', 'Category not found..!');
});

it('can delete a category', function () {
    $category = Category::factory()->create(['name' => 'Sample']);
    $this->actingAs($this->user)->get(route('delete-category', $category->id))
    ->assertRedirect(route('categories'))
    ->assertSessionHas('success', 'Deleted successfully..!');
});

it('returns error when deleting a non-existent category', function () {
    $this->actingAs($this->user)->get(route('delete-category', 9999))
    ->assertSessionHas('error', 'Category not found..!');
});