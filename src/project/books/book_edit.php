<?php
require_once 'php/lib/config.php';
require_once 'php/lib/session.php';
require_once 'php/lib/forms.php';
require_once 'php/lib/utils.php';

startSession();

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        throw new Exception('Invalid request method.');
    }

    if (!isset($_GET['id'])) {
        throw new Exception('No book ID provided.');
    }

    $id = $_GET['id'];

    $book = Book::findById($id);
    if (!$book) {
        throw new Exception('Book not found.');
    }

    $publishers = Publisher::findAll();

} catch (Exception $e) {
    setFlashMessage('error', $e->getMessage());
    redirect('index.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'php/inc/head_content.php'; ?>
    <title>Edit Book</title>
</head>
<body>

<div class="container">

    <div class="width-12">
        <?php require 'php/inc/flash_message.php'; ?>
    </div>

    <div class="width-12">
        <h1>Edit Book</h1>
    </div>

    <div class="width-12">
        <form action="book_update.php" method="POST" enctype="multipart/form-data">

            <!-- Hidden ID -->
            <input type="hidden" name="id" value="<?= h($book->id) ?>">

            <!-- Title -->
            <div class="input">
                <label class="special">Title:</label>
                <input type="text" name="title" value="<?= old('title', $book->title) ?>" required>
                <p><?= error('title') ?></p>
            </div>

            <!-- Author -->
            <div class="input">
                <label class="special">Author:</label>
                <input type="text" name="author" value="<?= old('author', $book->author) ?>" required>
                <p><?= error('author') ?></p>
            </div>

            <!-- Publisher -->
            <div class="input">
                <label class="special">Publisher:</label>
                <select name="publisher_id" required>
                    <?php foreach ($publishers as $publisher) { ?>
                        <option value="<?= h($publisher->id) ?>"
                            <?= chosen('publisher_id', $publisher->id, $book->publisher_id) ? "selected" : "" ?>>
                            <?= h($publisher->name) ?>
                        </option>
                    <?php } ?>
                </select>
                <p><?= error('publisher_id') ?></p>
            </div>

            <!-- Year -->
            <div class="input">
                <label class="special">Year:</label>
                <input type="number" name="year" value="<?= old('year', $book->year) ?>" required>
                <p><?= error('year') ?></p>
            </div>

            <!-- ISBN -->
            <div class="input">
                <label class="special">ISBN:</label>
                <input type="text" name="isbn" value="<?= old('isbn', $book->isbn) ?>" required>
                <p><?= error('isbn') ?></p>
            </div>

            <!-- Description -->
            <div class="input">
                <label class="special">Description:</label>
                <textarea name="description" required><?= old('description', $book->description) ?></textarea>
                <p><?= error('description') ?></p>
            </div>

            <!-- Current Image -->
            <div class="input">
                <label class="special">Current Image:</label><br>
                <img src="images/<?= h($book->cover_filename) ?>" style="width:150px;">
            </div>

            <!-- New Image -->
            <div class="input">
                <label class="special">New Image (optional):</label>
                <input type="file" name="cover_filename" accept="image/*">
                <p><?= error('cover_filename') ?></p>
            </div>

            <!-- Buttons -->
            <div class="input">
                <button class="button" type="submit">Update Book</button>
                <a href="index.php" class="button">Cancel</a>
            </div>

        </form>
    </div>

</div>

</body>
</html>

<?php
clearFormData();
clearFormErrors();
?>