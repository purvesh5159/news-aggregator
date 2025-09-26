📰 News Aggregator Backend (Laravel 12.4)
Overview

This is a backend-only News Aggregator built with Laravel 12.4.
It fetches and stores articles from multiple providers and exposes API endpoints for search, filtering, and user preferences.

Framework: Laravel 12.4 (PHP 8.1+)

Database: MySQL

Providers: NewsAPI, The Guardian API, New York Times API

Patterns: Repository, Service, Dependency Inversion

Principles: SOLID, DRY, KISS

🚀 Features

Fetches live articles from 3 providers

Normalizes and deduplicates articles in DB

Scheduled background updates (news:fetch)

API endpoints for:

Listing/searching articles (with filters: keyword, date, source, category, author)

Retrieving single articles

Listing distinct sources

Saving user preferences (sources, categories, authors)

⚙️ Installation
git clone https://github.com/purvesh5159/news-aggregator.git
cd news-aggregator
composer install
cp .env.example .env
php artisan key:generate


Set DB and API keys in .env:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=news_aggregator
DB_USERNAME=root
DB_PASSWORD=

NEWSAPI_KEY=45d9a2a4c276421185fdb6f442531ce7
GUARDIAN_KEY=4ff1c7b7-f603-4715-9519-4bffd791e204
NYT_KEY=bQCDa4RqaPrRIAbHph4Kv45MgDpE3Hri


Run migrations:

php artisan migrate

📡 Data Fetching

Fetch articles manually:

php artisan news:fetch


Schedule automatic fetching in app/Console/Kernel.php:

$schedule->command('news:fetch')->everyThirtyMinutes()->withoutOverlapping();

🔌 API Endpoints

Base URL: http://127.0.0.1:8000/api

1. List Articles
GET /articles


Query params:

q, source, category, author, date_from, date_to, per_page, page, user_id

Example:

GET /api/articles?q=ai&source=nyt&category=business&date_from=2025-05-01&per_page=10

2. Single Article
GET /articles/{id}

3. Sources
GET /sources

4. Save Preferences
POST /preferences


Body (JSON):

{
  "user_id": 1,
  "sources": ["guardian", "nyt"],
  "categories": ["technology", "science"],
  "authors": ["Jane Doe"]
}

✅ Example Response (Articles)
{
  "data": [
    {
      "id": 55,
      "source_code": "nyt",
      "title": "Trump Will Slap Tariffs on Imported Drugs, Trucks and Household Furnishings",
      "author": "By Ana Swanson, Rebecca Robbins and Peter Eavis",
      "url": "https://www.nytimes.com/2025/09/25/business/economy/trump-tariffs.html",
      "summary": "The president said his tariffs would range from 25 to 100 percent...",
      "category": "business",
      "published_at": "2025-09-25T20:37:43.000000Z"
    }
  ],
  "links": { "first": "...", "last": "...", "prev": null, "next": "..." },
  "meta": { "current_page": 1, "per_page": 10, "total": 100 }
}

📦 Deliverables

Laravel project with migrations, models, services, repositories

Command news:fetch

API endpoints for articles, sources, preferences

.env.example with required keys

Postman collection (NewsAggregator.postman_collection.json)

📑 Principles Applied

SOLID — Provider interfaces (DIP), Repository pattern, SRP in services

DRY — Shared fetch/store logic centralized

KISS — Clear separation of concerns, minimal boilerplate

Robustness — Deduplication on URL, scheduler for updates, error handling

🧭 Example Postman Usage

Import NewsAggregator.postman_collection.json into Postman

Test endpoints: /articles, /articles/{id}, /sources, /preferences