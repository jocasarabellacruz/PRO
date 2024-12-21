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
        <h2 style="font-size: 1rem; color: #ffffff; font-weight: 300;">Displayed here are personalized book recommendations tailored to your interests, based on your borrowing history. We've curated these selections to help you discover more of what you love. Enjoy exploring new titles and authors that resonate with your past choices!</h2>
        <div class="book-record">
            <div id="recommendationsContainer">
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

        $(document).ready(function () {
            $.ajax({
                url: 'php/getCurrentStudent.php',
                method: 'GET',
                success: function (response) {
                    const data = JSON.parse(response);
                    if (data.success) {
                        const studentID = data.studentID;

                        $.ajax({
                            url: 'php/getRecommendations.php',
                            method: 'POST',
                            data: { studentID: studentID },
                            success: function (data) {
                                $('#recommendationsContainer').html(data); 
                                $('#studentName').text(studentID);
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
