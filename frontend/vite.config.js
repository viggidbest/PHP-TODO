import { defineConfig } from 'vitest/config'
import vue from '@vitejs/plugin-vue'

export default defineConfig({
  plugins: [vue()],
  test: {
    globals: true,        // <-- enable describe, test, expect
    environment: 'jsdom',
     // <-- browser-like environment needed for Vue components
  },
})
