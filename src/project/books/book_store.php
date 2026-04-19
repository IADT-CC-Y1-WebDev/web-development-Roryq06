<?php
require_once 'php/lib/config.php';
require_once 'php/lib/session.php';
require_once 'php/lib/forms.php';
require_once 'php/lib/utils.php';
 
startSession();
 
try {
    // Initialize form data array
    $data = [];
    // Initialize errors array
    $errors = [];
 
    // Check if request is POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method.');
    }
 
    // Get form data
    $data = [
        'title' => $_POST['title'] ?? null,
        'author' => $_POST['author'] ?? null,
        'publisher_id' => $_POST['publisher_id'] ?? null,
        'year' => $_POST['year'] ?? null,
        'isbn' => $_POST['isbn'] ?? null,
        'description' => $_POST['description'] ?? null,
        'cover_filename' => $_FILES['cover_filename'] ?? null,
        // 'format_id' => $_POST['format_id'] ?? []
    ];
 
    // Define validation rules
    $rules = [
        'title' => 'required|notempty|min:1|max:255',
        'author' => 'required|notempty',
        'publisher_id' => 'required|integer',
        'year' => 'required|notempty',
        'isbn' => 'required|notempty|min:1|max:13',
        'description' => 'required|min:1|max:1000',
        'cover_filename' => 'required|file|cover|mimes:jpg,jpeg,png|max_file_size:5242880',
        // 'format_id' => 'required|array|min:1|max:10'
    ];
 
    // Validate all data (including file)
    $validator = new Validator($data, $rules);
 
    if ($validator->fails()) {
        // Get first error for each field
        foreach ($validator->errors() as $field => $fieldErrors) {
            $errors[$field] = $fieldErrors[0];
        }
 
        throw new Exception('Validation failed.');
    }
 
    // All validation passed - now process and save
    // Verify publisher exists
    $publisher = Publisher::findById($data['publisher_id']);
    if (!$publisher) {
        throw new Exception('Selected publisher does not exist.');
    }
 
    // Process the uploaded cover_filename (validation already completed)
    $uploader = new ImageUpload();
    $coverFilename = $uploader->process($_FILES['cover_filename']);
 
    if (!$coverFilename) {
        throw new Exception('Failed to process and save the cover_filename.');
    }
 
    // Create new book instance
    $book = new Book();
    $book->title = $data['title'];
    $book->author = $data['author'];
    $book->publisher_id = $data['publisher_id'];
    $book->year = $data['year'];
    $book->isbn = $data['isbn'];
    $book->description = $data['description'];
    // $book->format_id = $data['format_id'];
    $book->cover_filename = $coverFilename;
 
    // Save to database
    $book->save();
    // Create format associations
    if (!empty($data['format_id'])) {
        foreach ($data['format_id'] as $formatId) {
            // Verify format exists before creating relationship
            if (Format::findById($formatId)) {
                BookFormat::create($book->id, $formatId);
            }
        }
    }
 
    // Clear any old form data
    clearFormData();
    // Clear any old errors
    clearFormErrors();
 
    // Set success flash message
    setFlashMessage('success', 'Book stored successfully.');
 
    // Redirect to book details page
    redirect('book_view.php?id=' . $book->id);
}
catch (Exception $e) {
    // Error - clean up uploaded cover_filename
    if (isset($coverFilename) && $coverFilename) {
        $uploader->deleteImage($coverFilename);
    }
 
    // Set error flash message
    setFlashMessage('error', 'Error: ' . $e->getMessage());
 
    // Store form data and errors in session
    setFormData($data);
    setFormErrors($errors);
 
    redirect('book_create.php');
}
 