<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Random - Home</title>
</head>
<body>
    <h1>Welcome to Random</h1>
    
    <form action="category.php" method="GET">
        <label for="category">Select a category:</label>
        <select name="category" id="category">
            <option value="burgers">Burgers</option>
            <option value="pastries">Pastries</option>
            <option value="coffee">Coffee</option>
            <option value="soda">Soda</option>
            <option value="appliances">Appliances</option>
        </select>
        <button type="submit">Go</button>
    </form>
</body>
</html>