document.addEventListener('DOMContentLoaded', async () => {
    const naturalQuery = document.getElementById('naturalQuery');
    const convertBtn = document.getElementById('convertBtn');
    const sqlQuery = document.getElementById('sqlQuery');
    const queryResults = document.getElementById('queryResults');

    // Fetch API key from server
    let apiKey;
    try {
        const response = await fetch('http://localhost:3001/api/getApiKey');
        const data = await response.json();
        apiKey = data.apiKey;
    } catch (error) {
        console.error('Error fetching API key:', error);
        return;
    }

    // Smooth scroll for navigation
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            document.querySelector(this.getAttribute('href')).scrollIntoView({
                behavior: 'smooth'
            });
        });
    });

    // Convert button click handler
    convertBtn.addEventListener('click', async () => {
        if (!naturalQuery.value.trim()) {
            alert('Please enter a query');
            return;
        }

        try {
            convertBtn.disabled = true;
            convertBtn.textContent = 'Converting...';

            // Call to backend API for conversion
            const response = await fetch('http://localhost:3001/api/convert', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${apiKey}`
                },
                body: JSON.stringify({ text: naturalQuery.value })
            });

            const result = await response.json();

            if (!result.success) {
                throw new Error(result.error);
            }

            // Clean up the SQL query to remove unwanted backticks and SQL markers
            let cleanSql = result.sql.replace(/sql|```/g, '').trim();

            // Display the cleaned-up SQL
            sqlQuery.textContent = cleanSql;

            // Animate the result
            sqlQuery.style.opacity = '0';
            setTimeout(() => {
                sqlQuery.style.opacity = '1';
                sqlQuery.style.transition = 'opacity 0.5s ease-in';
            }, 100);

            // Execute query
            executeQuery(cleanSql);

        } catch (error) {
            console.error('Error:', error);
            sqlQuery.textContent = 'Error converting query';
        } finally {
            convertBtn.disabled = false;
            convertBtn.textContent = 'Convert to SQL';
        }
    });

    async function executeQuery(sql) {
        try {
            queryResults.innerHTML = '<div class="loading"></div>';

            const response = await fetch('http://localhost:3001/api/execute', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${apiKey}`
                },
                body: JSON.stringify({ query: sql })
            });

            const data = await response.json();

            if (!data.success) {
                throw new Error(data.error);
            }

            // Determine if query is DDL (e.g., CREATE, ALTER, DROP) or DML
            const isDDL = /^(CREATE|ALTER|DROP|TRUNCATE)\b/i.test(sql);
            if (isDDL) {
                queryResults.innerHTML = '<div class="success-message">DDL command executed successfully.</div>';
            } else {
                displayResults(data.data);
            }
        } catch (error) {
            console.error('Error executing query:', error);
            queryResults.innerHTML =
                `<div class="error-message">
                    ${error.message || 'Error executing query'}
                </div>`;
        }
    }

    function displayResults(data) {
        if (!data || data.length === 0) {
            queryResults.innerHTML = 'No results found';
            return;
        }

        const table = document.createElement('table');
        table.className = 'results-table';

        // Create header
        const headers = Object.keys(data[0]);
        const headerRow = document.createElement('tr');
        headers.forEach(header => {
            const th = document.createElement('th');
            th.textContent = header;
            headerRow.appendChild(th);
        });
        table.appendChild(headerRow);

        // Create data rows
        data.forEach(row => {
            const tr = document.createElement('tr');
            headers.forEach(header => {
                const td = document.createElement('td');
                td.textContent = row[header];
                tr.appendChild(td);
            });
            table.appendChild(tr);
        });

        queryResults.innerHTML = '';
        queryResults.appendChild(table);
    }
});
