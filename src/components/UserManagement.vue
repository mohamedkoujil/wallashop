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

        <label for="profilepicture">Foto de perfil:</label>
        <input type="text" v-model="newUser.profilepicture" required>

        <label for="role">Rol:</label>
        <select v-model="newUser.role" required>
          <option value="admin">Administrador</option>
          <option value="user">Usuario</option>
        </select>

        <button type="submit" class="button primary">Crear</button>
        <button @click="showCreateForm = false" class="button secondary">Cancelar</button>
      </form>
    </div>

    <table v-if="users.length">
      <thead>
        <tr>
          <th>Email</th>
          <th>Nombre</th>
          <th>Rol</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="user in users" :key="user.id">
          <td>{{ user.email }}</td>
          <td>{{ user.personname }}</td>
          <td>{{ user.role }}</td>
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

    <div v-if="showEditForm" class="form-container modal">
      <div class="modal-content">
        <span class="close" @click="showEditForm = false">&times;</span>
        <h2>Editar Usuario</h2>
        <form @submit.prevent="updateUser">
          <label for="email">Email:</label>
          <input type="email" v-model="selectedUser.email" required>

          <label for="password">Contraseña:</label>
          <input type="password" v-model="selectedUser.password">

          <label for="personname">Nombre:</label>
          <input type="text" v-model="selectedUser.personname" required>

          <label for="profilepicture">Foto de perfil:</label>
          <input type="text" v-model="selectedUser.profilepicture" required>

          <label for="role">Rol:</label>
          <select v-model="selectedUser.role" required>
            <option value="admin">Administrador</option>
            <option value="user">Usuario</option>
          </select>

          <button type="submit" class="button primary">Actualizar</button>
          <button @click="showEditForm = false" class="button secondary">Cancelar</button>
        </form>
      </div>
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
        personname: '',
        profilepicture: '',
        role: 'user'
      },
      selectedUser: {}
    };
  },
  methods: {
    fetchUsers() {
      fetch('http://44.218.60.222:8080/index.php?path=users')
        .then(response => response.json())
        .then(data => {
          console.log('Fetched users:', data);
          this.users = data;
        })
        .catch(error => console.error('Error fetching users:', error));
    },
    createUser() {
      fetch('http://44.218.60.222:8080/index.php?path=register', {
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
      this.selectedUser = user;
      this.showEditForm = true;
    },
    updateUser() {
      console.log(this.selectedUser);
      fetch(`http://44.218.60.222:8080/index.php?path=update-user&id=${this.selectedUser.id}`, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(this.selectedUser)
      })
        .then(response => response.json())
        .then(data => {
          if (data.status === 'User updated') {
            this.fetchUsers();
            this.showEditForm = false;
          } else {
            alert('Error al actualizar usuario');
          }
        })
        .catch(error => console.error('Error updating user:', error));
    },
    deleteUser(userId) {
      fetch(`http://44.218.60.222:8080/index.php?path=user&id=${userId}`, {
        method: 'DELETE'
      })
        .then(response => response.json())
        .then(data => {
          if (data.status === 'User marked as inactive') {
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

table,
th,
td {
  border: 1px solid #ddd;
}

th,
td {
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

.modal {
  display: flex;
  justify-content: center;
  align-items: center;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
}

.modal-content {
  background-color: #fefefe;
  margin: 15% auto;
  padding: 20px;
  border: 1px solid #888;
  border-radius: 10px;
  width: 50%;
  position: relative;
}

.modal-content .close {
  position: absolute;
  right: 10px;
  top: 10px;
  color: #aaa;
  font-size: 28px;
  font-weight: bold;
  cursor: pointer;
}

.modal-content .close:hover,
.modal-content .close:focus {
  color: black;
}

.modal-content form {
  display: flex;
  flex-direction: column;
}

.modal-content form label {
  margin-bottom: 5px;
  font-weight: bold;
  color: #333;
}

.modal-content form input {
  padding: 10px;
  margin-bottom: 15px;
  border: 1px solid #ccc;
  border-radius: 5px;
}

.modal-content form button {
  margin: 5px 0;
}

.modal-content form .button {
  margin: 0 5px;
  padding: 10px;
  border-radius: 5px;
  cursor: pointer;
  transition: background-color 0.3s;
}

.modal-content form .button.primary {
  background-color: #0E2945;
  color: white;
}

.modal-content form .button.primary:hover {
  background-color: #08223b;
}

.modal-content form .button.secondary {
  background-color: #ccc;
  color: black;
}

.modal-content form .button.secondary:hover {
  background-color: #aaa;
}
</style>
