<template>
  <div id="app">
    <AppHeader @search="handleSearch"/>
    <router-view :searchTerm="searchTerm"></router-view>
  </div>
</template>

<script>
import AppHeader from './components/AppHeader.vue';

export default {
  name: 'App',
  components: {
    AppHeader
  },
  data() {
    return {
      searchTerm: ''
    };
  },
  methods: {
    handleSearch(searchTerm) {
      this.searchTerm = searchTerm;
      //console.log('Search term:', searchTerm);
    }
  }
}
</script>

<style>
/* Estilos globales si es necesario */
</style>
