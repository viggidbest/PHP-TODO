import { defineConfig } from 'vitest/config'
import vue from '@vitejs/plugin-vue'

export default defineConfig({
  plugins: [vue()],
  test: {
    environment: 'jsdom',
    coverage: {
      reporter: ['text', 'lcov', 'html'], // text summary + LCOV + HTML report
      reportsDirectory: './coverage',     // output folder
    },
  },
})
