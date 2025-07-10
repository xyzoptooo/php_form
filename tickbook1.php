<body>
    <h1>Ticket  Checkout</h1>

        <form name="checkout" action="saveticket.php" method="POST">
          Select Ticket Category: <select name="category">
                                     <option>--Select Ticket Category</option>
                                     <option>Standard Admission - KSh 2,500</option>
                                     <option>VIP Access - KSh 7,500</option>
                                     <option>Balcony Seating - KSh 3,500</option>
                                     <option>Student Discount (requires ID) - KSh 1,800</option>
                                     <option>Child (Under 12) - KSh 1,000</option>
                                 </select> <br> <br>
                                
          Number of Tickets: <input type="number" id="numberOfTickets" name="numberOfTickets" min="1" value="1" required> <br> <br>

          Full Name: <input type="text" id="fullName" name="fullName" required> <br> <br>

          Email Address: <input type="email" id="email" name="email" required> <br> <br>

          Phone Number: <input type="tel" id="phone" name="phone"> <br> <br>

           Ticket Delivery Method:
                                    <input type="radio" id="deliveryEmail" name="deliveryMethod" value="email" checked required>
                                    <label for="deliveryEmail">E-ticket (Email)</label>

                                    <input type="radio" id="deliveryPrint" name="deliveryMethod" value="print">
                                    <label for="deliveryPrint">Print-at-Home</label>

                                    <input type="radio" id="deliveryWillCall" name="deliveryMethod" value="willcall">
                                    <label for="deliveryWillCall">Will Call (Venue Pickup)</label> <br> <br>

            Discount Code (Optional): 
                                        <input type="text" id="promoCode" name="promoCode"> <br> <br>

            Payment Method:                            
                            <select id="paymentMethod" name="paymentMethod" required>
                                <option value="">-- Select Payment Method --</option>
                                <option value="creditcard">Credit Card</option>
                                <option value="mpesa">M-Pesa</option>
                                <option value="paypal">PayPal</option>
                            </select> <br> <br>

            <input type="checkbox" id="acceptTerms" name="acceptTerms" value="yes" required>
            <label for="acceptTerms">I accept the <a href="#" target="_blank">Terms & Conditions</a></label> <br> <br>

            <button type="submit">Calculate Total & Proceed</button>
        </form>
</body>
