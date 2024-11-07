const express = require('express');
const bodyParser = require('body-parser');
const mysql = require('mysql2');
const { GoogleGenerativeAI } = require('@google/generative-ai');
const path = require('path');
require('dotenv').config();

const app = express();
const port = 3000;

// Middleware
app.use(bodyParser.json());
app.use(express.static(path.join(__dirname, 'public')));

// Database connection
const db = mysql.createConnection({
    host: 'localhost',
    user: 'root',
    password: process.env.DB_PASSWORD,
    database: 'test' // Replace with your database name
});

// Gemini AI setup
const apiKey = "AIzaSyCQp7m9jx4CMpvI3e7W2ojVLGOc9_9NatY";
const genAI = new GoogleGenerativeAI(apiKey);
const model = genAI.getGenerativeModel({ model: "gemini-1.5-flash" });

// Routes
app.get('/', (req, res) => {
    res.sendFile(path.join(__dirname, 'public', 'index.html'));
});

app.get('/api/getApiKey', (req, res) => {
    res.json({ apiKey: process.env.GEMINI_API_KEY });
});

app.post('/api/convert', async (req, res) => {
    try {
        const { text } = req.body;
        const result = await model.generateContent(`Convert this natural language query to one single SQL query(don't explain anything at all only give the sql query, and only give one single sql query at a time. If you feel that user has given incomplete information, dont generate query, and ask the user to provide more information.): ${text}`);
        const response = result.response;
        res.json({ success: true, sql: response.text() });
    } catch (error) {
        res.json({ success: false, error: error.message });
    }
});

app.post('/api/execute', (req, res) => {
    const { query } = req.body;
    db.query(query, (error, results) => {
        if (error) {
            res.json({ success: false, error: error.message });
            return;
        }
        res.json({ success: true, data: results });
    });
});

app.listen(port, () => {
    console.log(`Server running at http://localhost:${port}`);
});