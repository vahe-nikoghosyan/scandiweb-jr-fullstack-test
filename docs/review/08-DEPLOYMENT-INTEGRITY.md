# Deployment Integrity Report

## 1. Service Configuration

### Backend (PHP/GraphQL)

| Item | Value | Source |
|------|-------|--------|
| Builder | Nixpacks | `backend/railway.json`, `nixpacks.toml` |
| Start | `php -S 0.0.0.0:$PORT -t public` | railway.json |
| Document root | `public/` | GraphQL at `/graphql` |

**Status:** ✅ Backend configured for Railway.

---

### Frontend (Vite/React)

| Item | Value |
|------|-------|
| Build | `npm run build` |
| Start | `serve dist -s -l ${PORT:-3000}` |

**Status:** ✅ Frontend configured for Railway.

---

### Database (MySQL)

| Item | Value |
|------|-------|
| URL vars | `MYSQL_URL`, `DATABASE_URL` |
| Individual vars | `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, `DB_PASSWORD` |

**Status:** ✅ Env vars; no credentials in code.

---

## 2. Environment Variables

- **Backend:** `MYSQL_URL`/`DATABASE_URL`, `ALLOWED_ORIGIN`
- **Frontend:** `VITE_GRAPHQL_URI` (build time)

---

## 3. Domain, Ports, HTTPS

- Railway `*.up.railway.app` with TLS.
- 165.227.98.170 = Scandiweb AutoQA tool (enter your app URL).

---

## 4. Summary

| Service | Config | Env vars |
|---------|--------|----------|
| Backend (PHP) | ✅ | ✅ |
| Frontend (React) | ✅ | ✅ |
| Database (MySQL) | ✅ | ✅ |
