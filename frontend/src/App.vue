<template>
  <div id="app">
    <b-nav id="nav">
      <router-link to="/">Register</router-link>
      <span class="pl-1 pr-1">|</span>
      <router-link to="/about">About</router-link>
    </b-nav>
    <b-container>
      <router-view />
    </b-container>
  </div>
</template>

<style lang="scss">
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  color: #2c3e50;
}

#nav {
  padding: 30px;

  a {
    font-weight: bold;
    color: #2c3e50;

    &.router-link-exact-active {
      color: #42b983;
    }
  }
}
</style>
