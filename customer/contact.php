<?php include './includes/header.php'; ?>
<div class="container" style="padding: 20px;">
    <h2>Contact Us</h2>
    <p>If you have any questions or inquiries, feel free to reach out:</p>
    <ul>
        <li>Email: jaimishdave07@gmail.com</li>
        <li>Phone: +91 9099077437</li>
        <li>Address: Multimart Pvt Ltd, Ahmedabad, Gujarat, India</li>
    </ul>

    <form method="POST" action="https://api.web3forms.com/submit">
        <!-- Required Web3Forms hidden fields -->
        <input type="hidden" name="access_key" value="93493cf2-24fb-43dc-b852-78d641dc0d8f">
        <input type="hidden" name="subject" value="New Contact from Multimart Website">
        <input type="hidden" name="from_name" value="Multimart Website">
        <input type="hidden" name="redirect" value="http://localhost/Multimart/customer/thank-you.html"> <!-- Optional redirect page -->

        <label>Name:</label><br>
        <input type="text" name="name" required><br><br>

        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>

        <label>Message:</label><br>
        <textarea name="message" rows="5" required></textarea><br><br>

        <button type="submit">Send Message</button>
    </form>
</div>
<?php include './includes/footer.php'; ?>
