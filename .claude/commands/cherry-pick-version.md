# Cherry-Pick Fix Into a Version Tag

Use this when you have already fixed something on `main` and need that fix applied to a specific version tag (e.g. `v1.00`) **without** pulling in all the other commits that main has accumulated.

## What this does

1. Looks up the commit the version tag currently points to
2. Checks out that commit (detached HEAD)
3. Cherry-picks the latest fix commit from main onto it
4. Deletes and re-creates the tag at the new cherry-picked commit
5. Pushes the updated tag to GitHub
6. Returns to main
7. Updates the commit hash in `docs/Version-Control.md`
8. Commits and pushes the doc update

## Usage

```
/cherry-pick-version
```

You will be asked: **which version tag** and **which commit** to cherry-pick (defaults to latest commit on main).

---

## Steps to perform

Ask the user:
- Which version tag to update (e.g. `v1.00`)
- Which commit hash to cherry-pick (default: latest commit on `main` — run `git log -1 --format="%H"` to get it)

Then run the following in order:

### 1. Get the current tag commit and the fix commit
```bash
git log -1 --format="%H" <TAG>        # where the tag currently points
git log -1 --format="%H" main         # the fix to cherry-pick
```

### 2. Checkout the tag commit (detached HEAD)
```bash
git checkout <TAG>
```

### 3. Cherry-pick the fix onto it
```bash
git cherry-pick <FIX_COMMIT_HASH>
```
If there is a conflict, resolve it, then `git cherry-pick --continue`.

### 4. Delete old tag and re-create at the new cherry-picked commit
```bash
git tag -d <TAG>
git push origin :refs/tags/<TAG>
git tag <TAG>
git push origin <TAG>
```

### 5. Return to main
```bash
git checkout main
git remote set-head origin --delete
```

### 6. Get the new tag hash and update docs/Version-Control.md
```bash
git log -1 --format="%H" <TAG>
```
Find the row for `<TAG>` in the `## GitHub Release Tags` table in `docs/Version-Control.md` and replace the hash.

### 7. Commit and push the doc update
```bash
git add docs/Version-Control.md
git commit -m "docs: update <TAG> commit hash after cherry-pick"
git push origin main
```

---

## Important rules

- **Never** just re-point the tag to `main` HEAD — main has all versions unlocked. The tag must stay on its own isolated commit line so checking it out shows only the correct pages.
- Always cherry-pick onto the **existing tag commit**, not onto main.
- After the cherry-pick, always update `docs/Version-Control.md` so the hash table stays accurate.
- If two fixes need to go into the same tag, cherry-pick them one at a time in the order they were committed on main.
