<template>
    <div>
      <h2>Register</h2>
      <form @submit.prevent="register">
        <input type="text" v-model="username" placeholder="Username" required>
        <input type="password" v-model="password" placeholder="Password" required>
        <button type="submit">Register</button>
      </form>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        username: '',
        password: ''
      };
    },
    methods: {
      register() {
        // Lógica para registrar al usuario mediante API
      }
    }
  };
  </script>
  