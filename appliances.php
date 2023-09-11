<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Random - Appliances</title>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- Popper JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Link to your CSS file -->
    <link rel="stylesheet" href="css/appliances"> <!-- Update the CSS file path -->

    <script>
    $(document).ready(function() {
        // Enable tooltips
        $('[data-toggle="tooltip"]').tooltip();

        // Handle click event on appliance images
        $('.pic_option div').click(function() {
            var applianceName = $(this).find('p').eq(0).text().split(': ')[1];
            var appliancePrice = parseFloat($(this).find('p').eq(1).text().split(': ')[1].replace('$', ''));
            var applianceQuantityInput = $(this).find('input[name="quantity"]');
            var applianceQuantity = parseInt(applianceQuantityInput.val());

            // Check if the appliance is already in the selected list
            var listItem = $('#selected_appliance_list').find('li[data-name="' + applianceName + '"]');
            if (listItem.length > 0) {
                // Appliance is already in the list, update quantity and price
                var listItemQuantity = parseInt(listItem.attr('data-quantity'));
                listItemQuantity += applianceQuantity;
                if (listItemQuantity > 0) {
                    listItem.attr('data-quantity', listItemQuantity);
                    listItem.find('.selected-quantity').text('Quantity: ' + listItemQuantity);
                    var listItemPrice = parseFloat(listItem.attr('data-price'));
                    listItemPrice += appliancePrice * applianceQuantity;
                    listItem.attr('data-price', listItemPrice);
                    listItem.find('.selected-price').text('Price: $' + listItemPrice.toFixed(2));
                } else {
                    // If quantity becomes 0, remove the item from the list
                    listItem.remove();
                }
            } else {
                // Appliance is not in the list, add it
                var listItem = $('<li class="selected-item" data-name="' + applianceName + '" data-quantity="' + applianceQuantity + '" data-price="' + appliancePrice * applianceQuantity + '">' +
                    '<p>Name: ' + applianceName + '</p>' +
                    '<p class="selected-price">Price: $' + (appliancePrice * applianceQuantity).toFixed(2) + '</p>' +
                    '<p class="selected-quantity">Quantity: ' + applianceQuantity + '</p>' +
                    '</li>');
                $('#selected_appliance_list').append(listItem);
            }
        });

        // Handle change event on appliance quantity input fields
        $('.pic_option input[name="quantity"]').on('change', function() {
            var applianceName = $(this).closest('div').find('p').eq(0).text().split(': ')[1];
            var applianceQuantity = parseInt($(this).val());

            // Find the corresponding item in the selected list and update it
            var listItem = $('#selected_appliance_list').find('li[data-name="' + applianceName + '"]');
            if (listItem.length > 0) {
                if (applianceQuantity > 0) {
                    // Update quantity and price
                    listItem.attr('data-quantity', applianceQuantity);
                    listItem.find('.selected-quantity').text('Quantity: ' + applianceQuantity);
                    var appliancePrice = parseFloat($(this).closest('div').find('p').eq(1).text().split(': ')[1].replace('$', ''));
                    var listItemPrice = appliancePrice * applianceQuantity;
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

        // Clear order details and selected appliance list
        $('#new_order').click(function() {
            $('#price').val('');
            $('#quantity').val('');
            $('#amount_to_pay').val('');
            $('#customer_cash').val('');
            $('#change_amount').val('');
            $('#selected_appliance_list').empty();
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
                        <img src="appliance_images/airfryer1.png" data-toggle="tooltip" data-placement="bottom" 
                        title="airfryer1" width="200" height="210" alt="airfryer1">
                        <p>Name: airfryer1</p>
                        <p>Price: $250</p>
                        <input type="number" name="quantity" value="0" min="0">
                    </div>
                    <div class="text-center mb-3"> 
                        <img src="appliance_images/blender1.png" data-toggle="tooltip" data-placement="bottom" 
                        title="blender1" width="200" height="210" alt="blender1">
                        <p>Name: blender1</p>
                        <p>Price: $150</p>
                        <input type="number" name="quantity" value="0" min="0">
                    </div>
                    <div class="text-center mb-3"> 
                        <img src="appliance_images/blender2.png" data-toggle="tooltip" data-placement="bottom" 
                        title="blender2" width="200" height="210" alt="blender2">
                        <p>Name: blender2</p>
                        <p>Price: $100</p>
                        <input type="number" name="quantity" value="0" min="0">
                    </div>
                    <div class="text-center mb-3"> 
                        <img src="appliance_images/blender3.png" data-toggle="tooltip" data-placement="bottom" 
                        title="blender3" width="200" height="210" alt="blender3">
                        <p>Name: blender3</p>
                        <p>Price: $75</p>
                        <input type="number" name="quantity" value="0" min="0">
                    </div>
                    <div class="text-center mb-3"> 
                        <img src="appliance_images/cooler1.png" data-toggle="tooltip" data-placement="bottom" 
                        title="cooler1" width="200" height="210" alt="cooler1">
                        <p>Name: cooler1</p>
                        <p>Price: $350</p>
                        <input type="number" name="quantity" value="0" min="0">
                    </div>
                    <div class="text-center mb-3"> 
                        <img src="appliance_images/fridge1.png" data-toggle="tooltip" data-placement="bottom" 
                        title="fridge1" width="200" height="210" alt="fridge1">
                        <p>Name: fridge1</p>
                        <p>Price: $400</p>
                        <input type="number" name="quantity" value="0" min="0">
                    </div>
                    <div class="text-center mb-3"> 
                        <img src="appliance_images/fridge2.png" data-toggle="tooltip" data-placement="bottom" 
                        title="fridge2" width="200" height="210" alt="fridge2">
                        <p>Name: fridge2</p>
                        <p>Price: $350</p>
                        <input type="number" name="quantity" value="0" min="0">
                    </div>
                    <div class="text-center mb-3"> 
                        <img src="appliance_images/fridge3.png" data-toggle="tooltip" data-placement="bottom" 
                        title="fridge3" width="200" height="210" alt="fridge3">
                        <p>Name: fridge3</p>
                        <p>Price: $450</p>
                        <input type="number" name="quantity" value="0" min="0">
                    </div>
                    <div class="text-center mb-3"> 
                        <img src="appliance_images/fridge4.png" data-toggle="tooltip" data-placement="bottom" 
                        title="fridge4" width="200" height="210" alt="fridge4">
                        <p>Name: fridge4</p>
                        <p>Price: $300</p>
                        <input type="number" name="quantity" value="0" min="0">
                    </div>
                    <div class="text-center mb-3"> 
                        <img src="appliance_images/microwave1.png" data-toggle="tooltip" data-placement="bottom" 
                        title="microwave1" width="200" height="210" alt="microwave1">
                        <p>Name: microwave1</p>
                        <p>Price: $200</p>
                        <input type="number" name="quantity" value="0" min="0">
                    </div>
                    <div class="text-center mb-3"> 
                        <img src="appliance_images/microwave2.png" data-toggle="tooltip" data-placement="bottom" 
                        title="microwave2" width="200" height="210" alt="microwave2">
                        <p>Name: microwave2</p>
                        <p>Price: $225</p>
                        <input type="number" name="quantity" value="0" min="0">
                    </div>
                    <div class="text-center mb-3"> 
                        <img src="appliance_images/microwave3.png" data-toggle="tooltip" data-placement="bottom" 
                        title="microwave3" width="200" height="210" alt="microwave3">
                        <p>Name: microwave3</p>
                        <p>Price: $150</p>
                        <input type="number" name="quantity" value="0" min="0">
                    </div>
                    <div class="text-center mb-3"> 
                        <img src="appliance_images/microwave4.png" data-toggle="tooltip" data-placement="bottom" 
                        title="microwave4" width="200" height="210" alt="microwave4">
                        <p>Name: microwave4</p>
                        <p>Price: $100</p>
                        <input type="number" name="quantity" value="0" min="0">
                    </div>
                    <div class="text-center mb-3"> 
                        <img src="appliance_images/microwave5.png" data-toggle="tooltip" data-placement="bottom" 
                        title="microwave5" width="200" height="210" alt="microwave5">
                        <p>Name: microwave5</p>
                        <p>Price: $350</p>
                        <input type="number" name="quantity" value="0" min="0">
                    </div>
                    <div class="text-center mb-3"> 
                        <img src="appliance_images/microwave6.png" data-toggle="tooltip" data-placement="bottom" 
                        title="microwave6" width="200" height="210" alt="microwave6">
                        <p>Name: microwave6</p>
                        <p>Price: $240</p>
                        <input type="number" name="quantity" value="0" min="0">
                    </div>
                    <div class="text-center mb-3"> 
                        <img src="appliance_images/tv1.png" data-toggle="tooltip" data-placement="bottom" 
                        title="tv1" width="200" height="210" alt="tv1">
                        <p>Name: tv1</p>
                        <p>Price: $750</p>
                        <input type="number" name="quantity" value="0" min="0">
                    </div>
                    <div class="text-center mb-3"> 
                        <img src="appliance_images/tv2.png" data-toggle="tooltip" data-placement="bottom" 
                        title="tv2" width="200" height="210" alt="tv2">
                        <p>Name: tv2</p>
                        <p>Price: $550</p>
                        <input type="number" name="quantity" value="0" min="0">
                    </div>
                    <div class="text-center mb-3"> 
                        <img src="appliance_images/washingmachine2.png" data-toggle="tooltip" data-placement="bottom" 
                        title="washingmachine2" width="200" height="210" alt="washingmachine2">
                        <p>Name: washingmachine2</p>
                        <p>Price: $175</p>
                        <input type="number" name="quantity" value="0" min="0">
                    </div>
                    <div class="text-center mb-3"> 
                        <img src="appliance_images/washingmachine1.png" data-toggle="tooltip" data-placement="bottom" 
                        title="washingmachine1" width="200" height="210" alt="washingmachine1">
                        <p>Name: washingmachine1</p>
                        <p>Price: $275</p>
                        <input type="number" name="quantity" value="0" min="0">
                    </div>
                    <div class="text-center mb-3"> 
                        <img src="appliance_images/washingmachine3.png" data-toggle="tooltip" data-placement="bottom" 
                        title="washingmachine3" width="200" height="210" alt="washingmachine3">
                        <p>Name: washingmachine3</p>
                        <p>Price: $120</p>
                        <input type="number" name="quantity" value="0" min="0">
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mt-3">
            <div class="selected-appliance-tab">
                <h4>Selected Appliances:</h4>
                <ul id="selected_appliance_list"></ul>
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
