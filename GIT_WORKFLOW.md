# Git Workflow Documentation

## Branching Strategy

We follow a feature-branch workflow:

1. **Main branch**: `main` - Production-ready code
2. **Development branch**: `develop` - Integration branch for features
3. **Feature branches**: `feature/*` - Individual feature development
4. **Hotfix branches**: `hotfix/*` - Urgent production fixes
5. **Release branches**: `release/*` - Preparation for releases

## Creating a New Feature

1. Create a new branch from `develop`:
   ```bash
   git checkout develop
   git pull origin develop
   git checkout -b feature/your-feature-name
   ```

2. Make your changes and commit frequently:
   ```bash
   git add .
   git commit -m "feat: add new feature component"
   ```

3. Push your branch to remote:
   ```bash
   git push origin feature/your-feature-name
   ```

4. Create a Pull Request for review

## Commit Message Convention

We follow the Conventional Commits specification:

```
type(scope): description

[body]

[footer]
```

### Types:
- `feat`: New feature
- `fix`: Bug fix
- `docs`: Documentation changes
- `style`: Code style changes (formatting, missing semicolons, etc.)
- `refactor`: Code refactoring
- `perf`: Performance improvements
- `test`: Adding or updating tests
- `build`: Build system or external dependencies
- `ci`: CI configuration files and scripts
- `chore`: Other changes that don't modify src or test files

### Examples:
```
feat(auth): add forgot password functionality

- Implement dialog-based password reset
- Add CSRF protection to forms
- Integrate with mailcatcher for email testing
```

```
fix(css): resolve responsive layout issues

- Fix mobile navigation menu overflow
- Adjust grid spacing for small screens
- Update media queries for better breakpoints
```

## Pull Request Process

1. Ensure your branch is up to date with `develop`:
   ```bash
   git checkout develop
   git pull origin develop
   git checkout feature/your-feature-name
   git merge develop
   ```

2. Resolve any conflicts

3. Push your changes:
   ```bash
   git push origin feature/your-feature-name
   ```

4. Create Pull Request with:
   - Clear title describing the changes
   - Detailed description of what was implemented
   - Screenshots if UI changes are involved
   - Testing instructions

## Code Review Guidelines

Reviewers should check for:
- Code quality and best practices
- Proper error handling
- Security considerations
- Performance implications
- Test coverage
- Documentation updates

## Merging Pull Requests

1. All PRs require at least one approval
2. PRs should pass all CI checks
3. Use "Squash and merge" for clean commit history
4. Delete branch after merging

## Handling Conflicts

When you encounter merge conflicts:

1. Fetch the latest changes:
   ```bash
   git fetch origin
   ```

2. Merge the target branch:
   ```bash
   git merge origin/develop
   ```

3. Resolve conflicts in your editor

4. Stage resolved files:
   ```bash
   git add .
   ```

5. Complete the merge:
   ```bash
   git commit
   ```

## Stashing Changes

To temporarily save your work:

```bash
# Stash changes
git stash

# Stash with description
git stash push -m "Work in progress on feature X"

# List stashes
git stash list

# Apply most recent stash
git stash apply

# Apply specific stash
git stash apply stash@{1}
```

## Useful Git Commands

```bash
# View commit history
git log --oneline

# View branch structure
git log --graph --oneline --all

# View changes in working directory
git diff

# View changes in staging area
git diff --staged

# View specific commit
git show COMMIT_HASH

# Undo last commit (keep changes)
git reset --soft HEAD~1

# Undo last commit and changes
git reset --hard HEAD~1

# Revert a specific commit
git revert COMMIT_HASH

# Cherry-pick a commit
git cherry-pick COMMIT_HASH
```

## Best Practices

1. **Commit Frequently**: Make small, focused commits
2. **Write Meaningful Commit Messages**: Follow the convention above
3. **Keep Branches Small**: Focus on one feature per branch
4. **Update Regularly**: Keep your branch up to date with `develop`
5. **Review Before Pushing**: Check your changes before pushing
6. **Delete Merged Branches**: Keep the repository clean
7. **Use Descriptive Branch Names**: Make it clear what the branch is for

## Common Issues and Solutions

### Detached HEAD State
```bash
# Return to a branch
git checkout BRANCH_NAME
```

### Accidental Commit to Wrong Branch
```bash
# Move commit to correct branch
git log --oneline  # Find commit hash
git checkout correct-branch
git cherry-pick COMMIT_HASH
git checkout wrong-branch
git reset --hard HEAD~1
```

### Accidentally Added Files
```bash
# Remove from staging (keep files)
git reset FILE_NAME

# Remove from staging and disk
git rm --cached FILE_NAME
```