<?php
require_once 'php/lib/config.php';
require_once 'php/lib/session.php';
require_once 'php/lib/forms.php';
require_once 'php/lib/utils.php';

startSession();

try {
    $data = [];
    $errors = [];

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method.');
    }

    // Get form data
    $data = [
        'id' => $_POST['id'] ?? null,
        'title' => $_POST['title'] ?? null,
        'author' => $_POST['author'] ?? null,
        'publisher_id' => $_POST['publisher_id'] ?? null,
        'year' => $_POST['year'] ?? null,
        'isbn' => $_POST['isbn'] ?? null,
        'description' => $_POST['description'] ?? null,
        'cover_filename' => $_FILES['cover_filename'] ?? null
    ];

    // Clean ISBN
    $data['isbn'] = trim($data['isbn']);
    $data['isbn'] = str_replace('-', '', $data['isbn']);

    // Validation rules
    $rules = [
        'id' => 'required|integer',
        'title' => 'required|notempty|min:1|max:255',
        'author' => 'required|notempty',
        'publisher_id' => 'required|integer',
        'year' => 'required|notempty',
        'isbn' => 'required|min:13|max:13',
        'description' => 'required|min:1|max:1000',
        'cover_filename' => 'file|mimes:jpg,jpeg,png|max_file_size:5242880' // optional
    ];

    $validator = new Validator($data, $rules);

    if ($validator->fails()) {
        foreach ($validator->errors() as $field => $fieldErrors) {
            $errors[$field] = $fieldErrors[0];
        }

        throw new Exception('Validation failed.');
    }

    // Find existing book
    $book = Book::findById($data['id']);
    if (!$book) {
        throw new Exception('Book not found.');
    }

    // Check publisher exists
    if (!Publisher::findById($data['publisher_id'])) {
        throw new Exception('Invalid publisher.');
    }

    // Handle image (optional)
    $imageFilename = null;
    $uploader = new ImageUpload();

    if ($uploader->hasFile('cover_filename')) {
        // Delete old image
        $uploader->deleteImage($book->cover_filename);

        // Upload new image
        $imageFilename = $uploader->process($_FILES['cover_filename']);

        if (!$imageFilename) {
            throw new Exception('Failed to upload image.');
        }
    }

    // Update book
    $book->title = $data['title'];
    $book->author = $data['author'];
    $book->publisher_id = $data['publisher_id'];
    $book->year = $data['year'];
    $book->isbn = $data['isbn'];
    $book->description = $data['description'];

    if ($imageFilename) {
        $book->cover_filename = $imageFilename;
    }

    $book->save();

    clearFormData();
    clearFormErrors();

    setFlashMessage('success', 'Book updated successfully.');
    redirect('book_view.php?id=' . $book->id);

} catch (Exception $e) {

    if (isset($imageFilename) && $imageFilename) {
        $uploader->deleteImage($imageFilename);
    }

    setFlashMessage('error', 'Error: ' . $e->getMessage());
    setFormData($data);
    setFormErrors($errors);

    if (!empty($data['id'])) {
        redirect('book_edit.php?id=' . $data['id']);
    } else {
        redirect('index.php');
    }
}