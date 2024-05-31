<template>
  <div>
    <AppHeader />
    <div class="admin-dashboard">
      <h1>Panel de Administración</h1>
      <p>Bienvenido al panel de administración. Aquí puedes gestionar el contenido de la plataforma.</p>
      <div class="button-group">
        <button @click="view = 'products'" :class="{ active: view === 'products' }">Ver Productos</button>
        <button @click="view = 'users'" :class="{ active: view === 'users' }">Gestionar Usuarios</button>
      </div>
      <div v-if="view === 'products'">
        <h2>Productos</h2>
        <ProductList />
      </div>
      <div v-else-if="view === 'users'">
        <h2>Usuarios</h2>
        <UserManagement />
      </div>
    </div>
  </div>
  <AppFooter />
</template>

<script>
import AppHeader from '../components/AppHeader.vue';
import ProductList from '../components/ProductList.vue';
import UserManagement from '../components/UserManagement.vue';
import AppFooter from '../components/AppFooter.vue';

export default {
  components: {
    AppHeader,
    ProductList,
    UserManagement,
    AppFooter
  },
  data() {
    return {
      view: '' // No mostrar nada por defecto
    };
  }
};
</script>

<style scoped>
.admin-dashboard {
  padding: 20px;
  max-width: 800px;
  margin: 0 auto;
  text-align: center;
}

.button-group {
  margin-bottom: 20px;
}

button {
  background-color: #0E2945;
  color: white;
  padding: 10px 20px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  margin: 0 10px;
}

button:hover {
  background-color: #8292A4;
}

button.active {
  background-color: #5566c2;
}
</style>
