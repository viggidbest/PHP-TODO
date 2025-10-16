import { readFileSync, existsSync } from 'fs'

const coveragePath = './coverage/coverage-summary.json'

if (!existsSync(coveragePath)) {
  console.error('⚠️ Coverage summary not found.')
  process.exit(1)
}

const coverageSummary = JSON.parse(readFileSync(coveragePath, 'utf-8'))
const coveragePercent = coverageSummary.total.lines.pct

console.log(`Frontend coverage: ${coveragePercent}%`)

const COVERAGE_THRESHOLD = 40

if (coveragePercent < COVERAGE_THRESHOLD) {
  console.error(`❌ Coverage ${coveragePercent}% is below threshold of ${COVERAGE_THRESHOLD}%!`)
  process.exit(1)
}

process.exit(0)
