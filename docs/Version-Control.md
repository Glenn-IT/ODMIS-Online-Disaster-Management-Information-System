# ODMIS — Version Control & Rollout Schedule

## Rollout Schedule

| Version | Feature | Pages Unlocked | Pages Still Gated |
|---------|---------|---------------|------------------|
| v1.00 | Login / Register / Forgot Password (Public) | `login.php`, `register.php`, `forgot-password.php`, `index.php` | All admin & user pages |
| v1.01 | Admin: Dashboard | `admin/dashboard.php` | All except above |
| v1.02 | Admin: Incidents Management | `admin/incidents.php` | All except above |
| v1.03 | Admin: Evacuation Centers Management | `admin/evacuation.php` | All except above |
| v1.04 | Admin: Residents Management | `admin/residents.php` | All except above |
| v1.05 | Admin: Relief Operations Management | `admin/relief.php` | All except above |
| v1.06 | Admin: Reports & Exports | `admin/reports.php` | All except above |
| v1.07 | Admin: Settings | `admin/settings.php` | User pages only |
| v1.08 | User: Dashboard | `user/dashboard.php` | Remaining user pages |
| v1.09 | User: Alerts | `user/alerts.php` | Remaining user pages |
| v1.10 | User: Announcements | `user/announcements.php` | Remaining user pages |
| v1.11 | User: Evacuation Centers (view) | `user/evacuation-centers.php` | Remaining user pages |
| v1.12 | User: Report Incident | `user/report-incident.php` | `user/profile.php` |
| v1.13 | User: Profile — **Full System** | `user/profile.php` | None |

---

## Under Construction Strategy

All pages that are not yet presented use a PHP gate injected as the **very first line** of the file:

```php
<?php require_once '../components/under-construction.php'; ?>
```

`components/under-construction.php`:
- Defines `CURRENT_VERSION` (e.g. `'v1.00'`)
- Renders a full-page styled card with a hard-hat icon, version badge, and a **Log Out** button
- The Log Out button clears the JWT token from `localStorage` and redirects to `login.php`
- Calls `exit` at the bottom — no page content below it is ever rendered

To **unlock a page** for a version:
1. Remove the `require_once` gate line from that page's `.php` file
2. Update `CURRENT_VERSION` in `components/under-construction.php`

---

## Git Commands Per Version

```bash
# Stage the unlocked page + updated component
git add admin/dashboard.php components/under-construction.php

# Commit
git commit -m "feat: implement v1.01 - unlock Admin Dashboard"

# Tag and push
git tag v1.01
git push origin main
git push origin v1.01
```

---

## How Git Tags Work

Each version is a **permanent snapshot** tagged in Git.

- `git tag vX.XX` — creates a lightweight tag pointing to the current commit
- `git push origin vX.XX` — pushes the tag to GitHub (visible as a Release on GitHub)
- Tags never move. If you need to re-present v1.02, just checkout that tag:
  ```bash
  git checkout v1.02
  ```
- Return to latest with `git checkout main`

---

## GitHub Release Tags

| Version | Tag Name | Commit Hash |
|---------|----------|-------------|
| v1.00 | v1.00 | |
| v1.01 | v1.01 | |
| v1.02 | v1.02 | |
| v1.03 | v1.03 | |
| v1.04 | v1.04 | |
| v1.05 | v1.05 | |
| v1.06 | v1.06 | |
| v1.07 | v1.07 | |
| v1.08 | v1.08 | |
| v1.09 | v1.09 | |
| v1.10 | v1.10 | |
| v1.11 | v1.11 | |
| v1.12 | v1.12 | |
| v1.13 | v1.13 | |

---

## When a Prof / Client Requests Changes After a Presentation

```bash
# 1. Fix the issue on main
git checkout main
git add <changed-files>
git commit -m "feat: update [page] per feedback"
git push origin main

# 2. Delete the old tag locally and on remote
git tag -d vX.XX
git push origin :refs/tags/vX.XX

# 3. Re-create the tag pointing to the new commit
git tag vX.XX
git push origin vX.XX
```

---

## Filling in the Commit Hash Column

After all versions are pushed, run:

```bash
git tag | sort | xargs -I{} git log -1 --format="{} %H" {}
```

Paste the hashes into the GitHub Release Tags table above, then commit and push the updated docs.
