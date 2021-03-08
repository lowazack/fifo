<template>
  <div class="c-app" :class="{ 'c-dark-theme': $store.state.darkMode }">
    <Sidebar/>
    <Aside/>
    <CWrapper>
      <Header/>
      <div class="c-body">
        <main class="c-main">
          <CContainer fluid>
            <router-view :key="$route.path"></router-view>
          </CContainer>
        </main>
      </div>
      <Footer />
    </CWrapper>
  </div>
</template>

<script>
import Sidebar from './Sidebar'
import Header from './Header'
import Footer from './Footer'
import Aside from './Aside'

export default {
  name: 'Container',
  components: {
    Sidebar,
    Header,
    Footer,
    Aside
  }
}
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s;
}
.fade-enter,
.fade-leave-to {
  opacity: 0;
}
</style>
