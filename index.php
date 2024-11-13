<?php
$receipt = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $item = $_POST["item"];
  $quantity = intval($_POST["quantity"]);
  $payment = floatval($_POST["payment"]);

  $prices = [
    "item1" => 100,
    "item2" => 200,
    "item3" => 150
  ];

  $vatRate = 0.15;

  $itemPrice = isset($prices[$item]) ? $prices[$item] : 0;
  $totalCost = $itemPrice * $quantity * (1 + $vatRate);
  $balance = $payment - $totalCost;

  if ($balance >= 0) {
    $receipt = "<h2>Receipt</h2>
                    <p>Item: " . htmlspecialchars($item) . "</p>
                    <p>Quantity: $quantity</p>
                    <p>Total Cost (with VAT): KES" . number_format($totalCost, 2) . "</p>
                    <p>Payment Amount: KES" . number_format($payment, 2) . "</p>
                    <p>Balance: KES" . number_format($balance, 2) . "</p>
                    <p>Thank you for your purchase!</p>";
  } else {
    $receipt = "<p>Error: Insufficient payment. You need an additional KES" . number_format(abs($balance), 2) . "</p>";
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Shopping Cart</title>
  <style>
    body {
      font-family: Arial, sans-serif;
    }

    h1 {
      text-align: center;
    }

    label,
    select,
    input {
      margin: 10px;
    }

    button {
      margin-top: 20px;
    }

    #result {
      margin-top: 20px;
    }
  </style>
</head>

<body>

  <h1>Shopping App</h1>

  <form id="purchaseForm" action="index.php" method="POST">
    <label for="item">Select Item:</label>
    <select id="item" name="item" required>
      <option value="item1" data-price="100">Item 1 - $100</option>
      <option value="item2" data-price="200">Item 2 - $200</option>
      <option value="item3" data-price="150">Item 3 - $150</option>
    </select>

    <br>
    <br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" min="1" required>

    <br>
    <br>

    <label for="payment">Payment Amount:</label>
    <input type="number" id="payment" name="payment" min="0" required>

    <br>
    <br>

    <button type="button" onclick="calculateTotal()">Calculate Total</button>

    <br>
    <br>

    <div id="result"></div>

    <button type="submit" id="submitButton" disabled>Process Payment</button>
  </form>

  <div id="receipt">
    <?php echo $receipt; ?>
  </div>

  <script>
    function calculateTotal() {
      const itemSelect = document.getElementById("item");
      const selectedItem = itemSelect.options[itemSelect.selectedIndex];
      const itemPrice = parseFloat(selectedItem.getAttribute("data-price"));
      const quantity = parseInt(document.getElementById("quantity").value);
      const payment = parseFloat(document.getElementById("payment").value);
      const vatRate = 0.15;

      if (isNaN(quantity) || isNaN(payment) || quantity <= 0 || payment < 0) {
        document.getElementById("result").innerText = "Please enter valid quantity and payment.";
        return;
      }

      const totalCost = itemPrice * quantity * (1 + vatRate);
      const balance = payment - totalCost;

      if (balance >= 0) {
        document.getElementById("result").innerHTML = `Total Cost (with VAT): KES ${totalCost.toFixed(2)}<br>Balance: KES ${balance.toFixed(2)}`;
        document.getElementById("submitButton").disabled = false;
      } else {
        document.getElementById("result").innerHTML = `Total Cost (with VAT): KES ${totalCost.toFixed(2)}<br>Insufficient payment. You need additional KES ${Math.abs(balance).toFixed(2)}.`;
        document.getElementById("submitButton").disabled = true;
      }
    }
  </script>

</body>

</html>