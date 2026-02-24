# Scandiweb Test Assignment

## Backend Setup
1. Import database: `mysql -u root -p < backend/database/schema.sql`
2. Copy `backend/.env.example` to `backend/.env`, configure database
3. Import data: `php backend/scripts/import-data.php`
4. Run: `php -S localhost:8000 -t backend/public`

## Frontend Setup
1. `cd frontend && npm install`
2. Copy `frontend/.env.example` to `frontend/.env`, set GraphQL endpoint
3. Run: `npm run dev`

## Testing
- Automated tests: Visit http://165.227.98.170/
- GraphQL Playground: http://localhost:8000/graphql
