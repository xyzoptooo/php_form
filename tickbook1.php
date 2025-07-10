<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket Checkout</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(to right, #f5f7fa, #c3cfe2);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        form {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 600px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }
        input, select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 1rem;
        }
        .radio-group, .checkbox-group {
            margin-top: 15px;
        }
        .radio-group label,
        .checkbox-group label {
            margin-right: 15px;
            font-weight: normal;
        }
        button {
            margin-top: 20px;
            width: 100%;
            padding: 12px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.3s;
        }
        button:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <form name="checkout" action="saveticket.php" method="POST">
        <h1> Ticket Checkout</h1>

        <label for="category">Select Ticket Category</label>
        <select name="category" required>
            <option>Select Ticket Category</option>
            <option>Standard Admission - KSh 2,500</option>
            <option>VIP Access - KSh 7,500</option>
            <option>Balcony Seating - KSh 3,500</option>
            <option>Student Discount (requires ID) - KSh 1,800</option>
            <option>Child (Under 12) - KSh 1,000</option>
        </select>

        <label for="numberOfTickets">Number of Tickets</label>
        <input type="number" id="numberOfTickets" name="numberOfTickets" min="1" value="1" required>

        <label for="fullName">Full Name</label>
        <input type="text" id="fullName" name="fullName" required>

        <label for="email">Email Address</label>
        <input type="email" id="email" name="email" required>

        <label for="phone">Phone Number</label>
        <input type="tel" id="phone" name="phone">

        <div class="radio-group">
            <label>Ticket Delivery Method:</label><br>
            <input type="radio" id="deliveryEmail" name="deliveryMethod" value="email" checked required>
            <label for="deliveryEmail">E-ticket (Email)</label>
            <input type="radio" id="deliveryPrint" name="deliveryMethod" value="print">
            <label for="deliveryPrint">Print-at-Home</label>
            <input type="radio" id="deliveryWillCall" name="deliveryMethod" value="willcall">
            <label for="deliveryWillCall">Venue Pickup</label>
        </div>

        <label for="promoCode">Discount Code (Optional)</label>
        <input type="text" id="promoCode" name="promoCode">

        <label for="paymentMethod">Payment Method</label>
        <select id="paymentMethod" name="paymentMethod" required>
            <option value="">-- Select Payment Method --</option>
            <option value="creditcard">Credit Card</option>
            <option value="mpesa">M-Pesa</option>
            <option value="paypal">PayPal</option>
        </select>

        <div class="checkbox-group">
            <input type="checkbox" id="acceptTerms" name="acceptTerms" value="yes" required>
            <label for="acceptTerms">I accept the <a href="#" target="_blank">Terms & Conditions</a></label>
        </div>

        <button type="submit">Calculate Total & Proceed</button>
    </form>
</body>
</html>
