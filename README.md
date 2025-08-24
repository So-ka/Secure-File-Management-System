<h1>📌 Full-Stack Coding Challenge: Simple File Manager</h1>

<h2>Objective</h2>
<p>Build a secure file management system where authenticated users can upload, list, download, and delete their own files using a Vue 3 frontend and a Laravel API backend.</p>

<h2>Requirements</h2>

<h3>Core Features</h3>

<h4>Authentication</h4>
<ul>
    <li>User registration, login, and logout</li>
    <li>Each user manages only their own files</li>
</ul>

<h4>File Upload</h4>
<ul>
    <li>Single or multiple file uploads (max 5 files per request)</li>
    <li>Accepted file types: PDF, DOCX, PNG, JPG, ODT</li>
</ul>

<h4>File Listing</h4>
<ul>
    <li>Paginated list of uploaded files</li>
    <li>Display: file name, size, type, and upload date</li>
    <li>Optional: search files by name</li>
</ul>

<h4>File Actions</h4>
<ul>
    <li>Download: Download the file</li>
    <li>Delete: Remove the file (with confirmation)</li>
</ul>

<h3>Docker Setup</h3>
<ul>
    <li>Includes working <code>docker-compose.yml</code></li>
    <li>Ensure Docker and Docker Compose are installed</li>
</ul>

<pre><code># Build and start containers
docker-compose up -d --build

# Enter Laravel container
docker exec -it &lt;container_name&gt; bash

# Install dependencies
composer install

# Run migrations
php artisan migrate

# Seed database (optional)
php artisan db:seed
</code></pre>

<h3>Tech & Structure</h3>
<ul>
    <li>Backend: PHP (Laravel 11+)</li>
    <li>Frontend: Vue 3</li>
    <li>Version control: Git</li>
    <li>Host code on GitHub</li>
</ul>


<h3>Usage</h3>
<ol>
    <li>Clone repository:
        <pre><code>git clone https://github.com/yourusername/simple-file-manager.git</code></pre>
    </li>
    <li>Start Docker environment:
        <pre><code>npm install</code></pre>
        <pre><code>npm run build</code></pre>
        <pre><code>docker-compose up -d</code></pre>
    </li>
    <li>Access frontend at <code>http://localhost:8080</code></li>
    <li>Register a user and start managing files</li>
</ol>

<h3>Features Checklist</h3>
<ul>
    <li>✔ Authentication</li>
    <li>✔ File upload (single & multiple)</li>
    <li>✔ File listing with pagination</li>
    <li>✔ File download</li>
    <li>✔ File deletion</li>
</ul>
