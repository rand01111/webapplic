<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Random - Soda</title>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- Popper JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Link to your CSS file -->
    <link rel="stylesheet" href="css/peripherals"> <!-- Update the CSS file path -->

    <script>
    $(document).ready(function() {
        // Enable tooltips
        $('[data-toggle="tooltip"]').tooltip();

        // Handle click event on soda images
        $('.pic_option div').click(function() {
            var sodaName = $(this).find('p').eq(0).text().split(': ')[1];
            var sodaPrice = parseFloat($(this).find('p').eq(1).text().split(': ')[1].replace('$', ''));
            var sodaQuantityInput = $(this).find('input[name="quantity"]');
            var sodaQuantity = parseInt(sodaQuantityInput.val());

            // Check if the soda is already in the selected list
            var listItem = $('#selected_soda_list').find('li[data-name="' + sodaName + '"]');
            if (listItem.length > 0) {
                // Soda is already in the list, update quantity and price
                var listItemQuantity = parseInt(listItem.attr('data-quantity'));
                listItemQuantity += sodaQuantity;
                if (listItemQuantity > 0) {
                    listItem.attr('data-quantity', listItemQuantity);
                    listItem.find('.selected-quantity').text('Quantity: ' + listItemQuantity);
                    var listItemPrice = parseFloat(listItem.attr('data-price'));
                    listItemPrice += sodaPrice * sodaQuantity;
                    listItem.attr('data-price', listItemPrice);
                    listItem.find('.selected-price').text('Price: $' + listItemPrice.toFixed(2));
                } else {
                    // If quantity becomes 0, remove the item from the list
                    listItem.remove();
                }
            } else {
                // Soda is not in the list, add it
                var listItem = $('<li class="selected-item" data-name="' + sodaName + '" data-quantity="' + sodaQuantity + '" data-price="' + sodaPrice * sodaQuantity + '">' +
                    '<p>Name: ' + sodaName + '</p>' +
                    '<p class="selected-price">Price: $' + (sodaPrice * sodaQuantity).toFixed(2) + '</p>' +
                    '<p class="selected-quantity">Quantity: ' + sodaQuantity + '</p>' +
                    '</li>');
                $('#selected_soda_list').append(listItem);
            }
        });

        // Handle change event on soda quantity input fields
        $('.pic_option input[name="quantity"]').on('change', function() {
            var sodaName = $(this).closest('div').find('p').eq(0).text().split(': ')[1];
            var sodaQuantity = parseInt($(this).val());

            // Find the corresponding item in the selected list and update it
            var listItem = $('#selected_soda_list').find('li[data-name="' + sodaName + '"]');
            if (listItem.length > 0) {
                if (sodaQuantity > 0) {
                    // Update quantity and price
                    listItem.attr('data-quantity', sodaQuantity);
                    listItem.find('.selected-quantity').text('Quantity: ' + sodaQuantity);
                    var sodaPrice = parseFloat($(this).closest('div').find('p').eq(1).text().split(': ')[1].replace('$', ''));
                    var listItemPrice = sodaPrice * sodaQuantity;
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

        // Clear order details and selected soda list
        $('#new_order').click(function() {
            $('#price').val('');
            $('#quantity').val('');
            $('#amount_to_pay').val('');
            $('#customer_cash').val('');
            $('#change_amount').val('');
            $('#selected_soda_list').empty();
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
                    <option value="peripherals">Peripherals</option>
                    <option value="soda" selected>Soda</option>
                    <option value="appliances">Appliances</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Go</button>
        </form>
        
        <div class="row mt-3">
            <div class="col-md-12">
                <div class="pic_option d-flex flex-wrap justify-content-around">
                    <div class="text-center mb-3"> 
                        <img src="soda_images/7up.png" data-toggle="tooltip" data-placement="bottom" 
                        title="7up" width="200" height="210" alt="7up">
                        <p>Name: 7up</p>
                        <p>Price: $1.50</p>
                        <input type="number" name="quantity" value="0" min="0">
                    </div>
                    <div class="text-center mb-3"> 
                        <img src="soda_images/a&w.png" data-toggle="tooltip" data-placement="bottom" 
                        title="a&w" width="200" height="210" alt="a&w">
                        <p>Name: a&w</p>
                        <p>Price: $2.00</p>
                        <input type="number" name="quantity" value="0" min="0">
                    </div>
                    <div class="text-center mb-3"> 
                        <img src="soda_images/canada dry.png" data-toggle="tooltip" data-placement="bottom" 
                        title="canada dry" width="200" height="210" alt="canada dry">
                        <p>Name: canada dry</p>
                        <p>Price: $3.75</p>
                        <input type="number" name="quantity" value="0" min="0">
                    </div>
                    <div class="text-center mb-3"> 
                        <img src="soda_images/coca cola.png" data-toggle="tooltip" data-placement="bottom" 
                        title="coca cola" width="200" height="210" alt="coca cola">
                        <p>Name: coca cola</p>
                        <p>Price: $1.75</p>
                        <input type="number" name="quantity" value="0" min="0">
                    </div>
                    <div class="text-center mb-3"> 
                        <img src="soda_images/coke vanilla.png" data-toggle="tooltip" data-placement="bottom" 
                        title="coke vanilla" width="200" height="210" alt="coke vanilla">
                        <p>Name: coke vanilla</p>
                        <p>Price: $2.25</p>
                        <input type="number" name="quantity" value="0" min="0">
                    </div>
                    <div class="text-center mb-3"> 
                        <img src="soda_images/coke zero.png" data-toggle="tooltip" data-placement="bottom" 
                        title="coke zero" width="200" height="210" alt="coke zero">
                        <p>Name: coke zero</p>
                        <p>Price: $2.00</p>
                        <input type="number" name="quantity" value="0" min="0">
                    </div>
                    <div class="text-center mb-3"> 
                        <img src="soda_images/crush orange.png" data-toggle="tooltip" data-placement="bottom" 
                        title="crush orange" width="200" height="210" alt="crush orange">
                        <p>Name: crush orange</p>
                        <p>Price: $1.75</p>
                        <input type="number" name="quantity" value="0" min="0">
                    </div>
                    <div class="text-center mb-3"> 
                        <img src="soda_images/crush grape.png" data-toggle="tooltip" data-placement="bottom" 
                        title="crush grape" width="200" height="210" alt="crush grape">
                        <p>Name: crush grape</p>
                        <p>Price: $2.00</p>
                        <input type="number" name="quantity" value="0" min="0">
                    </div>
                    <div class="text-center mb-3"> 
                        <img src="soda_images/dr pepper.png" data-toggle="tooltip" data-placement="bottom" 
                        title="dr pepper" width="200" height="210" alt="dr pepper">
                        <p>Name: dr pepper</p>
                        <p>Price: $2.25</p>
                        <input type="number" name="quantity" value="0" min="0">
                    </div>
                    <div class="text-center mb-3"> 
                        <img src="soda_images/fanta.png" data-toggle="tooltip" data-placement="bottom" 
                        title="fanta" width="200" height="210" alt="fanta">
                        <p>Name: fanta</p>
                        <p>Price: $1.75</p>
                        <input type="number" name="quantity" value="0" min="0">
                    </div>
                    <div class="text-center mb-3"> 
                        <img src="soda_images/irn bru.png" data-toggle="tooltip" data-placement="bottom" 
                        title="irn bru" width="200" height="210" alt="irn bru">
                        <p>Name: irn bru</p>
                        <p>Price: $2.50</p>
                        <input type="number" name="quantity" value="0" min="0">
                    </div>
                    <div class="text-center mb-3"> 
                        <img src="soda_images/monster.png" data-toggle="tooltip" data-placement="bottom" 
                        title="monster" width="200" height="210" alt="monster">
                        <p>Name: monster</p>
                        <p>Price: $2.25</p>
                        <input type="number" name="quantity" value="0" min="0">
                    </div>
                    <div class="text-center mb-3"> 
                        <img src="soda_images/mountain dew.png" data-toggle="tooltip" data-placement="bottom" 
                        title="mountain dew" width="200" height="210" alt="mountain dew">
                        <p>Name: mountain dew</p>
                        <p>Price: $1.50</p>
                        <input type="number" name="quantity" value="0" min="0">
                    </div>
                    <div class="text-center mb-3"> 
                        <img src="soda_images/sunkist.png" data-toggle="tooltip" data-placement="bottom" 
                        title="sunkist" width="200" height="210" alt="sunkist">
                        <p>Name: sunkist</p>
                        <p>Price: $2.25</p>
                        <input type="number" name="quantity" value="0" min="0">
                    </div>
                    <div class="text-center mb-3"> 
                        <img src="soda_images/pepsi blue.png" data-toggle="tooltip" data-placement="bottom" 
                        title="pepsi blue" width="200" height="210" alt="pepsi blue">
                        <p>Name: pepsi blue</p>
                        <p>Price: $1.75</p>
                        <input type="number" name="quantity" value="0" min="0">
                    </div>
                    <div class="text-center mb-3"> 
                        <img src="soda_images/pepsi.png" data-toggle="tooltip" data-placement="bottom" 
                        title="pepsi" width="200" height="210" alt="pepsi">
                        <p>Name: pepsi</p>
                        <p>Price: $1.55</p>
                        <input type="number" name="quantity" value="0" min="0">
                    </div>
                    <div class="text-center mb-3"> 
                        <img src="soda_images/pibb.png" data-toggle="tooltip" data-placement="bottom" 
                        title="pibb" width="200" height="210" alt="pibb">
                        <p>Name: pibb</p>
                        <p>Price: $2.75</p>
                        <input type="number" name="quantity" value="0" min="0">
                    </div>
                    <div class="text-center mb-3"> 
                        <img src="soda_images/rc cola.png" data-toggle="tooltip" data-placement="bottom" 
                        title="rc cola" width="200" height="210" alt="rc cola">
                        <p>Name: rc cola</p>
                        <p>Price: $1.75</p>
                        <input type="number" name="quantity" value="0" min="0">
                    </div>
                    <div class="text-center mb-3"> 
                        <img src="soda_images/redbull.png" data-toggle="tooltip" data-placement="bottom" 
                        title="redbull" width="200" height="210" alt="redbull">
                        <p>Name: redbull</p>
                        <p>Price: $2.75</p>
                        <input type="number" name="quantity" value="0" min="0">
                    </div>
                    <div class="text-center mb-3"> 
                        <img src="soda_images/sprite.png" data-toggle="tooltip" data-placement="bottom" 
                        title="sprite" width="200" height="210" alt="sprite">
                        <p>Name: sprite</p>
                        <p>Price: $1.75</p>
                        <input type="number" name="quantity" value="0" min="0">
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mt-3">
            <div class="selected-peripheral-tab">
                <h4>Selected Soda:</h4>
                <ul id="selected_soda_list"></ul>
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
