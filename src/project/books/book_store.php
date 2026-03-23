<?php
require_once 'php/lib/config.php';
require_once 'php/lib/session.php';
require_once 'php/lib/forms.php';
require_once 'php/lib/utils.php';

startSession();

try {
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
        'cover_filename' => $_FILES['cover_filename'] ?? null
    ];

    // Clean ISBN
    $data['isbn'] = trim($data['isbn']);
    $data['isbn'] = str_replace('-', '', $data['isbn']);

    // Validation rules
    $rules = [
        'title' => 'required|notempty|min:1|max:255',
        'author' => 'required|notempty',
        'publisher_id' => 'required|integer',
        'year' => 'required|notempty',
        'isbn' => 'required|min:13|max:13',
        'description' => 'required|min:1|max:1000',
        'cover_filename' => 'required|file|mimes:jpg,jpeg,png|max_file_size:5242880'
    ];

    $validator = new Validator($data, $rules);

    if ($validator->fails()) {
        $errors = [];
        foreach ($validator->errors() as $field => $fieldErrors) {
            $errors[$field] = $fieldErrors[0];
        }

        setFormData($data);
        setFormErrors($errors);
        redirect('book_create.php');
    }

    // Check publisher exists
    if (!Publisher::findById($data['publisher_id'])) {
        throw new Exception('Invalid publisher.');
    }

    // Upload image
    $uploader = new ImageUpload();
    if (empty($_FILES['cover_filename']['tmp_name'])) {
        throw new Exception('Please upload an image.');
    }
    $coverFilename = $uploader->process($_FILES['cover_filename']);

    if (!$coverFilename) {
        throw new Exception('Image upload failed.');
    }

    // Create book
    $book = new Book();
    $book->title = $data['title'];
    $book->author = $data['author'];
    $book->publisher_id = $data['publisher_id'];
    $book->year = $data['year'];
    $book->isbn = $data['isbn'];
    $book->description = $data['description'];
    $book->cover_filename = $coverFilename;

    $book->save();

    clearFormData();
    clearFormErrors();

    setFlashMessage('success', 'Book created successfully.');
    redirect('book_view.php?id=' . $book->id);
} catch (Exception $e) {
    setFlashMessage('error', $e->getMessage());
    setFormData($data ?? []);
    redirect('book_create.php');
}
