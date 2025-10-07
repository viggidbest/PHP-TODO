// import { mount } from '@vue/test-utils'
// import { describe, it, expect, vi } from 'vitest'
// import App from './App.vue'
// import axios from 'axios'
// vi.mock('axios')
// describe('App.vue', () => {
//   it('loads and displays todos', async () => {
//     axios.get.mockResolvedValue({ data: [{ id: 1, title: 'A', done: false }] })
//     const wrapper = mount(App); await Promise.resolve(); await Promise.resolve()
//     expect(wrapper.text()).toContain('A')
//   })
//   it('adds a todo', async () => {
//     axios.get.mockResolvedValue({ data: [] })
//     axios.post.mockResolvedValue({ data: { id: 2, title: 'New', done: false } })
//     const wrapper = mount(App); await Promise.resolve(); await Promise.resolve()
//     await wrapper.find('input').setValue('New')
//     await wrapper.find('form').trigger('submit.prevent')
//     await Promise.resolve()
//     expect(wrapper.text()).toContain('New')
//   })
// })