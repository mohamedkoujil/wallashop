<template>
    <div v-if="user">
      <h2>{{ user.name }}</h2>
      <img :src="user.profilePicture" alt="Profile Picture">
    </div>
  </template>
  
  <script>
  export default {
    props: {
      user: Object
    }
  };
  </script>
  