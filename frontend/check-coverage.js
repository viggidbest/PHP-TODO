import { readFileSync, existsSync } from 'fs'

const coveragePath = './frontend/coverage/coverage-summary.json'  // update if needed

if (!existsSync(coveragePath)) {
  console.error('⚠️ Coverage summary not found at:', coveragePath)
  process.exit(1)
}

let coverageSummary
try {
  coverageSummary = JSON.parse(readFileSync(coveragePath, 'utf-8'))
} catch (err) {
  console.error('⚠️ Failed to parse coverage summary JSON:', err)
  process.exit(1)
}

const coveragePercent = coverageSummary?.total?.lines?.pct
if (coveragePercent === undefined) {
  console.error('⚠️ Coverage percentage not found in summary')
  process.exit(1)
}

console.log(`Frontend coverage: ${coveragePercent}%`)

const COVERAGE_THRESHOLD = 40

if (coveragePercent < COVERAGE_THRESHOLD) {
  console.error(`❌ Coverage ${coveragePercent}% is below threshold of ${COVERAGE_THRESHOLD}%!`)
  process.exit(1)
}

process.exit(0)
