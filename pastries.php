<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Random</title>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- Popper JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Link to your CSS file -->
    <link rel="stylesheet" href="css/pastries"> <!-- Update the CSS file path -->

    <script>
    $(document).ready(function() {
        // Enable tooltips
        $('[data-toggle="tooltip"]').tooltip();

        // Handle click event on pastry images
        $('.pic_option div').click(function() {
            var pastryName = $(this).find('p').eq(0).text().split(': ')[1];
            var pastryPrice = parseFloat($(this).find('p').eq(1).text().split(': ')[1].replace('$', ''));
            var pastryQuantityInput = $(this).find('input[name="quantity"]');
            var pastryQuantity = parseInt(pastryQuantityInput.val());

            // Check if the pastry is already in the selected list
            var listItem = $('#selected_pastry_list').find('li[data-name="' + pastryName + '"]');
            if (listItem.length > 0) {
                // Pastry is already in the list, update quantity and price
                var listItemQuantity = parseInt(listItem.attr('data-quantity'));
                listItemQuantity += pastryQuantity;
                if (listItemQuantity > 0) {
                    listItem.attr('data-quantity', listItemQuantity);
                    listItem.find('.selected-quantity').text('Quantity: ' + listItemQuantity);
                    var listItemPrice = parseFloat(listItem.attr('data-price'));
                    listItemPrice += pastryPrice * pastryQuantity;
                    listItem.attr('data-price', listItemPrice);
                    listItem.find('.selected-price').text('Price: $' + listItemPrice.toFixed(2));
                } else {
                    // If quantity becomes 0, remove the item from the list
                    listItem.remove();
                }
            } else {
                // Pastry is not in the list, add it
                var listItem = $('<li class="selected-item" data-name="' + pastryName + '" data-quantity="' + pastryQuantity + '" data-price="' + pastryPrice * pastryQuantity + '">' +
                    '<p>Name: ' + pastryName + '</p>' +
                    '<p class="selected-price">Price: $' + (pastryPrice * pastryQuantity).toFixed(2) + '</p>' +
                    '<p class="selected-quantity">Quantity: ' + pastryQuantity + '</p>' +
                    '</li>');
                $('#selected_pastry_list').append(listItem);
            }
        });

        // Handle change event on pastry quantity input fields
        $('.pic_option input[name="quantity"]').on('change', function() {
            var pastryName = $(this).closest('div').find('p').eq(0).text().split(': ')[1];
            var pastryQuantity = parseInt($(this).val());

            // Find the corresponding item in the selected list and update it
            var listItem = $('#selected_pastry_list').find('li[data-name="' + pastryName + '"]');
            if (listItem.length > 0) {
                if (pastryQuantity > 0) {
                    // Update quantity and price
                    listItem.attr('data-quantity', pastryQuantity);
                    listItem.find('.selected-quantity').text('Quantity: ' + pastryQuantity);
                    var pastryPrice = parseFloat($(this).closest('div').find('p').eq(1).text().split(': ')[1].replace('$', ''));
                    var listItemPrice = pastryPrice * pastryQuantity;
                    listItem.attr('data-price', listItemPrice);
                    listItem.find('.selected-price').text('Price: $' + listItemPrice.toFixed(2));
                } else {
                    // If quantity becomes 0, remove the item from the list
                    listItem.remove();
                }
            }
        });

        // Calculate bill
        $('#calculate_bill').click(function() {
            var totalPrice = 0;
            var totalQuantity = 0;

            // Calculate total price and quantity
            $('.selected-item').each(function() {
                var priceText = $(this).find('.selected-price').text().split(': ')[1];
                var quantityText = $(this).find('.selected-quantity').text().split(': ')[1];

                var price = parseFloat(priceText.replace('$', ''));
                var quantity = parseInt(quantityText);

                totalPrice += price;
                totalQuantity += quantity;
            });

            // Update input fields
            $('#price').val('$' + totalPrice.toFixed(2));
            $('#quantity').val(totalQuantity);
            $('#amount_to_pay').val('$' + totalPrice.toFixed(2));

            var customerCash = parseFloat($('#customer_cash').val());
            var changeAmount = customerCash - totalPrice;

            // Update change amount field
            if (!isNaN(changeAmount) && changeAmount >= 0) {
                $('#change_amount').val('$' + changeAmount.toFixed(2));
            } else {
                $('#change_amount').val('');
            }
        });

        // Clear order details and selected pastry list
        $('#new_order').click(function() {
            $('#price').val('');
            $('#quantity').val('');
            $('#amount_to_pay').val('');
            $('#customer_cash').val('');
            $('#change_amount').val('');
            $('#selected_pastry_list').empty();
            $('.pic_option input[name="quantity"]').val('0');
        });
    });
    </script>
</head>
<body>
    <div class="container page_border"> 
        <h1 style="text-align:center; margin-top:10px; font-size:70px; font-family:Algerian; color:black">
            Random`s Point of Sale
        </h1>
        <h1>Welcome to Random</h1>
    
        <form action="category.php" method="GET" class="mt-3">
            <div class="form-group">
                <label for="category">Select a category:</label>
                <select name="category" id="category" class="form-control">
                    <option value="burgers">Burgers</option>
                    <option value="pastries">Pastries</option>
                    <option value="coffee">Coffee</option>
                    <option value="soda">Soda</option>
                    <option value="appliances">Appliances</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Go</button>
        </form>
        
        <div class="row mt-3">
            <div class="col-md-12">
                <div class="pic_option d-flex flex-wrap justify-content-around">
                    <!-- Add your pastry items here -->
                    <!-- Example pastry item -->
                    <div class="text-center mb-3"> 
                        <img src="pastry_images/choco-chip cookie.png" data-toggle="tooltip" data-placement="bottom" 
                        title="choco-chip cookie" width="200" height="210" alt="choco-chip cookie">
                        <p>Name: choco-chip cookie</p>
                        <p>Price: $5</p>
                        <input type="number" name="quantity" value="0" min="0">
                    </div>
                    <div class="text-center mb-3"> 
                        <img src="pastry_images/churros.png" data-toggle="tooltip" data-placement="bottom" 
                        title="churros" width="200" height="210" alt="churros">
                        <p>Name: churros</p>
                        <p>Price: $4</p>
                        <input type="number" name="quantity" value="0" min="0">
                    </div>
                    <div class="text-center mb-3"> 
                        <img src="pastry_images/creme breulle.png" data-toggle="tooltip" data-placement="bottom" 
                        title="creme breulle" width="200" height="210" alt="creme breulle">
                        <p>Name: creme breulle</p>
                        <p>Price: $5</p>
                        <input type="number" name="quantity" value="0" min="0">
                    </div>
                    <div class="text-center mb-3"> 
                        <img src="pastry_images/croissant.png" data-toggle="tooltip" data-placement="bottom" 
                        title="croissant" width="200" height="210" alt="croissant">
                        <p>Name: croissant</p>
                        <p>Price: $7</p>
                        <input type="number" name="quantity" value="0" min="0">
                    </div>
                    <div class="text-center mb-3"> 
                        <img src="pastry_images/croquembouche.png" data-toggle="tooltip" data-placement="bottom" 
                        title="croquembouche" width="200" height="210" alt="croquembouche">
                        <p>Name: croquembouche</p>
                        <p>Price: $4</p>
                        <input type="number" name="quantity" value="0" min="0">
                    </div>
                    <div class="text-center mb-3"> 
                        <img src="pastry_images/eclairs.png" data-toggle="tooltip" data-placement="bottom" 
                        title="eclairs" width="200" height="210" alt="eclairs">
                        <p>Name: eclairs</p>
                        <p>Price: $3</p>
                        <input type="number" name="quantity" value="0" min="0">
                    </div>
                    <div class="text-center mb-3"> 
                        <img src="pastry_images/kouign amann.png" data-toggle="tooltip" data-placement="bottom" 
                        title="kouign amann" width="200" height="210" alt="kouign amann">
                        <p>Name: kouign amann</p>
                        <p>Price: $6</p>
                        <input type="number" name="quantity" value="0" min="0">
                    </div>
                    <div class="text-center mb-3"> 
                        <img src="pastry_images/macaroons.png" data-toggle="tooltip" data-placement="bottom" 
                        title="macaroons" width="200" height="210" alt="macaroons">
                        <p>Name: macaroons</p>
                        <p>Price: $5</p>
                        <input type="number" name="quantity" value="0" min="0">
                    </div>
                    <div class="text-center mb-3"> 
                        <img src="pastry_images/madeleines.png" data-toggle="tooltip" data-placement="bottom" 
                        title="madeleines" width="200" height="210" alt="madeleines">
                        <p>Name: madeleines</p>
                        <p>Price: $8</p>
                        <input type="number" name="quantity" value="0" min="0">
                    </div>
                    <div class="text-center mb-3"> 
                        <img src="pastry_images/mille feuilles.png" data-toggle="tooltip" data-placement="bottom" 
                        title="mille feuilles" width="200" height="210" alt="mille feuilles">
                        <p>Name: mille feuilles</p>
                        <p>Price: $9</p>
                        <input type="number" name="quantity" value="0" min="0">
                    </div>
                    <div class="text-center mb-3"> 
                        <img src="pastry_images/pain aux raisins.png" data-toggle="tooltip" data-placement="bottom" 
                        title="pain aux raisins" width="200" height="210" alt="pain aux raisins">
                        <p>Name: pain aux raisins</p>
                        <p>Price: $7</p>
                        <input type="number" name="quantity" value="0" min="0">
                    </div>
                    <div class="text-center mb-3"> 
                        <img src="pastry_images/pain au chocolat.png" data-toggle="tooltip" data-placement="bottom" 
                        title="pain au chocolat" width="200" height="210" alt="pain au chocolat">
                        <p>Name: pain au chocolat</p>
                        <p>Price: $8</p>
                        <input type="number" name="quantity" value="0" min="0">
                    </div>
                    <div class="text-center mb-3"> 
                        <img src="pastry_images/palmier.png" data-toggle="tooltip" data-placement="bottom" 
                        title="palmier" width="200" height="210" alt="palmier">
                        <p>Name: palmier</p>
                        <p>Price: $4</p>
                        <input type="number" name="quantity" value="0" min="0">
                    </div>
                    <div class="text-center mb-3"> 
                        <img src="pastry_images/paris-brest.png" data-toggle="tooltip" data-placement="bottom" 
                        title="paris-brest" width="200" height="210" alt="paris-brest">
                        <p>Name: paris-brest</p>
                        <p>Price: $6</p>
                        <input type="number" name="quantity" value="0" min="0">
                    </div>
                    <div class="text-center mb-3"> 
                        <img src="pastry_images/profiteroles.png" data-toggle="tooltip" data-placement="bottom" 
                        title="profiteroles" width="200" height="210" alt="profiteroles">
                        <p>Name: profiteroles</p>
                        <p>Price: $7</p>
                        <input type="number" name="quantity" value="0" min="0">
                    </div>
                    <div class="text-center mb-3"> 
                        <img src="pastry_images/religieuse.png" data-toggle="tooltip" data-placement="bottom" 
                        title="religieuse" width="200" height="210" alt="religieuse">
                        <p>Name: religieuse</p>
                        <p>Price: $10</p>
                        <input type="number" name="quantity" value="0" min="0">
                    </div>
                    <div class="text-center mb-3"> 
                        <img src="pastry_images/quiche bacon and egg pie.png" data-toggle="tooltip" data-placement="bottom" 
                        title="quiche bacon and egg pie" width="200" height="210" alt="quiche bacon and egg pie">
                        <p>Name: quiche bacon and egg pie</p>
                        <p>Price: $12</p>
                        <input type="number" name="quantity" value="0" min="0">
                    </div>
                    <div class="text-center mb-3"> 
                        <img src="pastry_images/quiche egg tart.png" data-toggle="tooltip" data-placement="bottom" 
                        title="quiche egg tart" width="200" height="210" alt="quiche egg tart">
                        <p>Name: quiche egg tart</p>
                        <p>Price: $9</p>
                        <input type="number" name="quantity" value="0" min="0">
                    </div>
                    <div class="text-center mb-3"> 
                        <img src="pastry_images/strawberry crepe.png" data-toggle="tooltip" data-placement="bottom" 
                        title="strawberry crepe" width="200" height="210" alt="strawberry crepe">
                        <p>Name: strawberry crepe</p>
                        <p>Price: $15</p>
                        <input type="number" name="quantity" value="0" min="0">
                    </div>
                    <div class="text-center mb-3"> 
                        <img src="pastry_images/strawberry tart.png" data-toggle="tooltip" data-placement="bottom" 
                        title="strawberry tart" width="200" height="210" alt="strawberry tart">
                        <p>Name: strawberry tart</p>
                        <p>Price: $17</p>
                        <input type="number" name="quantity" value="0" min="0">
                    </div>


                </div>
            </div>
        </div>
        
        <div class="row mt-3">
            <div class="selected-pastry-tab">
                <h4>Selected Pastries:</h4>
                <ul id="selected_pastry_list"></ul>
            </div>
            <div class="order-details-tab">
                <h4>Order Details:</h4>
                <div>
                    <label for="price">Price:</label>
                    <input type="text" id="price" name="price" readonly>
                </div>
                <div>
                    <label for="quantity">Quantity:</label>
                    <input type="text" id="quantity" name="quantity" readonly>
                </div>
                <div>
                    <label for="amount_to_pay">Amount to Pay:</label>
                    <input type="text" id="amount_to_pay" name="amount_to_pay" readonly>
                </div>
                <div>
                    <label for="customer_cash">Customer Cash:</label>
                    <input type="text" id="customer_cash" name="customer_cash">
                </div>
                <div>
                    <label for="change_amount">Change Amount:</label>
                    <input type="text" id="change_amount" name="change_amount" readonly>
                </div>
                <button type="button" id="calculate_bill" class="btn btn-primary">Calculate Bill</button>
                <button type="button" id="new_order" class="btn btn-secondary">New Order</button>
            </div>
        </div>
    </div>
</body>
</html>
