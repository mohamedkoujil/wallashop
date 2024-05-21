<template>
  <div class="profile-page">
    <h1>Profile Page</h1>
    <div v-if="profile">
      <p><strong>Email:</strong> {{ profile.email }}</p>
      <p><strong>Location:</strong> {{ profile.location }}</p>
      <p><strong>Profile Picture:</strong></p>
      <img :src="profile.profilePicture" alt="Profile Picture" />
      <p><strong>Level:</strong> {{ profile.nivell }}</p>
      <p><strong>Rate:</strong> {{ profile.rate }}</p>
    </div>
    <div v-else>
      <p>Loading...</p>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ProfilePage',
  data() {
    return {
      profile: null,
      apiUrl: 'http://your-api-endpoint/profile', // Reemplaza con tu URL de la API
    };
  },
  methods: {
    fetchProfile() {
      fetch(this.apiUrl)
        .then(response => response.json())
        .then(data => {
          this.profile = data;
        })
        .catch(error => {
          console.error('Error fetching profile:', error);
        });
    },
  },
  mounted() {
    this.fetchProfile();
  },
};
</script>

<style scoped>
.profile-page {
  padding: 20px;
}
img {
  max-width: 150px;
  border-radius: 50%;
}
</style>
