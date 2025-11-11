<?php

use App\Models\File;
use App\Models\Person;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

test('file index component renders correctly', function () {
    $user = Person::factory()->create();
    $this->actingAs($user);

    Livewire::test(\App\Livewire\Files\Index::class)
        ->assertSee('Files')
        ->assertSee('Add File')
        ->assertSee('Search files');
});

test('file create component renders form', function () {
    $user = Person::factory()->create();
    $this->actingAs($user);

    Livewire::test(\App\Livewire\Files\Create::class)
        ->assertSee('Upload File')
        ->assertSee('Person')
        ->assertSee('Category')
        ->assertSee('File')
        ->assertSee('Description');
});

test('file upload validation works', function () {
    $user = Person::factory()->create();
    $this->actingAs($user);

    Livewire::test(\App\Livewire\Files\Create::class)
        ->set('person_id', '')
        ->set('file_category', '')
        ->call('save')
        ->assertHasErrors(['person_id', 'file_category', 'file']);
});

test('file upload works with valid data', function () {
    Storage::fake('public');

    $user = Person::factory()->create();
    $person = Person::factory()->create();
    $file = UploadedFile::fake()->create('document.pdf', 1000);

    $this->actingAs($user);

    Livewire::test(\App\Livewire\Files\Create::class)
        ->set('person_id', $person->id)
        ->set('file_category', 'cv')
        ->set('description', 'Test CV document')
        ->set('uploaded_at', now()->format('Y-m-d\TH:i'))
        ->set('file', $file)
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect(route('files.index'));

    $this->assertDatabaseHas('files', [
        'person_id' => $person->id,
        'filename' => 'document.pdf',
        'file_category' => 'cv',
        'description' => 'Test CV document',
    ]);

    // Check that file was stored (we can't predict the exact hash, so just check the directory exists)
    $files = Storage::disk('public')->allFiles('files');
    expect($files)->toHaveCount(1);
});

test('file upload rejects large files', function () {
    $user = Person::factory()->create();
    $person = Person::factory()->create();
    $file = UploadedFile::fake()->create('large.pdf', 15000); // 15MB, exceeds 10MB limit

    $this->actingAs($user);

    Livewire::test(\App\Livewire\Files\Create::class)
        ->set('person_id', $person->id)
        ->set('file_category', 'cv')
        ->set('file', $file)
        ->call('save')
        ->assertHasErrors(['file']);
});

test('file show component displays file details', function () {
    $user = Person::factory()->create();
    $person = Person::factory()->create();
    $file = File::factory()->create(['person_id' => $person->id]);

    $this->actingAs($user);

    Livewire::test(\App\Livewire\Files\Show::class, ['file' => $file])
        ->assertSee('File Details')
        ->assertSee($file->filename)
        ->assertSee($file->description)
        ->assertSee($person->name)
        ->assertSee(ucfirst($file->file_category));
});

test('file edit component renders edit form', function () {
    $user = Person::factory()->create();
    $person = Person::factory()->create();
    $file = File::factory()->create(['person_id' => $person->id]);

    $this->actingAs($user);

    Livewire::test(\App\Livewire\Files\Edit::class, ['file' => $file])
        ->assertSee('Edit File')
        ->assertSet('person_id', $person->id)
        ->assertSet('file_category', $file->file_category)
        ->assertSet('description', $file->description);
});

test('file update works with valid data', function () {
    $user = Person::factory()->create();
    $person = Person::factory()->create();
    $file = File::factory()->create(['person_id' => $person->id]);

    $this->actingAs($user);

    Livewire::test(\App\Livewire\Files\Edit::class, ['file' => $file])
        ->set('file_category', 'contract')
        ->set('description', 'Updated description')
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect(route('files.show', $file));

    $this->assertDatabaseHas('files', [
        'id' => $file->id,
        'file_category' => 'contract',
        'description' => 'Updated description',
    ]);
});

test('file deletion works', function () {
    Storage::fake('public');

    $user = Person::factory()->create();
    $person = Person::factory()->create();
    $file = File::factory()->create(['person_id' => $person->id]);

    // Create the actual file
    Storage::disk('public')->put($file->file_path, 'dummy content');

    $this->actingAs($user);

    Livewire::test(\App\Livewire\Files\Show::class, ['file' => $file])
        ->call('delete')
        ->assertRedirect(route('files.index'));

    $this->assertDatabaseMissing('files', ['id' => $file->id]);
    Storage::disk('public')->assertMissing($file->file_path);
});

test('file filtering works correctly', function () {
    $user = Person::factory()->create();
    $person1 = Person::factory()->create(['first_name' => 'John', 'last_name' => 'Doe']);
    $person2 = Person::factory()->create(['first_name' => 'Jane', 'last_name' => 'Smith']);

    File::factory()->create([
        'person_id' => $person1->id,
        'file_category' => 'cv',
        'filename' => 'john_cv.pdf',
    ]);

    File::factory()->create([
        'person_id' => $person2->id,
        'file_category' => 'contract',
        'filename' => 'jane_contract.pdf',
    ]);

    $this->actingAs($user);

    // Test category filter
    $component = Livewire::test(\App\Livewire\Files\Index::class)
        ->set('fileCategory', 'cv')
        ->assertSee('john_cv.pdf')
        ->assertDontSee('jane_contract.pdf');

    // Test person filter
    $component = Livewire::test(\App\Livewire\Files\Index::class)
        ->set('personFilter', $person1->id)
        ->assertSee('john_cv.pdf')
        ->assertDontSee('jane_contract.pdf');

    // Test search
    $component = Livewire::test(\App\Livewire\Files\Index::class)
        ->set('search', 'John')
        ->assertSee('john_cv.pdf')
        ->assertDontSee('jane_contract.pdf');
});

test('file download works', function () {
    Storage::fake('public');

    $user = Person::factory()->create();
    $person = Person::factory()->create();
    $file = File::factory()->create([
        'person_id' => $person->id,
    ]);

    // Create the actual file using the factory's generated path
    Storage::disk('public')->put($file->file_path, 'dummy content');

    $this->actingAs($user);

    // Test by directly calling the controller method with file ID
    $controller = new \App\Http\Controllers\FileController;

    // Mock the authorization to pass - accept any File object
    Gate::shouldReceive('authorize')->with('download', \Mockery::type(\App\Models\File::class))->andReturn(true);

    $response = $controller->download($file->id);

    // StreamedResponse doesn't have assertOk(), just check it's a proper response
    expect($response)->toBeInstanceOf(\Symfony\Component\HttpFoundation\StreamedResponse::class);

    // Check the headers by getting them from the response
    expect($response->headers->get('content-disposition'))->toBe('attachment; filename="'.$file->filename.'"');
});

test('file download works via HTTP request', function () {
    Storage::fake('public');

    $user = Person::factory()->create();
    $person = Person::factory()->create();
    $file = File::factory()->create([
        'person_id' => $person->id,
    ]);

    // Create the actual file using the factory's generated path
    Storage::disk('public')->put($file->file_path, 'dummy content');

    $response = $this->actingAs($user)->get(route('files.download', $file));

    $response->assertOk();
    $response->assertHeader('content-disposition', 'attachment; filename="'.$file->filename.'"');
});

test('file model relationships work correctly', function () {
    $person = Person::factory()->create();

    $cv = File::factory()->cv()->create(['person_id' => $person->id]);
    $contract = File::factory()->contract()->create(['person_id' => $person->id]);
    $certificate = File::factory()->certificate()->create(['person_id' => $person->id]);
    $other = File::factory()->other()->create(['person_id' => $person->id]);

    // Test basic relationship
    expect($person->files)->toHaveCount(4);
    expect($person->files->pluck('id'))->toContain($cv->id, $contract->id, $certificate->id, $other->id);

    // Test category-specific relationships
    expect($person->cvs)->toHaveCount(1);
    expect($person->contracts)->toHaveCount(1);
    expect($person->certificates)->toHaveCount(1);
    expect($person->otherFiles)->toHaveCount(1);

    expect($person->cvs->first()->id)->toBe($cv->id);
    expect($person->contracts->first()->id)->toBe($contract->id);
    expect($person->certificates->first()->id)->toBe($certificate->id);
    expect($person->otherFiles->first()->id)->toBe($other->id);
});

test('file model attributes work correctly', function () {
    $file = File::factory()->create([
        'file_size' => 1024 * 1024, // 1MB
        'file_type' => 'application/pdf',
    ]);

    // Test formatted size
    expect($file->file_size_formatted)->toBe('1 MB');

    // Test file icon
    expect($file->file_icon)->toBe('📄');

    // Test file type checks
    expect($file->isImage())->toBeFalse();
    expect($file->isPdf())->toBeTrue();

    // Test image file
    $imageFile = File::factory()->image()->create(['file_type' => 'image/jpeg']);
    expect($imageFile->isImage())->toBeTrue();
    expect($imageFile->isPdf())->toBeFalse();
});
