<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Recommendations</title>
    <link rel="stylesheet" href="borrow.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body>
    <header>
        <h1>SHELFSEARCH</h1>
        <div class="digital-clock" id="digitalClock"></div>
    </header>

    <main>
        <h2>Recommended Books</h2>
        <div class="book-record">
            <div id="recommendationsContainer">
                <!-- Recommendations will be dynamically populated -->
            </div>
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

        // Fetch the current logged-in student ID
        $(document).ready(function () {
            $.ajax({
                url: 'php/getCurrentStudent.php',
                method: 'GET',
                success: function (response) {
                    const data = JSON.parse(response);
                    if (data.success) {
                        const studentID = data.studentID;

                        // Use the studentID to fetch recommendations
                        $.ajax({
                            url: 'php/getRecommendations.php',
                            method: 'POST',
                            data: { studentID: studentID },
                            success: function (data) {
                                $('#recommendationsContainer').html(data); // Populate recommendations
                                $('#studentName').text(studentID); // Display the student ID or name
                            },
                            error: function () {
                                $('#recommendationsContainer').html('<p>No recommendations available.</p>');
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
