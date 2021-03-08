<template>
  <div>
    <keep-alive>
      <router-view></router-view>
    </keep-alive>
    <CToaster :autohide="3000">
      <CToast
        v-for="(toast, index) in toasts"
        :key="`toast-${index}`"
        :show="true"
        :header="toast.title"
        :color="toast.color"
      >
        <span v-html="toast.content"></span>
      </CToast>
    </CToaster>
  </div>
</template>

<script>
  export default {
    name: 'app',
    components: {
    },
    data: () => ({
      showSelf: true,
      toasts: []
    }),
    mounted() {
      let vm = this;
      window.addEventListener('resize', () => {
        this.windowWidth = window.innerWidth;
        this.windowHeight = window.innerHeight;
      });
      this.$root.$on('toast', (toast) => {
        vm.toasts.push({
          title: toast.title,
          content: toast.content,
          color: toast.color || undefined
        })
      })
    },
  };
</script>