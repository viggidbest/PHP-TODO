import { mount } from '@vue/test-utils'
import App from './App.vue'

// ❌ Existing test (optional to keep)
// test('renders app', () => {
//   const wrapper = mount(App)
//   expect(wrapper.exists()).toBe(true)
// })

// 🔴 Intentional failing test
test('force pre-commit hook failure', () => {
  expect(true).toBe(false)  // This will always fail
})
