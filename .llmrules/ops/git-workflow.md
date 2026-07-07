# Git Workflow - MISA Kiosk API

Lightweight workflow for this Laravel + React repo. Keep history clean and tests passing.

---

## 1) Branches

- `main`: stable. No direct pushes; merge via PR after checks.
- `dev`: development branch. All feature branches merge here first.
- `feature/*`: new work. One task/feature per branch.
- `hotfix/*`: urgent fixes off `main`.
- `release/*`: optional for prepping a release; otherwise feature → dev → main via PR.

### Creating New Feature Branches

When requesting a new feature branch:

1. **If on `dev`**: Create the new branch directly from `dev`
2. **If on another branch**:
   - First: Merge the old branch into `dev` using **squash** (combine all commits into one)
   - Then: Create the new branch from `dev`
   - Then: Delete the old branch (unless you explicitly request to keep it)
3. **Exception**: Only skip this if you explicitly request otherwise

> 💡 **Rule**: Each feature should be independent and based on `dev`, not on another feature branch.

---

## 2) Commits

- Small, scoped commits with clear messages. Suggested prefixes: `[Feat]`, `[Fix]`, `[Refactor]`, `[Docs]`, `[Tests]`.
- No secrets or credentials in commits.
- Run `php artisan test` + `./vendor/bin/pint` for PHP changes; `npm run lint`/`npm run test` (or `npm run build`) for JS/TS changes. Note any skips with reasons.

---

## 3) Pull Requests

- Keep PRs small and focused; summarize changes and tests run.
- Required checks: Pint + Pest (or Laravel test suite) for backend; lint/test/build for frontend when touched. No external/custom scripts.
- Code review: at least one review; address feedback promptly.

---

## 4) Releases

- Versioning: `Major.Minor.Patch`. Update `CHANGELOG.md` (if maintained) and any version banners in docs/UI.
- Tag releases as `vX.Y.Z` after checks pass.

---

## 5) Quality Gates

- Tests green (`php artisan test`/Pest), lint/format (`pint`), JS/TS lint/tests/build when relevant.
- No new secrets or sensitive data added.
- Docs only if they add clear value (see `docs-rules.md`); keep them lean.

---

## 6) Hygiene

- Merge feature branches to `dev` using **squash** and delete them after successful merge.
- Rebase on latest `dev` before opening/merging PR to keep history clean.
- Avoid long-lived branches; ship small increments.
- No doc files in repo root; keep `.llmrules/` and `Docs/` organized.
