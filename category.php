<?php
if (isset($_GET['category'])) {
    $category = $_GET['category'];
    
    switch ($category) {
        case 'burgers':
            header('Location: burgers.php');
            break;
        case 'pastries':
            header('Location: pastries.php');
            break;
        case 'coffee':
            header('Location: coffee.php');
            break;
        case 'soda':
            header('Location: soda.php');
            break;
        case 'appliances':
            header('Location: appliances.php');
            break;
        default:
            // Redirect to a default page or show an error message
            header('Location: error.php');
            break;
    }
} else {
    // Redirect to a default page or show an error message
    header('Location: error.php');
}
?>