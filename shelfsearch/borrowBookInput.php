<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrow a Book</title>
    <link rel="stylesheet" href="manual_input.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>
    <div class="logo-wrapper">
        <div class="logo">
            <img src="img/logo.png" alt="Logo">
        </div>
    </div>

    <header class="shelfsearch-header">
        <h1>Borrow a Book</h1>
        <div class="digital-clock" id="digitalClock"></div>
    </header>

    <div class="main-container">
        <div class="input-container">
            <input type="text" id="inputBox" class="input-box" readonly>
            <div class="keypad">
                <button onclick="addToInput('1')">1</button>
                <button onclick="addToInput('2')">2</button>
                <button onclick="addToInput('3')">3</button>
                <button onclick="addToInput('4')">4</button>
                <button onclick="addToInput('5')">5</button>
                <button onclick="addToInput('6')">6</button>
                <button onclick="addToInput('7')">7</button>
                <button onclick="addToInput('8')">8</button>
                <button onclick="addToInput('9')">9</button>
                <button onclick="deleteInput()">DELETE</button>
                <button onclick="addToInput('0')">0</button>
                <button onclick="submitBarcode()">ENTER</button>
            </div>
        </div>
    </div>

    <a href="borrow.php" class="return-btn">Return</a>

    <div id="successModal" class="modal fade">
        <div class="modal-dialog modal-confirm">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="icon-box">
                        <img src="img/check.png" alt="Success Icon" style="width: 70px; height: 70px;">
                    </div>
                    <h4 class="modal-title">Borrow Success!</h4>
                </div>
                <div class="modal-body">
                    <p class="text-center" id="successMessage">Book borrowed successfully!</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success btn-block" id="proceedButton">OK</button>
                </div>
            </div>
        </div>
    </div>

    <div id="failureModal" class="modal fade">
        <div class="modal-dialog modal-confirm">
            <div class="modal-content" style="border: 2px solid rgb(167, 35, 35);">
                <div class="modal-header">
                    <div class="icon-box">
                        <img src="img/fail.png" alt="Error Icon" style="width: 70px; height: 70px;">
                    </div>
                    <h4 class="modal-title">Borrow Failed!</h4>
                </div>
                <div class="modal-body">
                    <p class="text-center" id="errorMessage">Book is already borrowed or unavailable.</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger btn-block" data-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function updateClock() {
            const now = new Date();
            const date = now.toLocaleDateString();
            const time = now.toLocaleTimeString();
            document.getElementById('digitalClock').textContent = `${date} ${time}`;
        }

        setInterval(updateClock, 1000);
        updateClock();

        function addToInput(value) {
            const inputBox = document.getElementById('inputBox');
            inputBox.value += value;
        }

        function deleteInput() {
            const inputBox = document.getElementById('inputBox');
            inputBox.value = inputBox.value.slice(0, -1);
        }

        function submitBarcode() {
            const barcode = document.getElementById('inputBox').value;
            if (barcode) {
                fetch('php/borrowBook.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ barcode }),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        $('#successModal').modal('show');
                        document.getElementById('successMessage').textContent = data.message;
                        document.getElementById('proceedButton').addEventListener('click', () => {
                            $('#successModal').modal('hide');
                            window.location.href = 'borrow.php';
                        });
                    } else {
                        $('#failureModal').modal('show');
                        document.getElementById('errorMessage').textContent = data.message;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    $('#failureModal').modal('show');
                    document.getElementById('errorMessage').textContent = 'An error occurred. Please try again.';
                });
            } else {
                alert("Please enter a barcode before proceeding.");
            }
        }

    </script>
</body>
</html>
