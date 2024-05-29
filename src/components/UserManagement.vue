<template>
  <div class="user-management">
    <h1>Gestión de Usuarios</h1>
    <button @click="showCreateForm = true">Crear Usuario</button>

    <div v-if="showCreateForm" class="form-container">
      <h2>Crear Usuario</h2>
      <form @submit.prevent="createUser">
        <label for="email">Email:</label>
        <input type="email" v-model="newUser.email" required>
        
        <label for="password">Contraseña:</label>
        <input type="password" v-model="newUser.password" required>
        
        <label for="personname">Nombre:</label>
        <input type="text" v-model="newUser.personname" required>
        
        <button type="submit">Crear</button>
        <button @click="showCreateForm = false">Cancelar</button>
      </form>
    </div>

    <table>
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
            <button @click="editUser(user)">Editar</button>
            <button @click="deleteUser(user.id)">Borrar</button>
          </td>
        </tr>
      </tbody>
    </table>

    <div v-if="showEditForm" class="form-container">
      <h2>Editar Usuario</h2>
      <form @submit.prevent="updateUser">
        <label for="email">Email:</label>
        <input type="email" v-model="selectedUser.email" required>
        
        <label for="password">Contraseña:</label>
        <input type="password" v-model="selectedUser.password">
        
        <label for="personname">Nombre:</label>
        <input type="text" v-model="selectedUser.personname" required>
        
        <button type="submit">Actualizar</button>
        <button @click="showEditForm = false">Cancelar</button>
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
      fetch('http://18.212.255.200:8080/index.php?path=get-users')
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            this.users = data.users;
          } else {
            console.error('Error fetching users:', data.message);
          }
        })
        .catch(error => console.error('Error fetching users:', error));
    },
    createUser() {
      fetch('http://18.212.255.200:8080/index.php?path=create-user', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(this.newUser)
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
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
        if (data.success) {
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
        if (data.success) {
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
}
.form-container {
  margin-top: 20px;
  padding: 20px;
  border: 1px solid #ccc;
  border-radius: 5px;
}
table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
}
table, th, td {
  border: 1px solid #ddd;
}
th, td {
  padding: 8px;
  text-align: left;
}
button {
  margin-right: 5px;
}
</style>
