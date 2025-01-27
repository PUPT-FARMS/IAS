<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Unique Grid Cipher</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 0;
        }

        header {
            text-align: center;
            padding: 2rem;
            background-color: #4caf50;
            color: #fff;
        }

        header h1 {
            margin: 0;
            font-size: 2.5rem;
            font-weight: 600;
        }

        main {
            max-width: 800px;
            margin: 2rem auto;
            background: #fff;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        form label {
            font-weight: 600;
            margin-bottom: 0.5rem;
            font-size: 1rem;
        }

        form input {
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            font-family: 'Poppins', sans-serif;
        }

        form button {
            padding: 0.8rem;
            font-size: 1rem;
            font-weight: 600;
            background-color: #4caf50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        form button:hover {
            background-color: #45a049;
        }

        table {
            margin: 2rem auto;
            border-collapse: collapse;
            width: 100%;
            max-width: 400px;
        }

        table td {
            border: 1px solid #ddd;
            text-align: center;
            padding: 1rem;
            font-size: 1.2rem;
            font-weight: 400;
            background-color: #f4f4f9;
            color: #333;
        }

        h3 {
            margin-top: 2rem;
            font-size: 1.5rem;
            font-weight: 600;
            color: #4caf50;
        }

        .output {
            font-size: 1.2rem;
            color: #333;
            background: #f9f9f9;
            padding: 1rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-top: 1rem;
        }

        footer {
            text-align: center;
            margin: 2rem 0;
            color: #aaa;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <header>
        <h1>Unique Grid Cipher</h1>
    </header>
    <main>
        <form id="cipherForm">
            <div>
                <label for="text">Enter text (letters only):</label>
                <input type="text" id="text" name="text" placeholder="e.g., HELLO" required>
            </div>
            <div>
                <label for="key">Enter key (numeric):</label>
                <input type="number" id="key" name="key" placeholder="e.g., 1" required>
            </div>
            <div>
                <button type="button" onclick="encryptText()">Encrypt</button>
                <button type="button" onclick="decryptText()">Decrypt</button>
            </div>
        </form>

        <h3>Grid (Substitution Table):</h3>
        <table>
            <tr><td>T</td><td>R</td><td>S</td><td>O</td></tr>
            <tr><td>P</td><td>A</td><td>Q</td><td>L</td></tr>
            <tr><td>M</td><td>C</td><td>E</td><td>N</td></tr>
            <tr><td>D</td><td>F</td><td>G</td><td>H</td></tr>
        </table>

        <h3>Encrypted Output:</h3>
        <div class="output" id="encryptedOutput"></div>

        <h3>Decrypted Output:</h3>
        <div class="output" id="decryptedOutput"></div>
    </main>
    <footer>
        &copy; 2025 Unique Grid Cipher System. All rights reserved.
    </footer>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        async function encryptText() {
            const text = document.getElementById('text').value;
            const key = document.getElementById('key').value;

            if (!/^[A-Za-z]+$/.test(text)) {
                alert("Please enter letters only for text.");
                return;
            }

            const response = await fetch('/cipher/encrypt', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ text, key }),
            });

            const data = await response.json();
            document.getElementById('encryptedOutput').innerText = data.encrypted || "Error in encryption.";
        }

        async function decryptText() {
            const text = document.getElementById('text').value;
            const key = document.getElementById('key').value;

            if (!/^[A-Za-z]+$/.test(text)) {
                alert("Please enter letters only for text.");
                return;
            }

            const response = await fetch('/cipher/decrypt', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ text, key }),
            });

            const data = await response.json();
            document.getElementById('decryptedOutput').innerText = data.decrypted || "Error in decryption.";
        }
    </script>
</body>
</html>