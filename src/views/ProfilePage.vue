<template>
    <div>
      <h1>Profile Page</h1>
      <!-- Your home page content goes here -->
      <div>
        <h2>{{ user.name }}</h2>
        <p>{{ user.email }}</p>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    name: 'HomePage',
    data() {
      return {
        user: {
          name: '',
          email: '',
        }
      }
    },
    methods: {
  // Función para hacer la solicitud API
  apiRequest() {
    let url = 'http://204.236.196.1:8080/test.php';
    fetch(url)
      .then(response => {
        if (!response.ok) {
          throw new Error('Network response was not ok');
        }
        return response.json();
      })
      .then(data => {
        // Manejar los datos recibidos aquí
        console.log(data[0].email); // Solo imprime los datos en la consola por ahora
        this.user.email = data[0].email;
      })
      .catch(error => {
        console.error('There was a problem with the fetch operation:', error);
      });
  }
},
mounted() {
  // Llamar a la función apiRequest() cuando el componente se monta
  this.apiRequest();
}

  }
  </script>
  
  <style>
  /* Your component styles go here */
  </style>
  