import os
from flask import Flask, jsonify, render_template
from flask_cors import CORS
import subprocess

app = Flask(__name__,
            static_folder=os.path.join(os.getcwd(), 'chatbot/static'),
            template_folder=os.path.join(os.getcwd(), 'chatbot/templates'))

CORS(app, origins="http://127.0.0.1:5500")  # Allow CORS from the frontend URL

@app.route('/')
def index():
    return render_template("chatbot.html")  # Serve your chatbot's HTML template

@app.route('/start-chatbot', methods=['POST'])
def start_chatbot():
    try:
        # Start the chatbot backend (chatbot.py)
        subprocess.Popen(["python", "chatbot/chatbot.py"], shell=True)
        return jsonify({"status": "Chatbot started successfully"}), 200
    except Exception as e:
        return jsonify({"error": str(e)}), 500

if __name__ == "__main__":
    app.run(port=5000)
