<template>
  <div>
    <AppHeader @search="handleSearch" />
    <div class="edit-profile">
      <h1>Edit Profile</h1>
      <form @submit.prevent="updateProfile">
        <div class="form-group">
          <label for="email">Email:</label>
          <input type="email" v-model="profile.email" id="email" required />
        </div>
        <div class="form-group">
          <label for="password">Password:</label>
          <input type="password" v-model="profile.password" id="password" required />
        </div>
        <div class="form-group">
          <label for="location">Location:</label>
          <input type="text" v-model="profile.location" id="location" required />
        </div>
        <div class="form-group">
          <label for="profilePicture">Profile Picture URL:</label>
          <input type="text" v-model="profile.profilePicture" id="profilePicture" required />
        </div>
        <div class="form-group" v-if="isAdmin">
          <label for="nivell">Level:</label>
          <select v-model="profile.nivell" id="nivell" required>
            <option value="admin">Admin</option>
            <option value="user">User</option>
          </select>
        </div>
        <button type="submit" class="btn-primary">Update Profile</button>
      </form>
    </div>
  </div>
</template>

<script>
import AppHeader from '../components/AppHeader.vue';

export default {
  name: 'EditProfile',
  data() {
    return {
      profile: {
        email: '',
        password: '',
        location: '',
        profilePicture: '',
        nivell: 'user'
      },
      isAdmin: false,
    };
  },
  components: {
    AppHeader
  },
  created() {
    this.checkAdmin();
    this.fetchProfile();
  },
  methods: {
    checkAdmin() {
      const user = JSON.parse(localStorage.getItem('user'));
      if (user && user.nivell === 'admin') {
        this.isAdmin = true;
      }
    },
    fetchProfile() {
      const user = JSON.parse(localStorage.getItem('user'));
      if (user) {
        this.profile = {
          email: user.email,
          password: user.password,
          location: user.location,
          profilePicture: user.profilepicture,
          nivell: user.nivell
        };
      }
    },
    updateProfile() {
      fetch('http://http://54.197.171.146:8080/index.php?path=update-user', {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(this.profile)
      })
      .then(response => response.json())
      .then(data => {
        if (data.status === 'success') {
          this.$router.push('/profile');
        } else {
          alert('Error updating profile');
        }
      })
      console.log("Profile updated:", this.profile);
    }
  }
};
</script>

<style scoped>
.edit-profile {
  max-width: 600px;
  margin: 50px auto;
  padding: 20px;
  background: #fff;
  border-radius: 8px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

h1 {
  text-align: center;
  color: #0E2945;
  margin-bottom: 20px;
}

form {
  display: flex;
  flex-direction: column;
}

.form-group {
  margin-bottom: 15px;
}

label {
  font-weight: bold;
  color: #0E2945;
}

input, select {
  width: 100%;
  padding: 10px;
  margin-top: 5px;
  border: 1px solid #ccc;
  border-radius: 4px;
  box-sizing: border-box;
}

input:focus, select:focus {
  border-color: #0E2945;
  outline: none;
  box-shadow: 0 0 5px rgba(14, 41, 69, 0.5);
}

button {
  padding: 10px 20px;
  background-color: #0E2945;
  color: #fff;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  transition: background-color 0.3s ease;
  margin-top: 20px;
}

button:hover {
  background-color: #8292A4;
}
</style>
