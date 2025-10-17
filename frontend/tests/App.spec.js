import { mount } from '@vue/test-utils'
import App from '../src/App.vue'
import axios from 'axios'
import { vi } from 'vitest'

// ✅ Mock axios globally
vi.mock('axios', () => ({
  default: {
    get: vi.fn(),
    post: vi.fn(),
    put: vi.fn(),
    delete: vi.fn()
  }
}))

describe('App.vue TODO App', () => {
  beforeEach(() => {
    // Reset all mock calls before each test
    vi.clearAllMocks()

    // ✅ Common mock for GET /api/todos on mount
    axios.get.mockResolvedValue({ data: [] })
  })

  test('renders header and input', () => {
    const wrapper = mount(App)
    expect(wrapper.find('h1').text()).toBe('TODOs')
    expect(wrapper.find('input').exists()).toBe(true)
    expect(wrapper.find('button').text()).toBe('Add')
  })

  test('loads todos on mount', async () => {
    const todosData = [{ id: 1, title: 'Test task', done: false }]
    axios.get.mockResolvedValueOnce({ data: todosData })

    const wrapper = mount(App)
    // Wait for onMounted API call
    await new Promise((r) => setTimeout(r, 0))

    const items = wrapper.findAll('li')
    expect(items.length).toBe(1)
    expect(wrapper.text()).toContain('Test task')
  })

  test('adds a new todo', async () => {
    axios.post.mockResolvedValueOnce({
      data: { id: 2, title: 'New Task', done: false }
    })

    const wrapper = mount(App)
    const input = wrapper.find('input')

    await input.setValue('New Task')
    await wrapper.find('form').trigger('submit.prevent')
    await new Promise((r) => setTimeout(r, 0))

    expect(wrapper.text()).toContain('New Task')
  })

  test('does not add empty todo', async () => {
    const wrapper = mount(App)
    await wrapper.find('form').trigger('submit.prevent')
    expect(wrapper.findAll('li').length).toBe(0)
  })

  test('toggles a todo', async () => {
    const todo = { id: 1, title: 'Toggle Task', done: false }

    axios.get.mockResolvedValueOnce({ data: [todo] })
    axios.put.mockResolvedValueOnce({ data: { ...todo, done: true } })

    const wrapper = mount(App)
    await new Promise((r) => setTimeout(r, 0))

    const checkbox = wrapper.find('input[type="checkbox"]')
    await checkbox.setChecked(true)
    await new Promise((r) => setTimeout(r, 0))

    const span = wrapper.find('span')
    expect(span.element.style.textDecoration).toBe('line-through')
  })

   //This block to fail the pipeline
  test("intentional failure test", () => {
    expect(1 + 1).toBe(3); // This will always fail
  });
})
