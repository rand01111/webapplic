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
    <link rel="stylesheet" href="css/burgers"> <!-- Update the CSS file path -->


    <script>
    $(document).ready(function() {
        // Enable tooltips
        $('[data-toggle="tooltip"]').tooltip();

        // Handle click event on burger images
        $('.pic_option div').click(function() {
            var burgerName = $(this).find('p').eq(0).text().split(': ')[1];
            var burgerPrice = parseFloat($(this).find('p').eq(1).text().split(': ')[1].replace('$', ''));
            var burgerQuantityInput = $(this).find('input[name="quantity"]');
            var burgerQuantity = parseInt(burgerQuantityInput.val());

            // Check if the burger is already in the selected list
            var listItem = $('#selected_burger_list').find('li[data-name="' + burgerName + '"]');
            if (listItem.length > 0) {
                // Burger is already in the list, update quantity and price
                var listItemQuantity = parseInt(listItem.attr('data-quantity'));
                listItemQuantity += burgerQuantity;
                if (listItemQuantity > 0) {
                    listItem.attr('data-quantity', listItemQuantity);
                    listItem.find('.selected-quantity').text('Quantity: ' + listItemQuantity);
                    var listItemPrice = parseFloat(listItem.attr('data-price'));
                    listItemPrice += burgerPrice * burgerQuantity;
                    listItem.attr('data-price', listItemPrice);
                    listItem.find('.selected-price').text('Price: $' + listItemPrice.toFixed(2));
                } else {
                    // If quantity becomes 0, remove the item from the list
                    listItem.remove();
                }
            } else {
                // Burger is not in the list, add it
                var listItem = $('<li class="selected-item" data-name="' + burgerName + '" data-quantity="' + burgerQuantity + '" data-price="' + burgerPrice * burgerQuantity + '">' +
                    '<p>Name: ' + burgerName + '</p>' +
                    '<p class="selected-price">Price: $' + (burgerPrice * burgerQuantity).toFixed(2) + '</p>' +
                    '<p class="selected-quantity">Quantity: ' + burgerQuantity + '</p>' +
                    '</li>');
                $('#selected_burger_list').append(listItem);
            }
        });

        // Handle change event on burger quantity input fields
        $('.pic_option input[name="quantity"]').on('change', function() {
            var burgerName = $(this).closest('div').find('p').eq(0).text().split(': ')[1];
            var burgerQuantity = parseInt($(this).val());

            // Find the corresponding item in the selected list and update it
            var listItem = $('#selected_burger_list').find('li[data-name="' + burgerName + '"]');
            if (listItem.length > 0) {
                if (burgerQuantity > 0) {
                    // Update quantity and price
                    listItem.attr('data-quantity', burgerQuantity);
                    listItem.find('.selected-quantity').text('Quantity: ' + burgerQuantity);
                    var burgerPrice = parseFloat($(this).closest('div').find('p').eq(1).text().split(': ')[1].replace('$', ''));
                    var listItemPrice = burgerPrice * burgerQuantity;
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

        // Clear order details and selected burger list
        $('#new_order').click(function() {
            $('#price').val('');
            $('#quantity').val('');
            $('#amount_to_pay').val('');
            $('#customer_cash').val('');
            $('#change_amount').val('');
            $('#selected_burger_list').empty();
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
                        <img src="burger_images/1.png" data-toggle="tooltip" data-placement="bottom" 
                        title="burger1" width="200" height="210" alt="burger1">
                        <p>Name: Burger 1</p>
                        <p>Price: $10</p>
                        <input type="number" name="quantity" value="0" min="0">
                    </div>
                    <div> 
                        <img src="burger_images/2.png" data-toggle="tooltip" data-placement="bottom"
                        title="burger2" alt="burger2" width="200" height="210">
                        <p>Name: Burger 2</p>
                        <p>Price: $12</p>
                        <input type="number" name="quantity" value="0" min="0">
                    </div>
                    <div> 
                        <img src="burger_images/3.png" data-toggle="tooltip" data-placement="bottom"
                        title="burger3" alt="burger3" width="200" height="210">
                        <p>Name: Burger 3</p>
                        <p>Price: $11</p>
                        <input type="number" name="quantity" value="0" min="0">
                    </div>
                    <div> 
                        <img src="burger_images/4.png" data-toggle="tooltip" data-placement="bottom"
                        title="burger4" alt="burger4" width="200" height="210">
                        <p>Name: Burger 4</p>
                        <p>Price: $14</p>
                        <input type="number" name="quantity" value="0" min="0">
                    </div>
                    <div> 
                        <img src="burger_images/5.png" data-toggle="tooltip" data-placement="bottom"
                        title="burger5" alt="burger5" width="200" height="210">
                        <p>Name: Burger 5</p>
                        <p>Price: $16</p>
                        <input type="number" name="quantity" value="0" min="0">
                    </div>
                    <div> 
                        <img src="burger_images/6.png" data-toggle="tooltip" data-placement="bottom"
                        title="burger6" alt="burger6" width="200" height="210">
                        <p>Name: Burger 6</p>
                        <p>Price: $15</p>
                        <input type="number" name="quantity" value="0" min="0">
                    </div>
                    <div> 
                        <img src="burger_images/7.png" data-toggle="tooltip" data-placement="bottom"
                        title="burger7" alt="burger7" width="200" height="210">
                        <p>Name: Burger 7</p>
                        <p>Price: $20</p>
                        <input type="number" name="quantity" value="0" min="0">
                    </div>
                    <div> 
                        <img src="burger_images/8.png" data-toggle="tooltip" data-placement="bottom"
                        title="burger8" alt="burger8" width="200" height="210">
                        <p>Name: Burger 8</p>
                        <p>Price: $19</p>
                        <input type="number" name="quantity" value="0" min="0">
                    </div>
                    <div> 
                        <img src="burger_images/9.png" data-toggle="tooltip" data-placement="bottom"
                        title="burger9" alt="burger9" width="200" height="210">
                        <p>Name: Burger 9</p>
                        <p>Price: $23</p>
                        <input type="number" name="quantity" value="0" min="0">
                    </div>
                    <div> 
                        <img src="burger_images/10.png" data-toggle="tooltip" data-placement="bottom"
                        title="burger10" alt="burger10" width="200" height="210">
                        <p>Name: Burger 10</p>
                        <p>Price: $25</p>
                        <input type="number" name="quantity" value="0" min="0">
                    </div>
                    <div> 
                        <img src="burger_images/11.png" data-toggle="tooltip" data-placement="bottom"
                        title="burger11" alt="burger11" width="200" height="210">
                        <p>Name: Burger 11</p>
                        <p>Price: $11</p>
                        <input type="number" name="quantity" value="0" min="0">
                    </div>
                    <div> 
                        <img src="burger_images/12.png" data-toggle="tooltip" data-placement="bottom"
                        title="burger12" alt="burger12" width="200" height="210">
                        <p>Name: Burger 12</p>
                        <p>Price: $24</p>
                        <input type="number" name="quantity" value="0" min="0">
                    </div>
                    <div> 
                        <img src="burger_images/13.png" data-toggle="tooltip" data-placement="bottom"
                        title="burger13" alt="burger13" width="200" height="210">
                        <p>Name: Burger 13</p>
                        <p>Price: $13</p>
                        <input type="number" name="quantity" value="0" min="0">
                    </div>
                    <div> 
                        <img src="burger_images/14.png" data-toggle="tooltip" data-placement="bottom"
                        title="burger14" alt="burger14" width="200" height="210">
                        <p>Name: Burger 14</p>
                        <p>Price: $7</p>
                        <input type="number" name="quantity" value="0" min="0">
                    </div>
                    <div> 
                        <img src="burger_images/15.png" data-toggle="tooltip" data-placement="bottom"
                        title="burger15" alt="burger15" width="200" height="210">
                        <p>Name: Burger 15</p>
                        <p>Price: $8</p>
                        <input type="number" name="quantity" value="0" min="0">
                    </div>
                    <div> 
                        <img src="burger_images/16.png" data-toggle="tooltip" data-placement="bottom"
                        title="burger16" alt="burger16" width="200" height="210">
                        <p>Name: Burger 16</p>
                        <p>Price: $17</p>
                        <input type="number" name="quantity" value="0" min="0">
                    </div>
                    <div> 
                        <img src="burger_images/17.png" data-toggle="tooltip" data-placement="bottom"
                        title="burger17" alt="burger17" width="200" height="210">
                        <p>Name: Burger 17</p>
                        <p>Price: $16</p>
                        <input type="number" name="quantity" value="0" min="0">
                    </div>
                    <div> 
                        <img src="burger_images/18.png" data-toggle="tooltip" data-placement="bottom"
                        title="burger18" alt="burger18" width="200" height="210">
                        <p>Name: Burger 18</p>
                        <p>Price: $26</p>
                        <input type="number" name="quantity" value="0" min="0">
                    </div>
                    <div> 
                        <img src="burger_images/19.png" data-toggle="tooltip" data-placement="bottom"
                        title="burger19" alt="burger19" width="200" height="210">
                        <p>Name: Burger 19</p>
                        <p>Price: $17</p>
                        <input type="number" name="quantity" value="0" min="0">
                    </div>
                    <div> 
                        <img src="burger_images/20.png" data-toggle="tooltip" data-placement="bottom"
                        title="burger20" alt="burger20" width="200" height="210">
                        <p>Name: Burger 20</p>
                        <p>Price: $15</p>
                        <input type="number" name="quantity" value="0" min="0">
                </div>
            </div>
        </div>
        
        <div class="row mt-3">
            <div class="selected-burger-tab">
        <h4>Selected Burger:</h4>
        <ul id="selected_burger_list"></ul>
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
    </div>
</body>
</html>
