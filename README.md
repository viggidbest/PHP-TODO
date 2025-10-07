# PHP + Vue.js TODO App (Sample)

A minimal full-stack sample:
- **Backend:** Plain PHP (PDO + SQLite). Provides CRUD REST API under `/api/todos`.
- **Frontend:** Vue 3 + Vite. Simple Todo UI.
- **Tests:** PHPUnit (backend) and Vitest (frontend).

## Quick start

### Backend
```bash
cd backend
php -S localhost:8080
# database file todo.sqlite3 will be created on first write
```

### Frontend
```bash
cd frontend
npm install
npm run dev   # open the printed URL (default http://localhost:5173)
```

> Make sure backend runs on 8080. The frontend dev server proxies `/api` to `http://localhost:8080`.

### Tests

**Backend (PHPUnit):**
```bash
cd backend
composer install
./vendor/bin/phpunit
```

**Frontend (Vitest):**
```bash
cd frontend
npm install
npm test
```

## Structure
- backend/
  - index.php (router + API)
  - TodoRepository.php (SQLite operations)
  - tests/ (PHPUnit tests)
  - composer.json, phpunit.xml
- frontend/
  - index.html, src/
  - vitest setup and a component test
  - vite.config.js (proxy `/api` to backend)
