# Implementation Plan: Account Security & Dashboard Features (7 items)

Backend: Laravel 8 + Sanctum API + Jetstream/Fortify + Bouncer (multi-tenant via `company_id`).

Decisions confirmed:
- **2FA**: Email OTP primary; Fortify TOTP stays for the web admin panel.
- **Delete account**: Scheduled (7-day grace) + password confirmation, cancellable.
- **Team roles**: Bouncer roles `admin`, `finance`, `viewer` (displayed Admin/Finance/Viewer).
- **Audit log**: Full — extend `audit_trail` schema, add logging service + hooks, expose API.

---

## Phase 0 — Foundation

1. Route `GET /api/user` → `Api\UserController@getUser` (replace inline closure). Response: `{ status, message, data: user, settings }`.
2. Seed `settings` row (id=1). Add `funding_fee` column.
3. Read-first: `Api/AuthenticateController` (login flow), users migration, live `audit_trail` schema, `app/Console/Kernel.php`, `app/Mail/*` conventions.

## Feature 1 — 2FA (email OTP primary)

- Migration `two_factor_codes` (user_id, hashed code, expires_at, used_at). Add `email_2fa_enabled` to users.
- `app/Mail/TwoFactorCodeMail.php`.
- `app/Http/Controllers/Api/TwoFactorController.php`:
  - `POST /api/two-factor/enable` — email 6-digit code (10 min expiry)
  - `POST /api/two-factor/confirm {code}` — verify → enable
  - `POST /api/two-factor/resend` — new code (60s throttle)
  - `DELETE /api/two-factor/disable {password}` — disable
  - `POST /api/two-factor/verify-login {username, code, device_name}` — issue token
- Login flow: if `email_2fa_enabled`, return `{ status: 2, requires_2fa: true }` (no token) + email code.
- `two_factor_enabled` appended to User → visible in `/api/user`.

## Feature 2 — Delete Account (scheduled)

- Add `deletion_requested_at`, `deletion_scheduled_for` to users.
- `POST /api/account/delete-request {password}` / `DELETE /api/account/delete-request` (cancel).
- Command `account:process-deletions` (daily) purges expired accounts + related rows. Login blocked when scheduled.

## Feature 3 — Profile Photo

- Override `profilePhotoUrl()` accessor on User (files served at `/api/user/image/{file}`).
- Harden `uploaddp`; add `POST /api/remove-photo`.

## Feature 4 — Notifications API

- `GET /api/notifications` (paginated + unread_count), `POST /api/notifications/{id}/read`, `POST /api/notifications/read-all`, `GET /api/notifications/unread-count`.
- `unread_notifications_count` appended to User.

## Feature 5 — Team Members

- Seeder: Bouncer roles `admin`, `finance`, `viewer`.
- `GET /api/team-members`, `POST /api/team-members/invite {email, role}`, `DELETE /api/team-members/{id}` (guards: no self-removal, keep ≥1 admin).

## Feature 6 — Audit Log

- Defensive migration for `audit_trail` (create if missing / add columns): `role`, `description`, `subject_type`, `subject_id`, `severity` (info|warning|critical), `ip_address`, `user_agent`.
- `app/Services/AuditService.php` + hooks on auth/2FA/deletion/team/photo actions.
- `GET /api/audit-logs` with filters (date range, severity, search) + pagination. Fields: actor (name/email/role), action, description, subject, severity, ip, browser, created_at.

## Feature 7 — Wallet Top-up Fee

- Add `funding_fee` decimal(10,2) default 80.00 to `settings`; returned via `/api/user` → `settings.funding_fee`.

## Validation

- `php artisan migrate`, `php artisan db:seed`, `php artisan route:list`, `php artisan test`, `php artisan schedule:list`.
- PHPUnit feature tests under `tests/Feature/Api/` per feature.

## Build order

Phase 0 → Features 3+4 → 7 → 5 → 1 → 2 → 6.
