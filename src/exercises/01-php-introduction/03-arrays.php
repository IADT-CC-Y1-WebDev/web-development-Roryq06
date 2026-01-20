<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arrays Exercises - PHP Introduction</title>
    <link rel="stylesheet" href="/exercises/css/style.css">
</head>
<body>
    <div class="back-link">
        <a href="index.php">&larr; Back to PHP Introduction</a>
        <a href="/examples/01-php-introduction/03-arrays.php">View Example &rarr;</a>
    </div>

    <h1>Arrays Exercises</h1>

    <!-- Exercise 1 -->
    <h2>Exercise 1: Favorite Movies</h2>
    <p>
        <strong>Task:</strong> 
        Create an indexed array with 5 of your favorite movies. Use a for 
        loop to display each movie with its position (e.g., "Movie 1: 
        The Matrix").
    </p>

    <p class="output-label">Output:</p>
    <div class="output">
        <?php
        // TODO: Write your solution here

        $movies = ["The Matrix", "Inception", "Avatar", "Titanic", "21"];

        for($i = 0; $i < count($movies); $i++) {
            echo "Movie", $i+1, ": ", $movies[$i], "<br>";
        }

        ?>
    </div>

    <!-- Exercise 2 -->
    <h2>Exercise 2: Student Record</h2>
    <p>
        <strong>Task:</strong> 
        Create an associative array for a student with keys: name, studentId, 
        course, and grade. Display this information in a formatted sentence.
    </p>

    <p class="output-label">Output:</p>
    <div class="output">
        <?php
        // TODO: Write your solution here

        $student = [
            "name" => "Rory",
            "studentId" => "n00255243",
            "course" => "Creative Computing",
            "grade" => "A+"];

        $text = "{$student['name']}  is the Students Name. <br> His ID Number is {$student['studentId']}. <br> He studies {$student['course']}. <br>and got an {$student['grade']} grade.";

        print("<p>$text</p>")

        ?>
    </div>

    <!-- Exercise 3 -->
    <h2>Exercise 3: Country Capitals</h2>
    <p>
        <strong>Task:</strong> 
        Create an associative array with at least 5 countries as keys and their 
        capitals as values. Use foreach to display each country and capital 
        in the format "The capital of [country] is [capital]."
    </p>

    <p class="output-label">Output:</p>
    <div class="output">
        <?php
        // TODO: Write your solution here

        $fcountries = [
            "France" => "Paris",
            "Spain" => "Madrid",
            "England" => "London",
            "Scotland" => "Glasgow",
            "Ireland" => "Dublin"
        ];

        foreach($fcountries as $countries => $capitals) {
            echo "$capitals is the capital of $countries <br>";
        }

        ?>
    </div>

    <!-- Exercise 4 -->
    <h2>Exercise 4: Menu Categories</h2>
    <p>
        <strong>Task:</strong> 
        Create a nested array representing a restaurant menu with at least 
        2 categories (e.g., "Starters", "Main Course"). Each category should 
        have at least 3 items with prices. Display the menu in an organized 
        format.
    </p>

    <p class="output-label">Output:</p>
    <div class="output">
        <?php
        // TODO: Write your solution here

        $menu = [
            'starters' => [
                "Chicken Wing" => 8,
                "Soup" => 5,
                "Mozzarella Sticks" => 7
            ],
            'Main Course' => [
                "Pizza" => 10,
                "Burger" => 12,
                "Steak" => 20
            ]
            ];


            foreach($menu as $dishes => $items) {
                echo "<b><p>" . ucfirst($dishes) . "</p></b>";
                foreach ($items as $key => $value) {
                    echo "$key: €$value <br>";
                }
            }

        ?>
    </div>

</body>
</html>
