<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Random - Coffee</title>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- Popper JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Link to your CSS file -->
    <link rel="stylesheet" href="css/coffee"> <!-- Update the CSS file path -->

    <script>
    $(document).ready(function() {
        // Enable tooltips
        $('[data-toggle="tooltip"]').tooltip();

        // Handle click event on peripheral images (similar to appliances)
        $('.pic_option div').click(function() {
            var coffeeName = $(this).find('p').eq(0).text().split(': ')[1];
            var coffeePrice = parseFloat($(this).find('p').eq(1).text().split(': ')[1].replace('$', ''));
            var coffeeQuantityInput = $(this).find('input[name="quantity"]');
            var coffeeQuantity = parseInt(coffeeQuantityInput.val());

            // Check if the peripheral is already in the selected list
            var listItem = $('#selected_coffee_list').find('li[data-name="' + coffeeName + '"]');
            if (listItem.length > 0) {
                // Peripheral is already in the list, update quantity and price
                var listItemQuantity = parseInt(listItem.attr('data-quantity'));
                listItemQuantity += coffeeQuantity;
                if (listItemQuantity > 0) {
                    listItem.attr('data-quantity', listItemQuantity);
                    listItem.find('.selected-quantity').text('Quantity: ' + listItemQuantity);
                    var listItemPrice = parseFloat(listItem.attr('data-price'));
                    listItemPrice += coffeePrice * coffeeQuantity;
                    listItem.attr('data-price', listItemPrice);
                    listItem.find('.selected-price').text('Price: $' + listItemPrice.toFixed(2));
                } else {
                    // If quantity becomes 0, remove the item from the list
                    listItem.remove();
                }
            } else {
                // Peripheral is not in the list, add it
                var listItem = $('<li class="selected-item" data-name="' + coffeeName + '" data-quantity="' + coffeeQuantity + '" data-price="' + coffeePrice * coffeeQuantity + '">' +
                    '<p>Name: ' + coffeeName + '</p>' +
                    '<p class="selected-price">Price: $' + (coffeePrice * coffeeQuantity).toFixed(2) + '</p>' +
                    '<p class="selected-quantity">Quantity: ' + coffeeQuantity + '</p>' +
                    '</li>');
                $('#selected_coffee_list').append(listItem);
            }
        });

        // Handle change event on peripheral quantity input fields (similar to appliances)
        $('.pic_option input[name="quantity"]').on('change', function() {
            var coffeeName = $(this).closest('div').find('p').eq(0).text().split(': ')[1];
            var coffeeQuantity = parseInt($(this).val());

            // Find the corresponding item in the selected list and update it
            var listItem = $('#selected_coffee_list').find('li[data-name="' + coffeeName + '"]');
            if (listItem.length > 0) {
                if (coffeeQuantity > 0) {
                    // Update quantity and price
                    listItem.attr('data-quantity', coffeeQuantity);
                    listItem.find('.selected-quantity').text('Quantity: ' + coffeeQuantity);
                    var coffeePrice = parseFloat($(this).closest('div').find('p').eq(1).text().split(': ')[1].replace('$', ''));
                    var listItemPrice = coffeePrice * coffeeQuantity;
                    listItem.attr('data-price', listItemPrice);
                    listItem.find('.selected-price').text('Price: $' + listItemPrice.toFixed(2));
                } else {
                    // If quantity becomes 0, remove the item from the list
                    listItem.remove();
                }
            }
        });

        // Calculate bill (similar to appliances)
        $('#calculate_bill').click(function() {
            var totalPrice = 0;
            var totalQuantity = 0;

            // Calculate total price and quantity (similar to appliances)
            $('.selected-item').each(function() {
                var priceText = $(this).find('.selected-price').text().split(': ')[1];
                var quantityText = $(this).find('.selected-quantity').text().split(': ')[1];

                var price = parseFloat(priceText.replace('$', ''));
                var quantity = parseInt(quantityText);

                totalPrice += price;
                totalQuantity += quantity;
            });

            // Update input fields (similar to appliances)
            $('#price').val('$' + totalPrice.toFixed(2));
            $('#quantity').val(totalQuantity);
            $('#amount_to_pay').val('$' + totalPrice.toFixed(2));

            var customerCash = parseFloat($('#customer_cash').val());
            var changeAmount = customerCash - totalPrice;

            // Update change amount field (similar to appliances)
            if (!isNaN(changeAmount) && changeAmount >= 0) {
                $('#change_amount').val('$' + changeAmount.toFixed(2));
            } else {
                $('#change_amount').val('');
            }
        });

        // Clear order details and selected peripheral list (similar to appliances)
        $('#new_order').click(function() {
            $('#price').val('');
            $('#quantity').val('');
            $('#amount_to_pay').val('');
            $('#customer_cash').val('');
            $('#change_amount').val('');
            $('#selected_peripheral_list').empty();
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
                <div class="text-center mb-3"> 
                        <img src="coffee_images/1.jpg" data-toggle="tooltip" data-placement="bottom" 
                        title="Espresso" width="200" height="210" alt="Espresso">
                        <p>Name: Espresso</p>
                        <p>Price: $3</p>
                        <input type="number" name="quantity" value="0" min="0">
                    </div>
                    
                <div class="text-center mb-3"> 
                        <img src="coffee_images/2.jpg" data-toggle="tooltip" data-placement="bottom" 
                        title="Americano" width="200" height="210" alt="Americano">
                        <p>Name: Americano</p>
                        <p>Price: $5</p>
                        <input type="number" name="quantity" value="0" min="0">
                        </div>
                <div class="text-center mb-3"> 
                        <img src="coffee_images/3.jpg" data-toggle="tooltip" data-placement="bottom" 
                        title="Robusta" width="200" height="210" alt="Robusta">
                        <p>Name: Robusta</p>
                        <p>Price: $15</p>
                        <input type="number" name="quantity" value="0" min="0">
                    </div>
                <div class="text-center mb-3"> 
                        <img src="coffee_images/4.jpg" data-toggle="tooltip" data-placement="bottom" 
                        title="Arabica" width="200" height="210" alt="Arabica">
                        <p>Name: Arabica</p>
                        <p>Price: $8</p>
                        <input type="number" name="quantity" value="0" min="0">
                </div>
                <div class="text-center mb-3"> 
                        <img src="coffee_images/5.jpg" data-toggle="tooltip" data-placement="bottom" 
                        title="Latte" width="200" height="210" alt="Latte">
                        <p>Name: Latte</p>
                        <p>Price: $6</p>
                        <input type="number" name="quantity" value="0" min="0">
                </div>
                <div class="text-center mb-3"> 
                        <img src="coffee_images/6.jpg" data-toggle="tooltip" data-placement="bottom" 
                        title="Black coffee_images" width="200" height="210" alt="Black coffee_images">
                        <p>Name: Black coffee_images</p>
                        <p>Price: $1</p>
                        <input type="number" name="quantity" value="0" min="0">
                </div>
                <div class="text-center mb-3"> 
                        <img src="coffee_images/7.jpg" data-toggle="tooltip" data-placement="bottom" 
                        title="Cappuccino" width="200" height="210" alt="Cappuccino">
                        <p>Name: Cappuccino</p>
                        <p>Price: $6</p>
                        <input type="number" name="quantity" value="0" min="0">
                </div>
                    <div class="text-center mb-3"> 
                        <img src="coffee_images/8.jpg" data-toggle="tooltip" data-placement="bottom" 
                        title="Red Eye" width="200" height="210" alt="Red Eye">
                        <p>Name: Red Eye</p>
                        <p>Price: $</p>
                        <input type="number" name="quantity" value="0" min="0">
                    </div>
                    <div class="text-center mb-3"> 
                        <img src="coffee_images/9.jpg" data-toggle="tooltip" data-placement="bottom" 
                        title="Cortado" width="200" height="210" alt="Cortado">
                        <p>Name: Cortado</p>
                        <p>Price: $5</p>
                        <input type="number" name="quantity" value="0" min="0">
                    </div>
                    <div class="text-center mb-3"> 
                        <img src="coffee_images/10.jpg" data-toggle="tooltip" data-placement="bottom" 
                        title="Doppio" width="200" height="210" alt="Doppio">
                        <p>Name: Doppio</p>
                        <p>Price: $4</p>
                        <input type="number" name="quantity" value="0" min="0">
                    </div>
                    <div class="text-center mb-3"> 
                        <img src="coffee_images/11.jpg" data-toggle="tooltip" data-placement="bottom" 
                        title="Lungo" width="200" height="210" alt="Lungo">
                        <p>Name: Lungo</p>
                        <p>Price: $4</p>
                        <input type="number" name="quantity" value="0" min="0">
                    </div>
                    <div class="text-center mb-3"> 
                        <img src="coffee_images/12.jpg" data-toggle="tooltip" data-placement="bottom" 
                        title="Macchiato" width="200" height="210" alt="Macchiato">
                        <p>Name: Macchiato</p>
                        <p>Price: $5</p>
                        <input type="number" name="quantity" value="0" min="0">
                    </div>
                    <div class="text-center mb-3"> 
                        <img src="coffee_images/13.jpg" data-toggle="tooltip" data-placement="bottom" 
                        title="Mocha" width="200" height="210" alt="Mocha">
                        <p>Name: Mocha</p>
                        <p>Price: $4</p>
                        <input type="number" name="quantity" value="0" min="0">
                    </div>
                    <div class="text-center mb-3"> 
                        <img src="coffee_images/14.jpg" data-toggle="tooltip" data-placement="bottom" 
                        title="Irish" width="200" height="210" alt="Irish">
                        <p>Name: Irish</p>
                        <p>Price: $12</p>
                        <input type="number" name="quantity" value="0" min="0">
                    </div>
                    <div class="text-center mb-3"> 
                        <img src="coffee_images/15.jpg" data-toggle="tooltip" data-placement="bottom" 
                        title="Affogato" width="200" height="210" alt="Affogato">
                        <p>Name: Affogato</p>
                        <p>Price: $6</p>
                        <input type="number" name="quantity" value="0" min="0">
                    </div>
                    <div class="text-center mb-3"> 
                        <img src="coffee_images/16.jpg" data-toggle="tooltip" data-placement="bottom" 
                        title="Cafe au Lait" width="200" height="210" alt="Cafe au Lait">
                        <p>Name: Cafe au Lait</p>
                        <p>Price: $6</p>
                        <input type="number" name="quantity" value="0" min="0">
                    </div>
                    <div class="text-center mb-3"> 
                        <img src="coffee_images/17.jpg" data-toggle="tooltip" data-placement="bottom" 
                        title="Frappuccino" width="200" height="210" alt="Frappuccino">
                        <p>Name: Frappuccino</p>
                        <p>Price: $6</p>
                        <input type="number" name="quantity" value="0" min="0">
                    </div>
                    <div class="text-center mb-3"> 
                        <img src="coffee_images/18.jpg" data-toggle="tooltip" data-placement="bottom" 
                        title="Nitro" width="200" height="210" alt="Nitro">
                        <p>Name: Nitro</p>
                        <p>Price: $6</p>
                        <input type="number" name="quantity" value="0" min="0">
                    </div>
                    <div class="text-center mb-3"> 
                        <img src="coffee_images/19.jpg" data-toggle="tooltip" data-placement="bottom" 
                        title="Cold Brew" width="200" height="210" alt="Cold Brew">
                        <p>Name: Cold Brew</p>
                        <p>Price: $5</p>
                        <input type="number" name="quantity" value="0" min="0">
                    </div>
                    <div class="text-center mb-3"> 
                        <img src="coffee_images/20.jpg" data-toggle="tooltip" data-placement="bottom" 
                        title="Ristretto" width="200" height="210" alt="Ristretto">
                        <p>Name: Ristretto</p>
                        <p>Price: $4</p>
                        <input type="number" name="quantity" value="0" min="0">
                    </div>



                </div>
            </div>
        </div>
        
        <div class="row mt-3">
            <div class="selected-peripheral-tab">
                <h4>Selected Coffee:</h4>
                <ul id="selected_coffee_list"></ul>
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
