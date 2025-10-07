# PHP + Vue TODO (Governed)
Includes CI/CD workflows, CODEOWNERS, PR template, and SonarCloud.

## Run
Backend: `cd backend && php -S localhost:8080`
Frontend: `cd frontend && npm install && npm run dev`

## Checks
- unit-tests: PHPUnit + Vitest
- static-checks: phpstan + phpcs
- build: Vite build
- sonar: SonarCloud scan (needs SONAR_TOKEN, sonar-project.properties)

## Branch protection
`OWNER=your-org REPO=your-repo ./setup-branch-protection.sh`
