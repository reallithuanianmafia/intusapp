# URL Shortener

## Overview

This is a simple URL shortener web application built using Laravel and Vue.js. The application allows users to shorten long URLs into concise, easy-to-share links.

![Alt Text](https://i.imgur.com/IAZ7Ekc.png)

## Features

- **Shorten URLs:** Enter a long URL, and the system generates a unique short URL.
- **Duplicate URL Handling:** Avoids generating a new short URL if the same long URL already exists.
- **Google Safe Browsing Integration:** Checks the safety of submitted URLs using the Google Safe Browsing API.
- **Redirection:** Users are redirected to the original URL when accessing the short URL.

## Getting Started

### Prerequisites

- [PHP](https://www.php.net/) installed
- [Composer](https://getcomposer.org/) installed
- [Node.js](https://nodejs.org/) installed
- [MySQL](https://www.mysql.com/) database


### SQL FILE 

```bash
intusdb_dump.sql