<template>
  <header class="header">
    <div class="header-top">
      <div class="header-logo">
        <router-link to="/"><img src="/logotipo-sinfondo.png" alt="WallaShop" class="logo"></router-link>
      </div>
      <div class="header-search">
        <input type="text" id="search-input" placeholder="Busca productos...">
      </div>
      <div v-if="user" class="header-user" @click="toggleMenu" @mouseleave="toggleMenuMouseover">
        <p>{{ user.personname }}</p>
        <img :src="user.profilepicture" alt="Foto de perfil" class="pfp">
        <div class="dropdown-menu" v-if="menuOpen">
          <ul>
            <li><router-link to="/profile">Ver Perfil</router-link></li>
            <li><router-link to="/edit-profile">Editar Perfil</router-link></li>
            <li><a href="#" @click="logout">Cerrar Sesión</a></li>
          </ul>
        </div>
      </div>
      <div v-else class="header-buttons">
        <button id="register-btn" class="btn" @click="navigateTo('/register.html')">Registrarse</button>
        <button id="login-btn" class="btn" @click="navigateTo('/login.html')">Iniciar Sesión</button>
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
      menuOpen: false
    };
  },
  methods: {
    navigateTo(path) {
      window.location.href = path;
    },
    toggleMenu() {
      this.menuOpen = !this.menuOpen;
    },
    toggleMenuMouseover() {
      if(this.menuOpen) {
        this.menuOpen = false;
      }
    },
    logout() {
      localStorage.removeItem('user');
      this.user = null;
      this.$router.push('/');
    }
  },
  watch: {
    $route() {
      this.user = JSON.parse(localStorage.getItem('user')) || null;
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

.header-user {
  display: flex;
  align-items: center;
  justify-content: space-around;
  width: 120px;
  cursor: pointer;
  background-color: #0E2945;
  color: white;
  padding: 5px;
  border-radius: 10px;
  transition: transform 0.3s ease;
}

.header-user:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
}

.header-user img {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  margin-right: 10px;
}

.dropdown-menu {
  position: absolute;
  right: 0%;
  top: 87%;
  width: 100%;
  background-color: #0E2945;
  border-radius:  0 0 10px 10px;
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
  z-index: 1000;
  overflow: hidden;
}

.dropdown-menu ul {
  list-style: none;
  margin: 0;
  padding: 0;
}

.dropdown-menu li {
  padding: 10px;
}

.dropdown-menu li:hover {
  background-color: #8292A4;
}

.dropdown-menu a {
  color: white;
  text-decoration: none;
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
</style>
