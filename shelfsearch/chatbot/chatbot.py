from flask import Flask, request, jsonify, render_template
from groq import Groq
import MySQLdb
from MySQLdb.cursors import DictCursor  # Import DictCursor
import os

secret_key = os.urandom(24)
app = Flask(__name__)

app.secret_key = os.urandom(24)
# Groq API setup
GROQ_API_KEY = "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"
client = Groq(api_key=GROQ_API_KEY)

# MySQL Database connection
def get_db_connection():
    return MySQLdb.connect(
        host="localhost",  # Your MySQL host
        user="root",       # Your MySQL username
        password="",  # Your MySQL password
        database="shelfsearchdb",  # Your MySQL database name
        cursorclass=DictCursor  # Use DictCursor here
    )


@app.route("/")
def home():
    return render_template("index.html")
@app.route("/ask_question", methods=["POST"])
def ask_question():
    data = request.json
    user_question = data.get("question")

    if not user_question:
        return jsonify({"error": "Question is required"}), 400

    try:
        # Check if the user's question matches local data from the database
        
        connection = get_db_connection()
        cursor = connection.cursor()
        cursor.execute("SELECT * FROM books")  # Execute SQL query
        books_data = cursor.fetchall()
        library_books = "\n".join(
    [
        f"{book['Title']}, {book['Author']}, "
        f"{book['Genre']}, {book['PublicationYear']}, "
        f"{book['Synopsis']}, availability {book['Status']}, "
        f"{book['ShelfLocation']}, barcode:{book['Barcode']}"
        for book in books_data
    ]   
)

        system_message_content = (
    "You are a library books management system assistant. Use the provided library_books as your sole source of information. "
    f"Here is the book data:\n{library_books}, Always check this data if the user ask about book and author."
    "Your sole purpose is to assist users with information about books in the library."
    "Reserving a book is not allowed"
    "Books that are borrowed is not available for borrowing."
    "Ensure that the user's requested books exist in the library_books."
    "books can be borrowed by going to the borrowing section of the system then scan or type the barcode of the book."
    "Answer user questions accurately and helpfully based on the content of these summaries. Do not reference being an AI "
    "or any limitations—respond as if you are knowledgeable about the books in the library. "
    "Online resources,program and events of the library can be checked in the https://www.thelibrary.dyci.edu.ph"
)
# Always check this data if the user ask about book Availability
        # Query library data from the database
        # cursor.execute("SELECT question, value FROM library_info")
        # library_data = cursor.fetchall()

        # Close the database connection
        cursor.close()
        connection.close()
        # previous_context = session.get('context', '')
        # if previous_context:
        #     system_message_content = f"Previous context: {previous_context}\n{system_message_content}"

        # Check if the user's question matches any of the library data
        # for row in library_data:
        #     if row['question'] in user_question.lower():
        #         return jsonify({"answer": row['value']})

        # Use the AI model to process the question if no local match is found
        completion = client.chat.completions.create(
            model="mixtral-8x7b-32768",
            messages=[ 
                {
                    "role": "system",
                    "content": system_message_content,
                },
                {"role": "user", "content": user_question},
            ],
            temperature=1,
            max_tokens=1024,
            top_p=1,
            stream=False,
            stop=None,
        )

        # Extract the answer from the Groq response
        ai_answer = completion.choices[0].message.content
        #session['context'] = ai_answer.strip()

        # If the AI's answer contains multiple points, it will include '\n' for newlines
        if ai_answer:
            return jsonify({"answer": ai_answer.strip()})

        return jsonify({"answer": ai_answer})

    except Exception as e:
        return jsonify({"error": str(e)}), 500
    
    
if __name__ == "__main__":
    app.run(debug=True)