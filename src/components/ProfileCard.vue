<template>
    <div class="profile-card">
      <div class="profile-header">
        <h2>{{ profile.email }}</h2>
        <img :src="profile.profilePicture" alt="Profile Picture" class="profile-picture" />
      </div>
      <div class="profile-details">
        <p><strong>Location:</strong> {{ profile.location }}</p>
        <p><strong>Level:</strong> {{ profile.nivell }}</p>
        <p><strong>Rate:</strong> {{ profile.rate }}</p>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    name: 'ProfileCard',
    props: {
      profile: Object
    }
  }
  </script>
  
  <style scoped>
  .profile-card {
    padding: 20px;
    max-width: 400px;
    margin: auto;
    background-color: #FFF;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  }
  
  .profile-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 20px;
  }
  
  .profile-picture {
    max-width: 100px;
    border-radius: 50%;
    border: 3px solid #0E2945;
  }
  
  .profile-details p {
    font-size: 16px;
    color: #0E2945;
  }
  
  .profile-details strong {
    font-weight: bold;
    color: #8292A4;
  }
  </style>
  