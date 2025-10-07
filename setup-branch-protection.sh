#!/usr/bin/env bash
# Usage: OWNER=your-org REPO=your-repo ./setup-branch-protection.sh
set -euo pipefail
: "${OWNER:?OWNER is required}"; : "${REPO:?REPO is required}"; BRANCH="${BRANCH:-main}"
CHECKS='["build","unit-tests","static-checks"]'
gh api -X PUT repos/$OWNER/$REPO/branches/$BRANCH/protection   -f required_status_checks.strict=true   -f required_status_checks.contexts="$CHECKS"   -f enforce_admins=true   -f required_pull_request_reviews.dismiss_stale_reviews=true   -f required_pull_request_reviews.require_code_owner_reviews=true   -f required_pull_request_reviews.required_approving_review_count=1   -f restrictions= -H "Accept: application/vnd.github+json"
echo "Branch protection configured for $OWNER/$REPO@$BRANCH"
