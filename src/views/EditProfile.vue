<template>
  <div class="edit-profile">
    <h1>Edit Profile</h1>
    <form @submit.prevent="updateProfile">
      <div>
        <label for="email">Email:</label>
        <input type="email" v-model="profile.email" id="email" required>
      </div>
      <div>
        <label for="password">Password:</label>
        <input type="password" v-model="profile.password" id="password" required>
      </div>
      <div>
        <label for="location">Location:</label>
        <input type="text" v-model="profile.location" id="location" required>
      </div>
      <div>
        <label for="profilePicture">Profile Picture URL:</label>
        <input type="text" v-model="profile.profilePicture" id="profilePicture" required>
      </div>
      <div>
        <label for="nivell">Level:</label>
        <input type="number" v-model="profile.nivell" id="nivell" required>
      </div>
      <button type="submit">Update Profile</button>
    </form>
  </div>
</template>

<script>
export default {
  name: 'EditProfile',
  data() {
    return {
      profile: {
        email: '',
        password: '',
        location: '',
        profilePicture: '',
        nivell: 0
      }
    };
  },
  methods: {
    updateProfile() {
      // Add logic to handle profile update
      console.log("Profile updated:", this.profile);
    }
  }
};
</script>

<style scoped>
.edit-profile {
  padding: 20px;
}
form div {
  margin-bottom: 10px;
}
label {
  display: inline-block;
  width: 150px;
}
input {
  width: 200px;
}
button {
  padding: 10px 20px;
}
</style>
