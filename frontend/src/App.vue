<template>
  <main class="container">
    <h1>TODOs</h1>
    <form @submit.prevent="add">
      <input v-model="newTitle" placeholder="Add a task" />
      <button>Add</button>
    </form>
    <ul>
      <li v-for="t in todos" :key="t.id">
        <label>
          <input type="checkbox" v-model="t.done" @change="toggle(t)" />
          <span :style="{textDecoration: t.done ? 'line-through' : 'none'}">{{ t.title }}</span>
        </label>
        <button @click="remove(t)">x</button>
      </li>
    </ul>
  </main>
</template>
<script setup>
import axios from 'axios'
import { ref, onMounted } from 'vue'
const todos = ref([])
const newTitle = ref('')
async function load(){ const { data } = await axios.get('/api/todos'); todos.value = data }
async function add(){ if(!newTitle.value.trim()) return; const { data } = await axios.post('/api/todos',{ title:newTitle.value }); todos.value.unshift(data); newTitle.value='' }
async function toggle(t){ const { data } = await axios.put(`/api/todos/${t.id}`,{ done:t.done }); Object.assign(t,data) }
async function remove(t){ await axios.delete(`/api/todos/${t.id}`); todos.value = todos.value.filter(x=>x.id!==t.id) }
onMounted(load)
</script>
<style>
.container { max-width: 600px; margin: 2rem auto; font-family: system-ui, sans-serif; }
form { display:flex; gap: .5rem; margin-bottom: 1rem; }
input[type="text"], input { flex: 1; padding: .5rem; }
button { padding: .5rem .75rem; }
ul { list-style: none; padding: 0; }
li { display: flex; gap: .5rem; align-items: center; margin: .25rem 0; }
</style>