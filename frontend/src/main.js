import { createApp } from 'vue'
import App from './App.vue'

createApp(App).mount('#app')

// ❌ ESLint will fail: unused variable
const unusedVariable = 123
