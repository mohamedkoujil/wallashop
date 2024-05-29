<template>
  <div class="user-management">
    <h1>Gestión de Usuarios</h1>
    <button @click="showCreateForm = true" class="button primary">Crear Usuario</button>

    <div v-if="showCreateForm" class="form-container">
      <h2>Crear Usuario</h2>
      <form @submit.prevent="createUser">
        <label for="email">Email:</label>
        <input type="email" v-model="newUser.email" required>
        
        <label for="password">Contraseña:</label>
        <input type="password" v-model="newUser.password" required>
        
        <label for="personname">Nombre:</label>
        <input type="text" v-model="newUser.personname" required>
        
        <button type="submit" class="button primary">Crear</button>
        <button @click="showCreateForm = false" class="button secondary">Cancelar</button>
      </form>
    </div>

    <table v-if="users.length">
      <thead>
        <tr>
          <th>Email</th>
          <th>Nombre</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="user in users" :key="user.id">
          <td>{{ user.email }}</td>
          <td>{{ user.personname }}</td>
          <td>
            <button @click="editUser(user)" class="button secondary">Editar</button>
            <button @click="deleteUser(user.id)" class="button danger">Borrar</button>
          </td>
        </tr>
      </tbody>
    </table>
    <div v-else>
      <p>No hay usuarios disponibles.</p>
    </div>

    <div v-if="showEditForm" class="form-container">
      <h2>Editar Usuario</h2>
      <form @submit.prevent="updateUser">
        <label for="email">Email:</label>
        <input type="email" v-model="selectedUser.email" required>
        
        <label for="password">Contraseña:</label>
        <input type="password" v-model="selectedUser.password">
        
        <label for="personname">Nombre:</label>
        <input type="text" v-model="selectedUser.personname" required>
        
        <button type="submit" class="button primary">Actualizar</button>
        <button @click="showEditForm = false" class="button secondary">Cancelar</button>
      </form>
    </div>
  </div>
</template>

<script>
export default {
  name: 'UserManagement',
  data() {
    return {
      users: [],
      showCreateForm: false,
      showEditForm: false,
      newUser: {
        email: '',
        password: '',
        personname: ''
      },
      selectedUser: {}
    };
  },
  methods: {
    fetchUsers() {
      fetch('http://18.212.255.200:8080/index.php?path=users')
        .then(response => {
          if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
          }
          return response.json();
        })
        .then(data => {
          console.log('Fetched users:', data); // Añadir mensaje de depuración
          this.users = data;
        })
        .catch(error => console.error('Error fetching users:', error));
    },
    createUser() {
      fetch('http://18.212.255.200:8080/index.php?path=register', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(this.newUser)
      })
      .then(response => response.json())
      .then(data => {
        if (data.status === 'success') {
          this.fetchUsers();
          this.showCreateForm = false;
          this.newUser = { email: '', password: '', personname: '' };
        } else {
          alert('Error al crear usuario');
        }
      })
      .catch(error => console.error('Error creating user:', error));
    },
    editUser(user) {
      this.selectedUser = { ...user };
      this.showEditForm = true;
    },
    updateUser() {
      fetch(`http://18.212.255.200:8080/index.php?path=update-user&id=${this.selectedUser.id}`, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(this.selectedUser)
      })
      .then(response => response.json())
      .then(data => {
        if (data.status === 'success') {
          this.fetchUsers();
          this.showEditForm = false;
        } else {
          alert('Error al actualizar usuario');
        }
      })
      .catch(error => console.error('Error updating user:', error));
    },
    deleteUser(userId) {
      fetch(`http://18.212.255.200:8080/index.php?path=delete-user&id=${userId}`, {
        method: 'DELETE'
      })
      .then(response => response.json())
      .then(data => {
        if (data.status == 'User deleted') {
          this.fetchUsers();
        } else {
          alert('Error al borrar usuario');
        }
      })
      .catch(error => console.error('Error deleting user:', error));
    }
  },
  mounted() {
    this.fetchUsers();
  }
};
</script>

<style scoped>
.user-management {
  padding: 20px;
  max-width: 800px;
  margin: 0 auto;
  background-color: #f9f9f9;
  border-radius: 10px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.form-container {
  margin-top: 20px;
  padding: 20px;
  background-color: #ffffff;
  border: 1px solid #ccc;
  border-radius: 10px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
  background-color: #ffffff;
  border-radius: 10px;
  overflow: hidden;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

table, th, td {
  border: 1px solid #ddd;
}

th, td {
  padding: 10px;
  text-align: left;
}

button {
  margin-right: 5px;
  padding: 5px 10px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  transition: background-color 0.3s;
}

.button {
  padding: 10px 20px;
  border-radius: 5px;
  cursor: pointer;
  transition: background-color 0.3s;
}

.button.primary {
  background-color: #0E2945;
  color: white;
}

.button.primary:hover {
  background-color: #08223b;
}

.button.secondary {
  background-color: #ccc;
  color: black;
}

.button.secondary:hover {
  background-color: #aaa;
}

.button.danger {
  background-color: #ff4d4d;
  color: white;
}

.button.danger:hover {
  background-color: #cc0000;
}
</style>
