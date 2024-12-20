from flask import Flask, jsonify
from flask_cors import CORS  # Import Flask-CORS
import subprocess

app = Flask(__name__)
CORS(app)  # Enable CORS for all routes

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
