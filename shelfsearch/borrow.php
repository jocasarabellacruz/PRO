<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrow a Book</title>
    <link rel="stylesheet" href="borrow.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <header>
        <h1>SHELFSEARCH</h1>
        <div class="digital-clock" id="digitalClock"></div>
    </header>

    <main>
        <h2>BORROW A BOOK</h2>
        <div class="container">
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
                    </tbody>
                </table>
            </section>

            <section class="scanning-section">
                <div class="loader"></div>
                <p class="scanning-text">Scanning Book...</p>
                <p class="manual-text">Can’t scan book? Manually input book ID here</p>
                <button class="manual-input" onclick="window.location.href='borrowBookInput.php'">Input Book</button>
            </section>
        </div>
    </main>

    <a href="main.html" class="return-btn">Return</a>

    <script>
        function updateClock() {
            const now = new Date();
            const date = now.toLocaleDateString();
            const time = now.toLocaleTimeString();
            document.getElementById('digitalClock').textContent = `${date} ${time}`;
        }

        setInterval(updateClock, 1000);
        updateClock();

        $(document).ready(function () {
            $.ajax({
                url: 'php/getCurrentStudent.php',
                method: 'GET',
                success: function (response) {
                    const data = JSON.parse(response);
                    if (data.success) {
                        const studentID = data.studentID;

                        $.ajax({
                            url: 'php/fetchBookRecords.php', 
                            method: 'POST',
                            data: { studentID: studentID },
                            success: function (data) {
                                $('#bookRecordTable tbody').html(data);
                            },
                            error: function () {
                                $('#bookRecordTable tbody').html('<tr><td colspan="4">Failed to load records</td></tr>');
                            }
                        });
                    } else {
                        $('#studentName').text('No Student Logged In');
                    }
                },
                error: function () {
                    $('#studentName').text('Error Fetching Student');
                }
            });
        });
    </script>
</body>
</html>
