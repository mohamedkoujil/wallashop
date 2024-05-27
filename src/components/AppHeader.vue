<template>
  <header class="header">
    <div class="header-top">
      <div class="header-logo">
        <router-link to="/"><img src="/logotipo-sinfondo.png" alt="WallaShop" class="logo"></router-link>
      </div>
      <div class="header-search">
        <input type="text" id="search-input" placeholder="Busca productos...">
      </div>
      <div id="usrLogedPfp" v-if="user" @click="navigateTo('/profile')">
        <p>{{ user.personname }}</p>
        <img :src="user.profilepicture" alt="Foto de perfil" class="pfp">
      </div>
      <div class="header-buttons">
        <button v-if="!user" id="register-btn" class="btn" @click="navigateTo('/register.html')">Registrarse</button>
        <button v-if="!user" id="login-btn" class="btn" @click="navigateTo('/login.html')">Iniciar Sesión</button>
        <div class="bar-wrapper" id="bar-wrapper" @click="toggleSidebar">
          <div class="bar"></div>
          <div class="bar"></div>
          <div class="bar"></div>
        </div>
        <div class="sidebar-menu" :style="{ display: sidebarVisible ? 'block' : 'none' }" id="sidebar-menu">
          <ul>
            <li><router-link to="/profile">Ver Perfil</router-link></li>
            <li><router-link to="/edit-profile">Editar Perfil</router-link></li>
            <li><a href="#" @click="logout">Cerrar Sesión</a></li>
          </ul>
        </div>
      </div>
    </div>
  </header>
</template>

<script>
export default {
  name: 'AppHeader',
  data() {
    return {
      user: JSON.parse(localStorage.getItem('user')) || null,
      sidebarVisible: false,
    };
  },
  methods: {
    navigateTo(path) {
      window.location.href = path;
    },
    toggleSidebar() {
      this.sidebarVisible = !this.sidebarVisible;
    },
    logout() {
      localStorage.removeItem('user');
      localStorage.removeItem('isLoggedIn');
      this.$router.push('/');
    }
  }
}
</script>

<style scoped>
.header {
  background-color: #FFF;
  height: 8em;
  padding: 10px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.header-top {
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.header-logo {
  flex: 0 0 auto;
}

.header-logo img {
  max-width: 150px;
  height: auto;
}

.header-search {
  flex: 1;
  display: flex;
  justify-content: center;
  align-items: center;
}

.header-search input[type="text"] {
  width: 80%;
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 4px;
  box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
}

.header-buttons {
  flex: 0 0 auto;
  display: flex;
  gap: 10px;
  position: relative;
}

.btn {
  background-color: #0E2945;
  color: white;
  border: none;
  padding: 10px;
  border-radius: 4px;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.btn:hover {
  background-color: #8292A4;
}

.bar-wrapper {
  display: inline-flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  cursor: pointer;
}

.bar {
  width: 25px;
  height: 3px;
  background-color: #0E2945;
  margin: 4px 0;
  transition: 0.4s;
}

.sidebar-menu {
  display: none;
  position: absolute;
  top: 50px;
  right: 0;
  width: 200px;
  background-color: #0E2945;
  border-radius: 4px;
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
  overflow: hidden;
  z-index: 1;
}

.sidebar-menu ul {
  padding: 10px;
  list-style: none;
}

.sidebar-menu li {
  font-size: 14px;
  text-transform: capitalize;
  margin: 10px 0;
}

.sidebar-menu a {
  color: #fff;
  text-decoration: none;
  padding: 10px;
  display: block;
  transition: background-color 0.3s ease;
}

.sidebar-menu a:hover {
  background-color: #8292A4;
}

.sidebar-menu.menu-open {
  display: block;
}

#logout-btn:hover {
  background-color: #ff4c4c;
  color: white;
}

#usrLogedPfp {
  display: none;
  justify-content: space-around;
  align-items: center;
  border-radius: 10px;
  padding: 5px;
  background-color: #0E2945;
  color: white;
  cursor: pointer;
  margin: 0 2em 0 -4em;
  width: 10em;
  transition: transform 0.3s ease;
}

#usrLogedPfp:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
}

#usrLogedPfp:active {
  transform: translateY(0);
}

#usrLogedPfp img {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  margin-right: 10px;
}
</style>
