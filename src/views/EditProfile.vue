<template>
  <div>
    <AppHeader @search="handleSearch" />
    <div class="edit-profile">
      <h1>Edit Profile</h1>
      <form @submit.prevent="updateProfile">
        <div class="form-group">
          <label for="personname">Person Name:</label>
          <input type="text" v-model="profile.personname" id="personname" required />

          <label for="email">Email:</label>
          <input type="email" v-model="profile.email" id="email" required />
        </div>
        <div class="form-group">
          <label for="password">Password:</label>
          <input type="password" v-model="profile.password" id="password" />
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
          <label for="role">Role:</label>
          <select v-model="profile.role" id="role" required>
            <option value="admin">Admin</option>
            <option value="user">User</option>
          </select>
        </div>
        <button type="submit" class="btn-primary">Update Profile</button>
      </form>
    </div>
  </div>
  <AppFooter />
</template>

<script>
import AppHeader from '../components/AppHeader.vue';
import AppFooter from '../components/AppFooter.vue';

export default {
  name: 'EditProfile',
  data() {
    return {
      profile: {
        email: '',
        password: '',
        location: '',
        profilePicture: '',
        role: JSON.parse(localStorage.getItem('user')).role,
        personname: ''
      },
      isAdmin: false,
    };
  },
  components: {
    AppHeader,
    AppFooter
  },
  created() {
    this.checkAdmin();
    this.fetchProfile();
  },
  methods: {
    checkAdmin() {
      const user = JSON.parse(localStorage.getItem('user'));
      if (user && user.role === 'admin') {
        this.isAdmin = true;
      }
    },
    fetchProfile() {
      const user = JSON.parse(localStorage.getItem('user'));
      if (user) {
        this.profile = {
          email: user.email,
          password: '',
          location: user.location,
          profilePicture: user.profilepicture,
          role: user.role,
          personname: user.personname
        };
      }
    },
    updateProfile() {
      const profileData = {
        id: JSON.parse(localStorage.getItem('user')).id,
        email: this.profile.email,
        location: this.profile.location,
        profilepicture: this.profile.profilePicture,
        personname: this.profile.personname
      };

      if (this.profile.password) {
        profileData.password = this.profile.password;
      }

      fetch('http://44.218.60.222:8080/index.php?path=update-user', {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(profileData)
      })
      .then(response => response.json())
      .then(data => {
        if (data.status === 'User updated') {
          this.$router.push('/profile');
          //volver a cargar el user al localstorage
          localStorage.setItem('user', JSON.stringify(data.user));
        } else {
          alert('Error updating profile');
        }
      })
      .catch(error => {
        console.error('Error updating profile:', error);
      });
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
