<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Return a Book</title>
    <link rel="stylesheet" href="borrow.css">
    <!-- Montserrat Font -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <!-- Header -->
    <header>
        <h1>SHELFSEARCH</h1>
        <div class="digital-clock" id="digitalClock"></div>
    </header>

    <!-- Main Section -->
    <main>
        <h2>RETURN A BOOK</h2>
        <div class="container">
            <!-- Left Side: Book Record -->
            <section class="book-record">
                <h3>BOOK RECORD</h3>
                <table id="bookRecordTable">
                    <thead>
                        <tr>
                            <th>Book Title</th>
                            <th>Status</th>
                            <th>Date Borrowed</th>
                            <th>Date Returned</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Rows will be dynamically inserted here by JavaScript -->
                    </tbody>
                </table>
            </section>

            <!-- Right Side: Borrow Book Button -->
            <section class="scanning-section">
                <div class="loader"></div>
                <p class="scanning-text">Scanning Book...</p>
                <p class="manual-text">Can’t scan book? Manually input book ID here</p>
                <button class="manual-input" onclick="window.location.href='returnBookInput.php'">Input Book</button>
            </section>
        </div>
    </main>

    <!-- Return Button -->
    <a href="main.html" class="return-btn">Return</a>

    <!-- JavaScript -->
    <script>
        // Digital Clock
        function updateClock() {
            const now = new Date();
            const date = now.toLocaleDateString();
            const time = now.toLocaleTimeString();
            document.getElementById('digitalClock').textContent = `${date} ${time}`;
        }

        setInterval(updateClock, 1000);
        updateClock();

        // Fetch Book Records and Populate the Table
        $(document).ready(function () {
            const studentID = 202201798; // Replace this with dynamic student ID from session if available

            $.ajax({
                url: 'php/fetchBookRecords.php', // Backend PHP script
                method: 'POST',
                data: { studentID: studentID },
                success: function (data) {
                    // Populate the table with the returned data
                    $('#bookRecordTable tbody').html(data);
                },
                error: function () {
                    $('#bookRecordTable tbody').html('<tr><td colspan="4">Failed to load records</td></tr>');
                }
            });
        });
    </script>
</body>
</html>
