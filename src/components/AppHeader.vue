<template>
    <header class="header">
      <div class="header-top">
        <div class="header-logo">
          <router-link to="/"><img src="/logotipo-sinfondo.png" alt="WallaShop" class="logo"></router-link>
        </div>
        <div class="header-search">
          <input type="text" id="search-input" placeholder="Busca productos...">
        </div>
        <div id="usrLogedPfp"></div>
        <div class="header-buttons">
          <button id="register-btn" class="btn">Registrarse</button>
          <button id="login-btn" class="btn">Iniciar Sesión</button>
          <div class="bar-wrapper" id="bar-wrapper">
            <div class="bar"></div>
            <div class="bar"></div>
            <div class="bar"></div>
          </div>
          <div class="sidebar-menu" id="sidebar-menu">
            <ul>
              <li><router-link to="/profile">Ver Perfil</router-link></li>
              <li><router-link to="/edit-profile">Editar Perfil</router-link></li>
              <li><a href="#" id="logout-btn">Cerrar Sesión</a></li>
            </ul>
          </div>
        </div>
      </div>
    </header>
  </template>
  
  <script>
  export default {
    name: 'AppHeader',
    mounted() {
      this.initializeHeader();
    },
    methods: {
      initializeHeader() {
        let loginButton = document.getElementById('login-btn');
        let registerButton = document.getElementById('register-btn');
        let barWrapper = document.getElementById("bar-wrapper");
        let sidebarMenu = document.getElementById("sidebar-menu");
        let logoutButton = document.getElementById('logout-btn');
        let click = false;
  
        loginButton.addEventListener('click', function () {
          window.location.href = '/login.html';
        });
  
        registerButton.addEventListener('click', function () {
          window.location.href = '/register.html';
        });
  
        logoutButton.addEventListener('click', function () {
          localStorage.removeItem('user');
          localStorage.removeItem('isLoggedIn');
          window.location.href = '/';
        });
  
        let userLocalStorage = localStorage.getItem('user');
  
        if (userLocalStorage) {
          loginButton.style.display = 'none';
          registerButton.style.display = 'none';
        } else {
          loginButton.style.display = 'block';
          registerButton.style.display = 'block';
        }
  
        barWrapper.onclick = function() {
          click = !click;
          if (click) {
            sidebarMenu.style.display = 'block';
          } else {
            sidebarMenu.style.display = 'none';
          }
        };
  
        window.onclick = function(event) {
          if (!event.target.closest('.bar-wrapper') && !event.target.closest('.sidebar-menu')) {
            sidebarMenu.style.display = 'none';
            click = false;
          }
        };
  
        let user = JSON.parse(localStorage.getItem('user'));
        if (user) {
          let usrLogedPfp = document.getElementById('usrLogedPfp');
          usrLogedPfp.style.display = 'flex';
          let usrName = document.createElement('p');
          usrName.textContent = user.personname;
          usrLogedPfp.appendChild(usrName);
          let pfp = document.createElement('img');
          pfp.src = user.profilepicture;
          pfp.alt = 'Foto de perfil';
          pfp.classList.add('pfp');
          usrLogedPfp.appendChild(pfp);
        }
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
  